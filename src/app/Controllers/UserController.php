<?php

namespace App\Controllers;

use App\Services\UserService;


class UserController extends BaseController
{
    protected $userService;
    protected $roleRepository;

    public function __construct(UserService $userService, $roleRepository = null)
    {
        $this->userService = $userService;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display list of all users
     */
    public function index($request = null)
    {
        $this->authorize('user:read');
        
        $users = $this->userService->readAllUsers();

        // Remove passwords from display
        foreach ($users as &$user) {
            unset($user['password']);
        }

        $this->render('users/list', ['users' => $users]);
    }

    /**
     * Show details of a specific user
     */
    public function show($request = null, $id = null)
    {
        // Handle both old and new parameter order
        if (is_object($request) && $id === null) {
            $id = $request;
            $request = null;
        }
        
        $this->authorizeOwnerOr($id, 'user:read');
        
        $user = $this->userService->read($id);

        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect(hasPermission('user:read') ? '/users' : '/dashboard');
            return;
        }

        // Remove password from display
        unset($user['password']);

        $this->render('users/show', ['user' => $user]);
    }

    /**
     * Display form to create new user
     */
    public function create($request = null)
    {
        $this->authorize('user:create');
        
        $roles = $this->roleRepository ? $this->roleRepository->findAll() : [];
        $this->render('users/create', ['roles' => $roles]);
    }

    /**
     * Process form submission and save new user
     */
    public function store($request = null)
    {
        if (!$this->isPost()) {
            $this->redirect('/users/create');
            return;
        }

        $data = $this->sanitize($this->getPostData());

        // Check if this is an edit (has id in data)
        $isEdit = isset($data['id']) && !empty($data['id']);

        // Validate required fields - password only required for new users
        $requiredFields = ['name', 'email'];
        if (!$isEdit) {
            $requiredFields[] = 'password';
            $requiredFields[] = 'password_confirm';
        }
        $errors = $this->validateRequired($data, $requiredFields);

        // Check password confirmation (only if password provided)
        if (!empty($data['password']) && $data['password'] !== ($data['password_confirm'] ?? '')) {
            $errors[] = 'Passwords do not match';
        }

        // Check password length (only if password provided)
        if (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters long';
        }

        // Check if email already exists
        if (empty($errors)) {
            $existingUser = $this->userService->findByEmail($data['email']);
            if ($existingUser && (!$isEdit || $existingUser['id'] != $data['id'])) {
                $errors[] = 'Email already exists';
            }
        }

        if (!empty($errors)) {
            $roles = $this->roleRepository ? $this->roleRepository->findAll() : [];
            $this->render('users/create', ['errors' => $errors, 'data' => $data, 'isEdit' => $isEdit, 'roles' => $roles]);
            return;
        }
        
        // Set default role_id to 'user' if not provided
        if (empty($data['role_id']) && $this->roleRepository) {
            $userRole = $this->roleRepository->findByName('user');
            if ($userRole) {
                $data['role_id'] = $userRole['id'];
            }
        }

        // Remove password_confirm from data
        unset($data['password_confirm']);

        // Remove empty password for updates
        if ($isEdit && empty($data['password'])) {
            unset($data['password']);
        }

        if ($isEdit) {
            // Update existing user
            $this->userService->update($data['id'], $data);
            $this->setFlash('success', 'User updated successfully');
            // Redirect based on permissions
            if (hasPermission('user:read')) {
                $this->redirect('/users');
            } else {
                $this->redirect('/users/' . $data['id']);
            }
        } else {
            // Create new user
            $user = $this->userService->create($data);
            $this->setFlash('success', 'User created successfully');
            $this->redirect('/users');
        }
    }

    /**
     * Display form to edit existing user
     */
    public function edit($request = null, $id = null)
    {
        // Handle both old and new parameter order
        if (is_object($request) && $id === null) {
            $id = $request;
            $request = null;
        }
        
        $this->authorizeOwnerOr($id, 'user:update');

        $user = $this->userService->read($id);

        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect('/users');
            return;
        }

        // Remove password from form data
        unset($user['password']);
        
        $roles = $this->roleRepository ? $this->roleRepository->findAll() : [];
        $this->render('users/create', ['data' => $user, 'isEdit' => true, 'roles' => $roles]);
    }

    /**
     * Process edit form and update user data
     */
    public function update($request = null, $id = null)
    {
        // Handle both old and new parameter order
        if (is_object($request) && $id === null) {
            $id = $request;
            $request = null;
        }
        // Add ID to POST data and use store method which handles both create and update
        $_POST['id'] = $id;
        $this->store();
    }

    /**
     * Remove user from system
     */
    public function delete($request = null, $id = null)
    {
        // Handle both old and new parameter order
        if (is_object($request) && $id === null) {
            $id = $request;
            $request = null;
        }
        
        $this->authorizeOwnerOr($id, 'user:delete');

        $user = $this->userService->read($id);
        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect('/users');
            return;
        }

        $currentUser = $this->getCurrentUser();
        $isSelfDelete = $currentUser && $currentUser['id'] === $id;

        $this->userService->delete($id);
        
        if ($isSelfDelete) {
            session_destroy();
            session_start();
            $this->setFlash('success', 'Your account has been deleted');
            $this->redirect('/');
        } else {
            $this->setFlash('success', 'User deleted successfully');
            $this->redirect('/users');
        }
    }

    /**
     * Show login form
     */
    public function login($request = null)
    {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user'])) {
            $this->redirect('/dashboard');
            return;
        }

        $this->render('users/login');
    }

    /**
     * Process login credentials
     */
    public function authenticate($request = null)
    {
        if (!$this->isPost()) {
            $this->redirect('/login');
            return;
        }

        $data = $this->sanitize($this->getPostData());

        $errors = $this->validateRequired($data, ['email', 'password']);

        if (!empty($errors)) {
            $this->render('users/login', ['errors' => $errors]);
            return;
        }

        $user = $this->userService->authenticate($data['email'], $data['password']);

        if (!$user) {
            $this->render('users/login', ['errors' => ['Invalid email or password']]);
            return;
        }

        // Store user data in session
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role_name'] ?? 'user';
        $_SESSION['permissions'] = isset($user['permissions']) ? json_decode($user['permissions'], true) : [];
        
        $this->setFlash('success', 'Welcome back!');
        $this->redirect('/dashboard');
    }

    /**
     * End user session
     */
    public function logout($request = null)
    {
        session_destroy();
        $this->redirect('/');
    }

    /**
     * Search users
     */
    public function search($request = null)
    {
        $query = isset($_GET['q']) ? $this->sanitize($_GET['q']) : '';

        if (empty($query)) {
            $this->redirect('/users');
            return;
        }

        $users = $this->userService->search($query);
        $this->render('users/list', ['users' => $users, 'searchQuery' => $query]);
    }
}
