<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .navbar {
            background: #6c757d;
            min-height: 48px;
        }

        .container {
            max-width: 1200px;
            margin: 2em auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            padding: 2em 2.5em;
        }

        h2 {
            color: #4e54c8;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/" style="font-size: 1.2em;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-book" viewBox="0 0 16 16" style="margin-right: 8px;">
                    <path d="M1 2.828c.885-.37 2.154-.829 3.388-.829 1.234 0 2.503.46 3.388.829V13.17c-.885.37-2.154.83-3.388.83-1.234 0-2.503-.46-3.388-.83V2.828zM8 13.17c.885.37 2.154.83 3.388.83 1.234 0 2.503-.46 3.388.83V2.828c-.885-.37-2.154-.829-3.388-.829-1.234 0-2.503.46-3.388.829v10.342z" />
                </svg>
                <span style="font-weight: 600; letter-spacing: 2px;">Book Store</span>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/books">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="/users">Users</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <?= htmlspecialchars($_SESSION['user']['name']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php include __DIR__ . '/../partials/flash-messages.php'; ?>

        <?php if (isset($_SESSION['user'])): ?>
            <div class="mb-4">
                <div class="alert alert-info">
                    <h4>Welcome <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h4>
                    <p class="mb-0">Welcome to your Bookstore Dashboard</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Dashboard Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" class="bi bi-book" viewBox="0 0 16 16">
                                    <path d="M1 2.828c.885-.37 2.154-.829 3.388-.829 1.234 0 2.503.46 3.388.829V13.17c-.885.37-2.154.83-3.388.83-1.234 0-2.503-.46-3.388-.83V2.828zM8 13.17c.885.37 2.154.83 3.388.83 1.234 0 2.503-.46 3.388-.83V2.828c-.885-.37-2.154-.829-3.388-.829-1.234 0-2.503.46-3.388.829v10.342z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="card-title text-primary mb-2" style="font-weight: 600; font-size: 2.5rem;">
                            <?= number_format($stats['total_books']) ?>
                        </h3>
                        <h5 class="card-subtitle text-muted mb-3" style="font-weight: 500;">Total Books</h5>
                        <p class="card-text text-secondary small mb-0">
                            Manage your complete book inventory
                        </p>
                        <div class="mt-3">
                            <a href="/books" class="btn btn-outline-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="margin-right: 4px;">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg>
                                View All Books
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" class="bi bi-people" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="card-title text-primary mb-2" style="font-weight: 600; font-size: 2.5rem;">
                            <?= number_format($stats['total_users']) ?>
                        </h3>
                        <h5 class="card-subtitle text-muted mb-3" style="font-weight: 500;">Total Users</h5>
                        <p class="card-text text-secondary small mb-0">
                            Manage registered users and accounts
                        </p>
                        <div class="mt-3">
                            <a href="/users" class="btn btn-outline-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="margin-right: 4px;">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg>
                                View All Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>