<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropsThePkOfHcpspecialities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('HCPSpecialities', function (Blueprint $table) {
            $table->dropPrimary(['id_hcp', 'id_speciality']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('HCPSpecialities', function (Blueprint $table) {
            $table->primary(['id_hcp', 'id_speciality']);
        });
    }
}
