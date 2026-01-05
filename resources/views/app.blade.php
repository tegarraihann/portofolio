<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        {{-- Open Graph Meta Tags --}}
        @php
            $meta = $meta ?? null;
        @endphp
        @if(!empty($meta))
            {{-- Basic Meta Tags --}}
            <meta name="description" content="{{ $meta['description'] ?? '' }}">

            {{-- Open Graph / Facebook --}}
            <meta property="og:type" content="{{ $meta['type'] ?? 'website' }}">
            <meta property="og:url" content="{{ $meta['url'] ?? request()->url() }}">
            <meta property="og:title" content="{{ $meta['title'] ?? config('app.name') }}">
            <meta property="og:description" content="{{ $meta['description'] ?? '' }}">
            <meta property="og:site_name" content="{{ $meta['site_name'] ?? config('app.name') }}">
            @if(!empty($meta['image']))
                <meta property="og:image" content="{{ $meta['image'] }}">
                @if(!empty($meta['image_type']))
                    <meta property="og:image:type" content="{{ $meta['image_type'] }}">
                @endif
                <meta property="og:image:width" content="{{ $meta['image_width'] ?? 1200 }}">
                <meta property="og:image:height" content="{{ $meta['image_height'] ?? 630 }}">
            @endif

            {{-- Twitter Cards --}}
            <meta name="twitter:card" content="{{ !empty($meta['image']) ? 'summary_large_image' : 'summary' }}">
            <meta name="twitter:url" content="{{ $meta['url'] ?? request()->url() }}">
            <meta name="twitter:title" content="{{ $meta['title'] ?? config('app.name') }}">
            <meta name="twitter:description" content="{{ $meta['description'] ?? '' }}">
            @if(!empty($meta['image']))
                <meta name="twitter:image" content="{{ $meta['image'] }}">
            @endif

            {{-- Article Specific Meta --}}
            @if(($meta['type'] ?? null) === 'article')
                @if(!empty($meta['published_time']))
                    <meta property="article:published_time" content="{{ $meta['published_time'] }}">
                @endif
                @if(!empty($meta['modified_time']))
                    <meta property="article:modified_time" content="{{ $meta['modified_time'] }}">
                @endif
                @if(!empty($meta['section']))
                    <meta property="article:section" content="{{ $meta['section'] }}">
                @endif
                @if(!empty($meta['tags']))
                    @foreach($meta['tags'] as $tag)
                        <meta property="article:tag" content="{{ $tag }}">
                    @endforeach
                @endif
            @endif
        @else
            {{-- Default Meta Tags untuk halaman lain --}}
            <meta name="description" content="Portfolio dan blog personal - {{ config('app.name') }}">
            <meta property="og:type" content="website">
            <meta property="og:url" content="{{ request()->url() }}">
            <meta property="og:title" content="{{ config('app.name') }}">
            <meta property="og:description" content="Portfolio dan blog personal - {{ config('app.name') }}">
            <meta property="og:site_name" content="{{ config('app.name') }}">

            <meta name="twitter:card" content="summary">
            <meta name="twitter:title" content="{{ config('app.name') }}">
            <meta name="twitter:description" content="Portfolio dan blog personal - {{ config('app.name') }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
