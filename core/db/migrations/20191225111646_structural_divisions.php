<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class StructuralDivisions extends Migration
{

    public function up()
    {
        $this->schema->create('divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->timestamps();
        });
    }


    public function down()
    {
        $this->schema->drop('divisions');
    }

}