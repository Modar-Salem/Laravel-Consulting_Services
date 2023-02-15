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
        Schema::create('expert_information', function (Blueprint $table) {
            $table->id();

            $table->string('phone') ;

            $table->string('address')->nullable() ;

            $table->foreignId('expert_id')->constrained('users') ;

            $table->enum('consulting_type' , ['medical' , 'professional' , 'psychological'
                , 'family' , 'administrative']) ;

            $table->text('experience') ;


            $table->float('fee') ;

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
        Schema::dropIfExists('expert_information');
    }
};
