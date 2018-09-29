<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHcpspecialities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HCPSpecialities', function (Blueprint $table) {
            $table->integer('id_hcp');
            $table->integer('id_speciality');
            $table->primary( ['id_hcp','id_speciality'] );
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
        Schema::dropIfExists('HCPSpecialities');
    }
}
