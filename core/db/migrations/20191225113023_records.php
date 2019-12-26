<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Records extends Migration
{

    public function up()
    {
        $this->schema->create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('division_id')->unsigned(); // id of structural division
            $table->string('fullname')->unique(); // fullname of user
            $table->integer('position_id')->unsigned(); // id of user position
            $table->integer('phone_id')->unsigned(); // id of user phone
            $table->string('email')->unique(); // email of user
            $table->string('place_division')->unique(); // place of division
            $table->timestamps();

            $table->foreign('division_id')
                ->references('id')->on('divisions')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('position_id')
                ->references('id')->on('positions')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('phone_id')
                ->references('id')->on('phones')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }


    public function down()
    {
        $this->schema->table('records', function (Blueprint $table) {
            $table->dropForeign('records_division_id_foreign');
            $table->dropForeign('records_position_id_foreign');
            $table->dropForeign('records_phone_id_foreign');
        });
        $this->schema->drop('records');
    }

}
