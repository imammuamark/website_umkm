@php
    $markClass = $class ?? 'h-10 w-10 rounded-xl bg-primary text-white';
    $markStyle = $style ?? null;
@endphp
<span class="inline-flex shrink-0 items-center justify-center {{ $markClass }}" @if($markStyle) style="{{ $markStyle }}" @endif aria-hidden="true">
    <svg class="h-[58%] w-[58%]" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M7.5 13h14v4.2a7 7 0 0 1-7 7 7 7 0 0 1-7-7V13Z"/>
        <path d="M21.5 15h1.7a3.1 3.1 0 0 1 0 6.2h-2.9M6 26h18"/>
        <path d="M11 5.5c-1.3 1.3 1.3 2.3 0 3.7M16 4.5c-1.5 1.5 1.5 2.5 0 4.2"/>
    </svg>
</span>
