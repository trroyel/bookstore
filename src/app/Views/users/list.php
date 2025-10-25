<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List - Bookstore</title>
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
            box-shadow: 0 2px 16px rgba(0,0,0,0.07); 
            padding: 2em 2.5em; 
        }
        h2 { 
            color: #4e54c8; 
        }
        .btn-primary {
            background-color: #4e54c8;
            border-color: #4e54c8;
        }
        .btn-primary:hover {
            background-color: #3d4db8;
            border-color: #3d4db8;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/" style="font-size: 1.2em;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-book" viewBox="0 0 16 16" style="margin-right: 8px;">
                    <path d="M1 2.828c.885-.37 2.154-.829 3.388-.829 1.234 0 2.503.46 3.388.829V13.17c-.885.37-2.154.83-3.388.83-1.234 0-2.503-.46-3.388-.83V2.828zM8 13.17c.885.37 2.154.83 3.388.83 1.234 0 2.503-.46 3.388.83V2.828c-.885-.37-2.154-.829-3.388-.829-1.234 0-2.503.46-3.388.829v10.342z"/>
                </svg>
                <span style="font-weight: 600; letter-spacing: 2px;">Book Store</span>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/books">Books</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/users">Users</a></li>
                    <?php if (hasPermission('role:read')): ?>
                    <li class="nav-item"><a class="nav-link" href="/roles">Roles</a></li>
                    <?php endif; ?>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16" style="margin-right: 8px;">
                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                </svg>
                All Users
            </h2>
            <a href="/users/create" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16" style="margin-right: 4px;">
                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                </svg>
                Add New User
            </a>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="/users/search" class="d-flex">
                    <input type="text" name="q" class="form-control me-2" placeholder="Search users by name or email..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button type="submit" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                        Search
                    </button>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <span class="text-muted">Total: <?= count($users ?? []) ?> users</span>
            </div>
        </div>

        <?php include __DIR__ . '/../partials/flash-messages.php'; ?>

        <div class="table-responsive">
            <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16" style="opacity: 0.3;">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                </svg>
                                <p class="mt-2">No users found</p>
                                <a href="/users/create" class="btn btn-primary btn-sm">Add First User</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="user-avatar">
                                    <?= strtoupper(substr($user['name'] ?? '', 0, 2)) ?>
                                </div>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($user['name'] ?? '') ?></strong>
                                <br><small class="text-muted">ID: <?= htmlspecialchars($user['id']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                            <td>
                                <?php if (($user['role'] ?? 'user') === 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($user['created_at'])): ?>
                                    <?= htmlspecialchars(date('M j, Y', strtotime($user['created_at']))) ?>
                                <?php else: ?>
                                    <span class="text-muted">Unknown</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/users/<?= htmlspecialchars($user['id']) ?>" class="btn btn-outline-primary btn-sm" title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </a>
                                    <a href="/users/<?= htmlspecialchars($user['id']) ?>/edit" class="btn btn-outline-info btn-sm" title="Edit User">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.854a.5.5 0 0 1 .708 0l2.292 2.292a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-4 1a.5.5 0 0 1-.62-.62l1-4a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 12.5 5.793 10.207 3.5l1-1zm1.586 3L10.5 3.207 3 10.707V13h2.293l7.5-7.5z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="/users/<?= htmlspecialchars($user['id']) ?>/delete" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete User">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </button>
                                    </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        </div>

        <?php if (!empty($users)): ?>
            <div class="row mt-4">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        Showing <?= count($users) ?> user<?= count($users) !== 1 ? 's' : '' ?>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="/users/create" class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16" style="margin-right: 4px;">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Add Another User
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>