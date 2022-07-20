<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeaderBoardResource;
use App\Models\DailyStep;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LeaderBoardController extends Controller
{

    /**
     * Listing the top ranked users in the leaderboard.
     *
     * @return LeaderBoardResource
     */
    public function topRanked()
    {

        $currentDaySteps = DailyStep::with('user')->where('start_time', '<', Carbon::now()->toDateTimeString())
                            ->where('end_time', '>', Carbon::now()->toDateTimeString())
                            ->orderBy('steps_count', 'DESC')
                            ->limit(3)
                            ->get();

        // setting the rank manually, since it is already sorted
        foreach($currentDaySteps as $index => $rankedUser) {
            $rankedUser->rank = $index + 1;
        }

        return LeaderBoardResource::collection($currentDaySteps);
    }


    /**
     * Listing the closest ranked to the authenticated user.
     *
     * @return LeaderBoardResource
     */
    public function closestRanked()
    {
        // due to time constraints, I am assuming the system will not scale up to millions of users and I'll handle this with collections

        // get all users for the current day field in descending order
        $currentDaySteps = DailyStep::with('user')->where('start_time', '<', Carbon::now()->toDateTimeString())
                                                ->where('end_time', '>', Carbon::now()->toDateTimeString())
                                                ->orderBy('steps_count', 'DESC')
                                                ->get();

        // finding the rank of the authenticated user in the collection
        $rankOfAuthenticatedUser = $currentDaySteps->search(function($step) {
            return $step->user_id == auth()->id();
        }) + 1;

        $userWithRank = $currentDaySteps->slice($rankOfAuthenticatedUser-1)->take(1);

        // get 3 ranks higher than authenticated user
        $higherRanked = [];

        switch($rankOfAuthenticatedUser) {
            case $rankOfAuthenticatedUser == 1:
                $higherRanked = [];
                break;
            case $rankOfAuthenticatedUser == 2:
                $higherRanked = $currentDaySteps->slice($rankOfAuthenticatedUser-2)->take(1);
                break;
            case $rankOfAuthenticatedUser == 3:
                $higherRanked = $currentDaySteps->slice($rankOfAuthenticatedUser-3)->take(2);
                break;
            case $rankOfAuthenticatedUser > 3:
                $higherRanked = $currentDaySteps->slice($rankOfAuthenticatedUser-4)->take(3);
                break;
        }


        // get 3 ranks lower than authenticated user
        $lowerRanked = [];

        switch($rankOfAuthenticatedUser) {
            case $rankOfAuthenticatedUser == $currentDaySteps->count():
                $lowerRanked = [];
                break;
            case $rankOfAuthenticatedUser == $currentDaySteps->count() - 1:
                $lowerRanked = $currentDaySteps->slice($rankOfAuthenticatedUser)->take(1);
                break;
            case $rankOfAuthenticatedUser == $currentDaySteps->count() - 2:
                $lowerRanked = $currentDaySteps->slice($rankOfAuthenticatedUser)->take(2);
                break;
            case $rankOfAuthenticatedUser < $currentDaySteps->count() - 2:
                $lowerRanked = $currentDaySteps->slice($rankOfAuthenticatedUser)->take(3);
                break;
        }

        $rankedUsers = new Collection();

        foreach($higherRanked as $index => $rankedUser) {
            $rankedUser->rank = $index + 1;
            $rankedUsers->push($rankedUser);
        }

        foreach($userWithRank as $index => $rankedUser) {
            $rankedUser->rank = $index + 1;
            $rankedUsers->push($rankedUser);
        }

        foreach($lowerRanked as $index => $rankedUser) {
            $rankedUser->rank = $index + 1;
            $rankedUsers->push($rankedUser);
        }

        return LeaderBoardResource::collection($rankedUsers);
    }
} 