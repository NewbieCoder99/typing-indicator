@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            @if(request()->user_id)
                @php
                    $user = \App\User::find(request()->user_id);
                @endphp
                <div class="col-md-8">
                    <a href="/home" class="btn btn-success">
                        Kembali
                    </a>
                    <br><br>
                    <div class="card">

                        <div class="card-header">
                            <b>{{ $user->email }}</b><br>
                            <em id="typingInfo"></em>
                        </div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <textarea class="form-control" name="message" id="message" placeholder="Type a message ..." is-typing="0" onkeyup="_isTyping()" onblur="_isNotTyping()"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-8">

                    @php
                        $users = \App\User::all();
                    @endphp

                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>User</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @if($user->id != Auth::user()->id)
                                    <tr>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ url()->current() }}?user_id={{ $user->id }}" class="btn btn-success">
                                                <i class="fa fa-comments"></i> Chat
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')

    <script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>

    @if(request()->user_id)

        <script src="https://js.pusher.com/4.4/pusher.min.js"></script>

        <script type="text/javascript">

            Pusher.logToConsole = true;
            var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                forceTLS: true
            });

            var subscribeTypingEvent = pusher.subscribe('is-typing-event_{{ $user->id }}-{{ Auth::user()->id }}');

            subscribeTypingEvent.bind('App\\Events\\TypingEvent', function(data) {
                var typingInfo = jQuery('#typingInfo');
                if(data.isTyping == 1) {
                    typingInfo.html(`<em>Typing...</em>`);
                } else {
                    typingInfo.html(``);
                }
            });

            function _isTyping() {
                var isTyping = jQuery('#message');
                if(isTyping.attr('is-typing') == 0) {
                    isTyping.attr('is-typing', 1);
                    jQuery.ajax({
                        url : '{{ route('event.typing') }}',
                        data : 'isTyping=' + isTyping.attr('is-typing') + '&user_id={{ $user->id }}',
                        method : 'post',
                        headers : {
                            'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                        },
                        success : function(response) {
                            console.log(response);
                        }
                    });
                }
            }

            function _isNotTyping() {
                var isTyping = jQuery('#message');
                if(isTyping.val() == '' && isTyping.attr('is-typing') == 1) {
                    isTyping.attr('is-typing', 0);
                    jQuery.ajax({
                        url : '{{ route('event.typing') }}',
                        data : 'isTyping=' + isTyping.attr('is-typing') + '&user_id={{ $user->id }}',
                        method : 'post',
                        headers : {
                            'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                        },
                        success : function(response) {
                            console.log(response);
                        }
                    });
                }
            }

        </script>
    @endif
@endsection