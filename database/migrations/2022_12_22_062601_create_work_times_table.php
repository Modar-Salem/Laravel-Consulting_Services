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
        Schema::create('work_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_id')->constrained('users') ;
            $table->boolean('sunday')->default(0) ;
            $table->boolean('monday')->default(0) ;
            $table->boolean('tuesday')->default(0) ;
            $table->boolean('wednesday')->default(0) ;
            $table->boolean('thursday')->default(0) ;
            $table->boolean('friday')->default(0) ;
            $table->boolean('saturday')->default(0) ;

            $table->time('begin_time1')->nullable() ;
            $table->time('end_time1')->nullable() ;

            $table->time('begin_time2')->nullable() ;
            $table->time('end_time2')->nullable() ;

            $table->time('begin_time3')->nullable() ;
            $table->time('end_time3')->nullable() ;

            $table->time('begin_time4')->nullable() ;
            $table->time('end_time4')->nullable() ;

            $table->time('begin_time5')->nullable() ;
            $table->time('end_time5')->nullable() ;

            $table->time('begin_time6')->nullable() ;
            $table->time('end_time6')->nullable() ;

            $table->time('begin_time7')->nullable() ;
            $table->time('end_time7')->nullable() ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_times');
    }
};
