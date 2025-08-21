<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Voxo admin template">
    <meta name="keywords" content="admin template, Voxo dashboard">
    <meta name="author" content="pixelstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon (sửa đường dẫn) -->
    <link rel="icon" href="{{ asset('admin/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.png') }}" type="image/x-icon">
    <!-- Thêm vào trong thẻ <head> hoặc trước </body> -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Voxo - Dashboard</title>
    <!-- Google font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200&amp;family=Nunito:wght@300;400;600;700;800&amp;display=swap"
        rel="stylesheet">

    <!-- CSS Assets (sửa tất cả đường dẫn) -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/linearicon.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vendors/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vendors/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/ratio.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/vector-map.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
