<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EquipmentWorkplaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_workplace', function (Blueprint $table) {
            $table->unsignedInteger('equipment_id');
            $table->unsignedInteger('workplace_id');

            $table->foreign('equipment_id')->references('id')->on('equipment');
            $table->foreign('workplace_id')->references('id')->on('workplaces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_workplace');
    }
}
