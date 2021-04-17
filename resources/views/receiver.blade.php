<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Receiver</title>

{{--    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>--}}
</head>
<body>
<main id="app">
    <div id="messages"></div>
</main>

<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>

<script
        src="https://code.jquery.com/jquery-2.2.4.js"
        integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
        crossorigin="anonymous"></script>
<script type="text/javascript">
  window.Echo.channel('EveryoneChannel')
    .listen('.EveryoneMessage', function (e) {
      $('#messages').append('<p>' + e.message + '</p>');
    })
</script>
</body>
</html>