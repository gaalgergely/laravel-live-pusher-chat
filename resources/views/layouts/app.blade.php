<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('dist/js/jquery.min.js') }}"></script>

    <script>
        var rec_id = '';
        var authuser = {{ auth()->user()->id ?? false }};

        $(document).ready(function () {

            $.ajaxSetup({
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') }
            });

            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            var pusher = new Pusher('77451f34b73e728d4bf0', {
                cluster: 'eu'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
                //alert(JSON.stringify(data));

                if (authuser==data.sender) {
                    //alert('authuseridsender');
                    $('#' + data.receiver).click();

                } else if (authuser==data.receiver) {
                    if (rec_id==data.sender) {

                        $('#' + data.sender).click();
                    } else {

                        var pendmess = parseInt($('#' + data.sender).find('.pendingmessages').html());

                        if (pendmess) {

                            $('#' + data.sender).find('.pending').html(pendmess + 1);
                        } else {

                            $('#' + data.sender).append('<span class="pendingmessages">1</span>');
                        }
                    }
                }
            });

            $('.oneuser').click(function () {

                $('.oneuser').removeClass('active');
                $(this).addClass('active');

                rec_id = $(this).attr('id');

                $.ajax({
                    type: 'get',
                    url: 'communicationmessage/' + rec_id,
                    data: '',
                    cache: false,
                    success: function (data) {

                        $('#communicationmessages').html(data);

                        rolltobottom();
                    }
                });
            });

            $(document).on('keyup', '.text-input input', function (e) {

                var communicationmessage = $(this).val();

                if (e.keyCode == 13 && communicationmessage!='' && rec_id) {

                    $(this).val('');

                    var datareceive = "rec_id=" + rec_id + "&communicationmessage=" + communicationmessage;

                    $.ajax({
                        type: 'post',
                        'url': 'communicationmessage',
                        data: datareceive,
                        cache: false,
                        success: function (data) {

                        },
                        error: function (jqXHR, status, err) {

                        },

                        complete: function () {
                            rolltobottom();
                        }
                    });
                }
            })
        });

        function rolltobottom () {
            $('.communicationmessage-wrapper').animate({
                scrollTo: $('.communicationmessage-wrapper').get(0).scrollHeight
            }, 50)
        }

    </script>
</body>
</html>
