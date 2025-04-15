<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHesoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heso', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('luongcb');
            $table->float('bac1', 5, 2);
            $table->float('bac2', 5, 2);
            $table->float('bac3', 5, 2);
            $table->float('bac4', 5, 2);
            $table->float('bac5', 5, 2);
            $table->float('bac6', 5, 2);
            $table->float('bac7', 5, 2);
            $table->float('bac8', 5, 2);
            $table->float('bac9', 5, 2);
            $table->float('bac10', 5, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heso');
    }
}
