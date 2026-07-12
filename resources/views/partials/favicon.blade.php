@php($faviconUrl = \App\Models\SiteSetting::faviconUrl())
<link rel="icon" href="{{ $faviconUrl }}" sizes="any">
<link rel="shortcut icon" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" href="{{ $faviconUrl }}">
