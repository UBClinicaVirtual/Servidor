<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicAppointmentSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_appointment_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('clinic_hcp_speciality_id');
            $table->integer('day_of_the_week');
            $table->datetime('appointment_hour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clinic_appointment_schedule');
    }
}
