<?php

namespace App\Controllers;

use App\Repositories\IRoleRepository;

class RoleController extends BaseController
{
    protected $roleRepository;

    public function __construct(IRoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index($request = null)
    {
        $this->authorize('role:read');
        $roles = $this->roleRepository->findAll();
        $this->render('roles/list', ['roles' => $roles]);
    }

    public function create($request = null)
    {
        $this->authorize('role:create');
        $availablePermissions = require __DIR__ . '/../../config/permissions.php';
        $this->render('roles/create', ['permissions' => $availablePermissions]);
    }

    public function store($request = null)
    {
        if (!$this->isPost()) {
            $this->redirect('/roles/create');
            return;
        }

        $data = $this->sanitize($this->getPostData());
        $isEdit = isset($data['id']) && !empty($data['id']);

        $errors = $this->validateRequired($data, ['name']);

        if (empty($errors)) {
            $existingRole = $this->roleRepository->findByName($data['name']);
            if ($existingRole && (!$isEdit || $existingRole['id'] != $data['id'])) {
                $errors[] = 'Role name already exists';
            }
        }

        if (!empty($errors)) {
            $availablePermissions = require __DIR__ . '/../../config/permissions.php';
            $this->render('roles/create', ['errors' => $errors, 'data' => $data, 'isEdit' => $isEdit, 'permissions' => $availablePermissions]);
            return;
        }

        $data['permissions'] = json_encode($data['permissions'] ?? []);

        if ($isEdit) {
            $this->roleRepository->update($data['id'], $data);
            $this->setFlash('success', 'Role updated successfully');
        } else {
            $this->roleRepository->create($data);
            $this->setFlash('success', 'Role created successfully');
        }

        $this->redirect('/roles');
    }

    public function edit($request = null, $id = null)
    {
        if (is_object($request) && $id === null) {
            $id = $request;
            $request = null;
        }

        $this->authorize('role:update');
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            $this->setFlash('error', 'Role not found');
            $this->redirect('/roles');
            return;
        }

        $availablePermissions = require __DIR__ . '/../../config/permissions.php';
        $this->render('roles/create', ['data' => $role, 'isEdit' => true, 'permissions' => $availablePermissions]);
    }

    public function update($request = null, $id = null)
    {
        if (is_object($request) && $id === null) {
            $id = $request;
            $request = null;
        }
        $_POST['id'] = $id;
        $this->store();
    }

    public function delete($request = null, $id = null)
    {
        if (is_object($request) && $id === null) {
            $id = $request;
            $request = null;
        }

        $this->authorize('role:delete');
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            $this->setFlash('error', 'Role not found');
            $this->redirect('/roles');
            return;
        }

        $this->roleRepository->delete($id);
        $this->setFlash('success', 'Role deleted successfully');
        $this->redirect('/roles');
    }
}
