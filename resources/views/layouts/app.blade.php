<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pandawa SMALA - Supporter Resmi SMA 5</title>
    <script src="/_sdk/element_sdk.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Perbaikan di sini -->
    <link href="{{ asset('assets/frame.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/script.js') }}" defer></script>
    <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>


<body class="bg-white">

    @include('partials.navbar')

    <main class="pt-20">
        @yield('content')
    </main>

    @include('partials.footer')


</body>