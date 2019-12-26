<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Phones extends Migration
{

    public function up()
    {
        $this->schema->create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', '50')->unique(); // type of phone : internal or external phone type
            $table->integer('value')->unique(); // numeric for int XXX, ext XXXXXX or mobile XXXXXXXXXXX
            $table->timestamps();
        });
    }


    public function down()
    {
        $this->schema->drop('phones');
    }

}