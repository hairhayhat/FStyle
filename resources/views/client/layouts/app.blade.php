<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from themes.pixelstrap.com/voxo/front-end/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Nov 2024 03:39:49 GMT -->

@include('client.partials.head')

<body class="theme-color2 light ltr">

    <!-- header start -->
    @include('client.partials.header')
    <!-- header end -->

    @if (session('success'))
        <script>
            Swal.fire({
                title: "Thành công",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: "Lỗi",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif

    @yield('content')

    <!-- service section start -->
    @include('client.partials.service')
    <!-- service section end -->

    <!-- footer start -->
    @include('client.partials.footer')
    <!-- footer end -->

    <!-- tap to top Section Start -->
    <div class="tap-to-top">
        <a href="#home">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
    <!-- tap to top Section End -->

    <div class="bg-overlay"></div>

    @include('client.partials.scripts')

    @vite(['resources/js/app.js'])

    @yield('scripts')

</body>

<!-- Mirrored from themes.pixelstrap.com/voxo/front-end/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Nov 2024 03:40:32 GMT -->

</html>
