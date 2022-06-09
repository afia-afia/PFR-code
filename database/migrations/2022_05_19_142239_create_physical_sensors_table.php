<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_sensors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("organization_id");
            $table->timestamps();
            $table->text("username");
            $table->string('password');
            $table->integer("rac");
            $table->string("ip")->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('physical_sensors');
    }
}
