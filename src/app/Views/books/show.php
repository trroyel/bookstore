<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details - Bookstore</title>
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
            max-width: 800px;
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
                    <path d="M1 2.828c.885-.37 2.154-.829 3.388-.829 1.234 0 2.503.46 3.388.829V13.17c-.885.37-2.154.83-3.388.83-1.234 0-2.503-.46-3.388-.83V2.828zM8 13.17c.885.37 2.154.83 3.388.83 1.234 0 2.503-.46 3.388-.83V2.828c-.885-.37-2.154-.829-3.388-.829-1.234 0-2.503.46-3.388.829v10.342z" />
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
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Book Details</h2>
            <a href="/books" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Back to Books
            </a>
        </div>

        <?php if ($book): ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title text-primary"><?= htmlspecialchars($book['title']) ?></h3>
                            <p class="text-muted mb-3">by <?= htmlspecialchars($book['author']) ?></p>

                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>ISBN:</strong></div>
                                <div class="col-sm-9"><code><?= htmlspecialchars($book['isbn']) ?></code></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Pages:</strong></div>
                                <div class="col-sm-9"><?= htmlspecialchars($book['pages']) ?> pages</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Status:</strong></div>
                                <div class="col-sm-9">
                                    <?php if ($book['available']): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Not Available</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <div class="book-cover mb-3" style="width: 120px; height: 180px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: bold; margin: 0 auto;">
                                📚<br>Book<br>Cover
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="/books/<?= htmlspecialchars($book['id']) ?>/edit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.854a.5.5 0 0 1 .708 0l2.292 2.292a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-4 1a.5.5 0 0 1-.62-.62l1-4a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 12.5 5.793 10.207 3.5l1-1zm1.586 3L10.5 3.207 3 10.707V13h2.293l7.5-7.5z" />
                            </svg>
                            Edit Book
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <h4>Book Not Found</h4>
                <p>The requested book could not be found.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>