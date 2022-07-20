<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyStepsResource;
use App\Http\Resources\UsersResource;
use App\Models\DailyStep;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyStepsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dailySteps = auth()->user()->dailySteps()->orderBy('start_time', 'DESC')->get();

        return DailyStepsResource::collection($dailySteps);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // check if request has step count
        $steps = 1;
        if ($request->has('steps')) {
            $steps = $request->steps;
        }

        // check if steps already exist for the current day
        $daySteps = auth()->user()
                            ->dailySteps()
                            ->where('start_time', '<', Carbon::now()->toDateTimeString())
                            ->where('end_time', '>', Carbon::now()->toDateTimeString())
                            ->first();

        if($daySteps) {
            $daySteps->update([
                'steps_count' => 14000
            ]);
        } else {
            $daySteps = $user->dailySteps()->create([
                'steps_count' => $steps,
                'start_time' => Carbon::now()->startOfDay()->toDateTimeString(),
                'end_time' => Carbon::now()->endOfDay()->toDateTimeString()
            ]);
        }

        return new DailyStepsResource($daySteps);
    
    }
}
