<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('mongodb')->dropIfExists('daily_steps');
        
        Schema::connection('mongodb')->create('daily_steps', function ($collection) {
            $collection->index('user_id');
            $collection->index('steps_count');
            $collection->index('start_time');
            $collection->index('end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::connection('mongodb')->dropIfExists('daily_steps');
    }
};
