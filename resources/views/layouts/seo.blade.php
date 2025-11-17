@php
    $defaultTitle = ($schoolProfile->school_name ?? config('app.name', 'Galeri Sekolah'));
    $title = trim($__env->yieldContent('meta_title', $defaultTitle));
    $desc = trim($__env->yieldContent('meta_description', 'Galeri, berita, dan informasi SMK Negeri 4 Kota Bogor.'));
    $image = trim($__env->yieldContent('meta_image', asset('favicon.ico')));
    $url = url()->current();
@endphp

<meta name="description" content="{{ $desc }}">
<link rel="canonical" href="{{ $url }}" />

<!-- OpenGraph -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:image" content="{{ $image }}">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $desc }}">
<meta name="twitter:image" content="{{ $image }}">


