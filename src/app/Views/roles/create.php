<?php $editMode = isset($isEdit) && $isEdit; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $editMode ? 'Edit' : 'Create' ?> Role - Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar { background: #6c757d; min-height: 48px; }
        .container { max-width: 900px; margin: 2em auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 2em 2.5em; }
        h2 { color: #4e54c8; }
        .form-control:focus { border-color: #4e54c8; box-shadow: 0 0 0 0.2rem rgba(78, 84, 200, 0.25); }
        .btn-primary { background-color: #4e54c8; border-color: #4e54c8; }
        .btn-primary:hover { background-color: #3d4db8; border-color: #3d4db8; }
        .permission-group { border: 1px solid #dee2e6; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        .permission-group h6 { color: #4e54c8; margin-bottom: 0.75rem; }
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
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/books">Books</a></li>
                    <?php if (hasPermission('user:read')): ?>
                    <li class="nav-item"><a class="nav-link" href="/users">Users</a></li>
                    <?php endif; ?>
                    <?php if (hasPermission('role:read')): ?>
                    <li class="nav-item"><a class="nav-link active" href="/roles">Roles</a></li>
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
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-shield-plus" viewBox="0 0 16 16" style="margin-right: 8px;">
                    <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                    <path d="M8 4.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V9a.5.5 0 0 1-1 0V7.5H6a.5.5 0 0 1 0-1h1.5V5a.5.5 0 0 1 .5-.5z"/>
                </svg>
                <?= $editMode ? 'Edit' : 'Create New' ?> Role
            </h2>
            <a href="/roles" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16" style="margin-right: 4px;">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Back to Roles
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

        <form method="POST" action="<?= $editMode ? '/roles/' . $data['id'] . '/update' : '/roles' ?>" id="roleForm">
            <?php if ($editMode): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Role Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($data['name'] ?? '') ?>" 
                               placeholder="e.g., manager, editor" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" 
                               value="<?= htmlspecialchars($data['description'] ?? '') ?>" 
                               placeholder="Brief description of this role">
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Permissions</h5>
            <p class="text-muted mb-4">Select the permissions for this role</p>

            <?php 
            $rolePermissions = isset($data['permissions']) ? (is_array($data['permissions']) ? $data['permissions'] : json_decode($data['permissions'], true)) : [];
            $groupedPermissions = [
                'User Management' => ['user:read', 'user:create', 'user:update', 'user:delete'],
                'Book Management' => ['book:read', 'book:create', 'book:update', 'book:delete'],
                'Role Management' => ['role:read', 'role:create', 'role:update', 'role:delete', 'role:assign'],
                'Self Management' => ['self:read', 'self:update', 'self:delete']
            ];
            ?>

            <?php foreach ($groupedPermissions as $groupName => $groupPerms): ?>
                <div class="permission-group">
                    <h6><?= $groupName ?></h6>
                    <div class="row">
                        <?php foreach ($groupPerms as $perm): ?>
                            <?php if (in_array($perm, $permissions)): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" 
                                               value="<?= $perm ?>" id="perm_<?= str_replace(':', '_', $perm) ?>"
                                               <?= in_array($perm, $rolePermissions) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="perm_<?= str_replace(':', '_', $perm) ?>">
                                            <?= ucfirst(str_replace([':', '_'], [' ', ' '], $perm)) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <hr class="my-4">

            <div class="d-flex justify-content-between">
                <a href="/roles" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16" style="margin-right: 4px;">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16" style="margin-right: 4px;">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                    </svg>
                    <?= $editMode ? 'Update' : 'Create' ?> Role
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
