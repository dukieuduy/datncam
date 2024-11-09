<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>Document</title>
</head>
<body>
    <header>
        @include('client.inc.header')
        @include('client.inc.style')
    </header>
    <main class="content">
        @yield('content')
    </main>
    <footer>
        @include('client.inc.footer')
        @include('client.inc.script')
    </footer>
</body>
</html>
