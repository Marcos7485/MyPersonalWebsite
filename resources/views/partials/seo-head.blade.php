{{-- Metas + Open Graph para bots, previews (LinkedIn/WhatsApp) y Google --}}
@php
    $seoTitle = $seo['title'] ?? 'Dragon Rojo Software | Softwares a medida';
    $seoDescription = $seo['description'] ?? 'Dragon Rojo Software — desarrollo web fullstack. Softwares propios: iQ Athletic (gestión para centros deportivos) y Ecommerce (tienda online lista para vender).';
    $seoUrl = $seo['url'] ?? url()->current();
    $seoImage = $seo['image'] ?? url('/images/drs.webp');
    $seoSiteName = $seo['site_name'] ?? 'Dragon Rojo Software';
    $seoLocale = $seo['locale'] ?? 'es_AR';
@endphp

<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<meta name="author" content="Marcos Gonzalez — Dragon Rojo Software">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $seoUrl }}">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $seoSiteName }}">
<meta property="og:locale" content="{{ $seoLocale }}">
<meta property="og:url" content="{{ $seoUrl }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:image:alt" content="{{ $seoSiteName }}">

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">
