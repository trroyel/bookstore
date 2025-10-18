<?php

namespace App\Middleware;

use App\Core\Request;
use App\Services\JwtService;
use App\Repositories\UserRepository;

class ApiAuthMiddleware
{
    private $jwtService;
    private $userRepository;

    public function __construct(JwtService $jwtService, UserRepository $userRepository)
    {
        $this->jwtService = $jwtService;
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request)
    {
        $token = $this->extractToken($request);

        if (!$token) {
            $this->jsonResponse(['success' => false, 'message' => 'API token required'], 401);
            return false;
        }

        $payload = $this->jwtService->verify($token);

        if (!$payload) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid or expired token'], 401);
            return false;
        }

        $user = $this->userRepository->findById($payload['user_id']);

        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'User not found'], 401);
            return false;
        }

        $request->setUser($user);
        return true;
    }

    private function extractToken(Request $request)
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }

        if (isset($_GET['token'])) {
            return $_GET['token'];
        }

        return null;
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
