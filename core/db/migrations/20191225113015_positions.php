<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Positions extends Migration
{

    public function up()
    {
        $this->schema->create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->timestamps();
        });
    }


    public function down()
    {
        $this->schema->drop('positions');
    }

}