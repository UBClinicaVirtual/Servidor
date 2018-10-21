<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHcpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hcps', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('identification_number',50);
            $table->date('birth_date');
            $table->integer('gender_id');
            $table->integer('user_id');
            $table->string('register_number',50);
            $table->string('address',200);
            $table->string('phone',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hcps');
    }
}
