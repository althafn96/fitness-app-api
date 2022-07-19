<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeaderBoardResource;
use App\Http\Resources\UsersResource;
use App\Models\DailyStep;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{
    /**
     * Listing the users in the leaderboard.
     *
     * @return Response
     */
    public function __invoke()
    {
        $users = User::get()->sortByDesc('currentDayStepsCount');

        return LeaderBoardResource::collection($users);
    }
}