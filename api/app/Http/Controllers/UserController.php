<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSong;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * @var UserServiceInterface
     */
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(string $userId)
    {
        return $this->userService->getWordsBags(intval($userId));
    }

    public function storeWordsBag(string $userId, Request $request)
    {
        return $this->userService->upsertWordsBag(intval($userId), $request->only('id', 'title', 'words'));
    }

    public function storeExpression(string $userId, Request $request)
    {
        return $this->userService->upsertExpression(intval($userId), $request->only('id', 'expression'));
    }

    public function deleteWordsBag(string $userId, string $bagId)
    {
        return $this->userService->deleteWordsBag(intval($userId), $bagId);
    }

    public function deleteExpression(string $userId, string $expressionId)
    {
        return $this->userService->deleteExpression(intval($userId), $expressionId);
    }
}
