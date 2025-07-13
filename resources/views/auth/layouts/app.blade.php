<!DOCTYPE html>
<html lang="en">

@include('auth.partials.head')

<body>

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

    <!-- login page start-->
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                @yield('content')
            </div>
        </div>
        <!-- latest jquery-->
        @include('auth.partials.scripts')
    </div>
</body>


<!-- Mirrored from themes.pixelstrap.com/voxo/back-end/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Nov 2024 03:24:47 GMT -->

</html>
