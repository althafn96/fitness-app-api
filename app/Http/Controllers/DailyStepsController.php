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
        $dailySteps = auth()->user()->dailySteps;

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
        $daySteps = DailyStep::where('start_time', '<', Carbon::now()->toDateTimeString())->where('end_time', '>', Carbon::now()->toDateTimeString())->first();

        if($daySteps) {
            $daySteps->update([
                'stepsCount' => $daySteps->stepsCount + $steps
            ]);
        } else {
            $user->dailySteps()->create([
                'stepsCount' => $steps,
                'start_time' => Carbon::now()->addDays(2)->startOfDay()->toDateTimeString(),
                'end_time' => Carbon::now()->addDays(2)->endOfDay()->toDateTimeString()
            ]);
        }

        return new UsersResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
