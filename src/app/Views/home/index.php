<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore - Your Online Book Destination</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .welcome {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }

        .feature-card {
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">📚 BookStore</a>
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                    <a class="nav-link" href="/books">Books</a>
                    <?php if (hasPermission('user:read')): ?>
                    <a class="nav-link" href="/users">Users</a>
                    <?php endif; ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <?= htmlspecialchars($_SESSION['user']['name']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="nav-link" href="/login">Login</a>
                    <a class="btn btn-primary px-3 ms-2" href="/signup">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section class="welcome text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Welcome to BookStore</h1>
            <p class="lead mb-4">Discover thousands of books and manage your reading journey</p>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/dashboard" class="btn btn-light btn-lg me-3">Go to Dashboard</a>
                <a href="/books" class="btn btn-outline-light btn-lg">Browse Books</a>
            <?php else: ?>
                <a href="/signup" class="btn btn-light btn-lg me-3">Get Started</a>
                <a href="/login" class="btn btn-outline-light btn-lg">Login</a>
            <?php endif; ?>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm feature-card">
                        <div class="card-body p-4">
                            <div class="fs-1 mb-3">📖</div>
                            <h5>Vast Collection</h5>
                            <p class="text-muted">Browse through thousands of books across all genres</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm feature-card">
                        <div class="card-body p-4">
                            <div class="fs-1 mb-3">⚡</div>
                            <h5>Easy Management</h5>
                            <p class="text-muted">Organize and track your books with our simple tools</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm feature-card">
                        <div class="card-body p-4">
                            <div class="fs-1 mb-3">🔒</div>
                            <h5>Secure Access</h5>
                            <p class="text-muted">Your data is safe with our secure platform</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row text-center">
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm feature-card">
                                <div class="card-body p-4">
                                    <div class="fs-1 mb-3">📕</div>
                                    <h5><?= htmlspecialchars($book['title']) ?></h5>
                                    <p class="text-muted mb-1">by <?= htmlspecialchars($book['author']) ?></p>
                                    <small class="text-secondary"><?= $book['pages'] ?> pages</small>
                                    <div class="mt-2">
                                        <?php if ($book['available']): ?>
                                            <span class="badge bg-success">Available</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Not Available</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 BookStore. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>