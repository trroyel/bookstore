<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Services\JwtService;

class ApiController extends BaseController
{
    private $userService;
    private $jwtService;

    public function __construct(UserService $userService, JwtService $jwtService)
    {
        $this->userService = $userService;
        $this->jwtService = $jwtService;
    }

    public function generateToken($request)
    {
        $data = $request->getBody();
        
        if (is_string($data)) {
            $data = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->json(['success' => false, 'message' => 'Invalid JSON format'], 400);
                return;
            }
        }

        if (!is_array($data) || empty($data['email']) || empty($data['password'])) {
            $this->json(['success' => false, 'message' => 'Email and password required'], 400);
            return;
        }

        $user = $this->userService->authenticate($data['email'], $data['password']);

        if (!$user) {
            $this->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            return;
        }

        $token = $this->jwtService->generate([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role_id' => $user['role_id'] ?? null
        ]);

        $this->json([
            'success' => true,
            'token' => $token,
            'expires_in' => 86400,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role_id' => $user['role_id'] ?? null
            ]
        ]);
    }
}
