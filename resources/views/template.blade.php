<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Sidebar */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background-color: #0d6efd;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar a {
            color: #fff;
            text-decoration: none;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
        }

        /* Content */
        #content {
            flex: 1;
            padding: 20px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            #sidebar {
                position: fixed;
                left: -250px;
                top: 0;
                height: 100%;
                z-index: 1000;
            }

            #sidebar.active {
                left: 0;
            }
        }

        .navbar-brand {
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 15px 0;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    @include('partials.navbar')

    <div class="d-flex">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Content -->
        @include('partials.content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    </script>
</body>

</html>
