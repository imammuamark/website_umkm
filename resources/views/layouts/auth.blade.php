<!DOCTYPE html>
<html lang="id" class="min-h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Panama Corner</title>
    @livewireStyles
    @filamentStyles
</head>
<body class="min-h-screen antialiased bg-gray-50 dark:bg-gray-950">
    {{ $slot }}
    
    @livewireScripts
    @filamentScripts
</body>
</html>
