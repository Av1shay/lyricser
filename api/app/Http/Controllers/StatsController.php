<?php

namespace App\Http\Controllers;


use App\Services\Contracts\StatsServiceInterface;

class StatsController extends Controller
{

    protected StatsServiceInterface $statsService;

    public function __construct(StatsServiceInterface $statsService)
    {
        $this->statsService = $statsService;
    }

    public function index()
    {
        $statsData = $this->statsService->getStats();

        return response()->json($statsData);
    }
}
