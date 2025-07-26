<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="@csrf">
    <title>{{{ $title }}}</title>
    <link rel="icon" href="{{ Route::assets('PEF_LOGO.webp') }}" type="webp">
    @css('bootstrap-v5.3.3/css/bootstrap.min.css')
    @css('bootstrap-icons-v1.11.3/font/bootstrap-icons.min.css')
</head>

<body>
    @include('admin.header')

    @yield('content')

    @js('bootstrap-v5.3.3/js/bootstrap.bundle.min.js')
    <script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    </script>
</body>

</html>