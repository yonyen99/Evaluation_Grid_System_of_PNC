<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Evaluation Grid System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('stylesheet')
</head>

<body style="font-family: 'Poppins', sans-serif;">

    <div class="container-fluid full-height">
        <div class="row h-100">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                @include('partials.sidebar')
            </nav>

            <!-- Main Panel -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @include('partials.navbar')

                <div class="content-area py-3">
                    @yield('content')
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    @include('partials.footer')
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src=" {{ asset('dashboard/js/core/jquery.min.js') }}"></script>
    @yield('script')
</body>

</html>
