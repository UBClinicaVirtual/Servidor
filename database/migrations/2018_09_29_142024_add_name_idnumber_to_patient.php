<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameIdnumberToPatient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Patients', function (Blueprint $table) {            
            $table->string('name')->default('');
            $table->string('identification_number')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Patients', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('identification_number');
        });
    }
}
