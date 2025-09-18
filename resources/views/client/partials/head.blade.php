<head>
    <link rel="icon" href="{{ asset($favicon ?? 'client/assets/images/favicon/2.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset($favicon ?? 'client/assets/images/favicon/2.png') }}" />
    <meta name="theme-color" content="{{ $themeColor ?? '#e22454' }}" />
    <meta name="msapplication-TileImage" content="{{ asset($favicon ?? 'client/assets/images/favicon/2.png') }}" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Voxo' }}">
    <meta name="keywords" content="{{ $keywords ?? 'Voxo' }}">
    <meta name="author" content="{{ $author ?? 'Voxo' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Index' }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- bootstrap css -->
    <link id="rtl-link" rel="stylesheet" type="text/css"
        href="{{ asset('client/assets/css/vendors/bootstrap.css') }}">

    <!-- font-awesome css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/font-awesome.css') }}">

    <!-- feather icon css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/feather-icon.css') }}">

    <!-- animation css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/animate.css') }}">

    <!-- slick css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/slick/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/slick/slick-theme.css') }}">

    <!-- Theme css -->
    <link id="color-link" rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/demo2.css') }}">
    <link id="color-link" rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/custom.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/ion.rangeSlider.min.css') }}" />
</head>
