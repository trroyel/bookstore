<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($isEdit) && $isEdit ? 'Edit' : 'Create' ?> Book - Bookstore</title>
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

        .form-control:focus {
            border-color: #4e54c8;
            box-shadow: 0 0 0 0.2rem rgba(78, 84, 200, 0.25);
        }

        .btn-primary {
            background-color: #4e54c8;
            border-color: #4e54c8;
        }

        .btn-primary:hover {
            background-color: #3d4db8;
            border-color: #3d4db8;
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
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/books">Books</a></li>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16" style="margin-right: 8px;">
                    <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                </svg>
                <?= isset($isEdit) && $isEdit ? 'Edit' : 'Create New' ?> Book
            </h2>
            <a href="/books" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16" style="margin-right: 4px;">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Back to Books
            </a>
        </div>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <h6>Please fix the following errors:</h6>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= isset($isEdit) && $isEdit ? '/books/' . $data['id'] . '/update' : '/books' ?>" id="createBookForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16" style="margin-right: 4px;">
                                <path d="M1 2.828c.885-.37 2.154-.829 3.388-.829 1.234 0 2.503.46 3.388.829V13.17c-.885.37-2.154.83-3.388.83-1.234 0-2.503-.46-3.388-.83V2.828zM8 13.17c.885.37 2.154.83 3.388.83 1.234 0 2.503-.46 3.388.83V2.828c-.885-.37-2.154-.829-3.388-.829-1.234 0-2.503.46-3.388.829v10.342z" />
                            </svg>
                            Book Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="<?= htmlspecialchars($data['title'] ?? '') ?>"
                            placeholder="Enter book title" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="author" class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16" style="margin-right: 4px;">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                            </svg>
                            Author <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="author" name="author"
                            value="<?= htmlspecialchars($data['author'] ?? '') ?>"
                            placeholder="Enter author name" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="isbn" class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upc" viewBox="0 0 16 16" style="margin-right: 4px;">
                                <path d="M2 2h1v12H2V2zm3 0h1v12H5V2zm2 0h1v12H7V2zm3 0h1v12h-1V2zm2 0h1v12h-1V2z" />
                            </svg>
                            ISBN <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="isbn" name="isbn"
                            value="<?= htmlspecialchars($data['isbn'] ?? '') ?>"
                            placeholder="Enter ISBN number" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="pages" class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16" style="margin-right: 4px;">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z" />
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                            </svg>
                            Pages <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="pages" name="pages"
                            value="<?= htmlspecialchars($data['pages'] ?? '') ?>"
                            placeholder="Enter number of pages" min="1" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="available" class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16" style="margin-right: 4px;">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
                            </svg>
                            Availability
                        </label>
                        <select class="form-select" id="available" name="available">
                            <option value="1" <?= ($data['available'] ?? true) ? 'selected' : '' ?>>Available</option>
                            <option value="0" <?= isset($data['available']) && !$data['available'] ? 'selected' : '' ?>>Not Available</option>
                        </select>
                        <div class="form-text">Select the book's availability status.</div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between">
                <a href="/books" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16" style="margin-right: 4px;">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16" style="margin-right: 4px;">
                        <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                    </svg>
                    <?= isset($isEdit) && $isEdit ? 'Update' : 'Create' ?> Book
                </button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Form validation script -->
    <script>
        document.getElementById('createBookForm').addEventListener('submit', function(e) {
            const pages = document.getElementById('pages').value;

            if (pages <= 0) {
                e.preventDefault();
                alert('Number of pages must be greater than 0!');
                document.getElementById('pages').focus();
                return false;
            }
        });
    </script>
</body>

</html>