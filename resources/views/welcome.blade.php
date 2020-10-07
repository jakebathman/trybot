<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

<!-- HTML Meta Tags -->
<title>TryBot2000</title>
<meta name="description" content="TryBot2000">

<!-- Google / Search Engine Tags -->
<meta itemprop="name" content="TryBot2000">
<meta itemprop="description" content="TryBot2000">
<meta itemprop="image" content="{{ url('img/trybot-circle.png') }}">

<!-- Facebook Meta Tags -->
<meta property="og:url" content="https://jake.cat">
<meta property="og:type" content="website">
<meta property="og:title" content="TryBot2000">
<meta property="og:description" content="TryBot2000">
<meta property="og:image" content="{{ url('img/trybot-circle.png') }}">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="TryBot2000">
<meta name="twitter:description" content="TryBot2000">
<meta name="twitter:image" content="{{ url('img/trybot-circle.png') }}">

<!-- Meta Tags Generated via http://heymeta.com -->


        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #54c1a9;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .meow {
                max-width: 90%;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <img class="trybot2000" src="img/trybot-circle.png" />
                </div>
            </div>
        </div>
    </body>
</html>
