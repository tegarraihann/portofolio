<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        {{-- Open Graph Meta Tags untuk Article Pages --}}
        @if(Route::is('articles.show'))
            @php
                $slug = request()->segment(2);
                $article = \App\Models\Article::where('slug', $slug)->where('status', 'published')->first();
            @endphp
            @if($article)
                {{-- Basic Meta Tags --}}
                <meta name="description" content="{{ $article->excerpt ?: strip_tags(substr($article->content, 0, 160)) }}">

                {{-- Open Graph / Facebook --}}
                <meta property="og:type" content="article">
                <meta property="og:url" content="{{ request()->url() }}">
                <meta property="og:title" content="{{ $article->title }}">
                <meta property="og:description" content="{{ $article->excerpt ?: strip_tags(substr($article->content, 0, 160)) }}">
                <meta property="og:site_name" content="{{ config('app.name') }}">
                @if($article->thumbnail_path)
                    <meta property="og:image" content="{{ asset('storage/' . ltrim($article->thumbnail_path, '/')) }}">
                    <meta property="og:image:type" content="image/jpeg">
                    <meta property="og:image:width" content="1200">
                    <meta property="og:image:height" content="630">
                @endif

                {{-- Twitter Cards --}}
                <meta name="twitter:card" content="summary_large_image">
                <meta name="twitter:url" content="{{ request()->url() }}">
                <meta name="twitter:title" content="{{ $article->title }}">
                <meta name="twitter:description" content="{{ $article->excerpt ?: strip_tags(substr($article->content, 0, 160)) }}">
                @if($article->thumbnail_path)
                    <meta name="twitter:image" content="{{ asset('storage/' . ltrim($article->thumbnail_path, '/')) }}">
                @endif

                {{-- Article Specific Meta --}}
                <meta property="article:published_time" content="{{ $article->created_at->toISOString() }}">
                <meta property="article:modified_time" content="{{ $article->updated_at->toISOString() }}">
                @if($article->category)
                    <meta property="article:section" content="{{ $article->category->name }}">
                @endif
                @if($article->tags && $article->tags->count() > 0)
                    @foreach($article->tags as $tag)
                        <meta property="article:tag" content="{{ $tag->name }}">
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

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
