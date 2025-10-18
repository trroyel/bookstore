<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar { background: #6c757d; min-height: 48px; }
        .hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 3rem 0; border-radius: 8px; margin-bottom: 2rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/" style="font-size: 1.2em;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-book" viewBox="0 0 16 16" style="margin-right: 8px;">
                    <path d="M1 2.828c.885-.37 2.154-.829 3.388-.829 1.234 0 2.503.46 3.388.829V13.17c-.885.37-2.154.83-3.388.83-1.234 0-2.503-.46-3.388-.83V2.828zM8 13.17c.885.37 2.154.83 3.388.83 1.234 0 2.503-.46 3.388-.83V2.828c-.885-.37-2.154-.829-3.388-.829-1.234 0-2.503.46-3.388.829v10.342z"/>
                </svg>
                <span style="font-weight: 600; letter-spacing: 2px;">Book Store</span>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/books">Books</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <?= htmlspecialchars($_SESSION['user']['name']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/users/<?= htmlspecialchars($_SESSION['user']['id']) ?>">My Profile</a></li>
                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php include __DIR__ . '/../partials/flash-messages.php'; ?>

        <div class="hero text-center">
            <h1 class="display-4">Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
            <p class="lead">Explore our collection of books</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#667eea" class="bi bi-book mb-3" viewBox="0 0 16 16">
                            <path d="M1 2.828c.885-.37 2.154-.829 3.388-.829 1.234 0 2.503.46 3.388.829V13.17c-.885.37-2.154.83-3.388.83-1.234 0-2.503-.46-3.388-.83V2.828zM8 13.17c.885.37 2.154.83 3.388.83 1.234 0 2.503-.46 3.388-.83V2.828c-.885-.37-2.154-.829-3.388-.829-1.234 0-2.503.46-3.388.829v10.342z"/>
                        </svg>
                        <h3><?= count($books) ?></h3>
                        <p class="text-muted">Books Available</p>
                        <a href="/books" class="btn btn-primary btn-sm">Browse Books</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#f5576c" class="bi bi-person mb-3" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        </svg>
                        <h5>My Profile</h5>
                        <p class="text-muted">Manage your account</p>
                        <a href="/users/<?= htmlspecialchars($user['id']) ?>" class="btn btn-outline-primary btn-sm">View Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4caf50" class="bi bi-check-circle mb-3" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                        <h5>Available Books</h5>
                        <p class="text-muted"><?= count(array_filter($books, fn($b) => $b['available'])) ?> books ready</p>
                        <a href="/books" class="btn btn-outline-success btn-sm">See Available</a>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3">Recent Books</h3>
        <div class="row">
            <?php foreach (array_slice($books, 0, 6) as $book): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($book['author']) ?></p>
                            <p class="small">
                                <span class="badge <?= $book['available'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $book['available'] ? 'Available' : 'Not Available' ?>
                                </span>
                            </p>
                            <a href="/books/<?= htmlspecialchars($book['id']) ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="text-white text-center py-4 mt-5" style="background: #6c757d;">
        <div class="container">
            <p class="mb-0">&copy; 2025 Book Store. All rights reserved.</p>
            <p class="small mb-0">Manage your reading collection with ease</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
