<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    @include('partials.favicon')
    <title>Preview Footer</title>
    @vite(['resources/css/app.css'])
    <style>
        html, body { margin: 0; min-height: 100%; background: #09121f; }
        body { font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body>
    @include('partials.footer')
</body>
</html>
