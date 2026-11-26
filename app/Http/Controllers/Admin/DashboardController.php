<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_challenges' => Challenge::count(),
            'total_rewards' => Reward::count(),
            'total_redemptions' => Redemption::count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
