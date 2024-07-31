<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AD Service')</title>
    <!-- Add your CSS files here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; flex-direction: column; height: 100vh;">
    <header>
        <!-- Add navigation, logo, or other header elements here -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-bottom: 20px; flex-shrink: 0;">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ad_web_manage_popup') }}">Create Popup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ad_web_list_popup') }}">List Popup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout.post') }}">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container d-flex">
        <div style="flex: 1; margin-right: 20px;">
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <footer class="mt-5 text-center">
        <p>&copy; {{ date('Y') }} My Application. All rights reserved.</p>
    </footer>
    <!-- Add your JavaScript files here -->
    <script>
        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: "error",
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                Swal.fire({
                    icon: "error",
                    title: "{{ $error }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            @endforeach
        @endif
    </script>
</body>

</html>