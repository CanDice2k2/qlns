<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhuCapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phucap', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('phongban_id');
            $table->unsignedInteger('chucvu_id');
            $table->float('hsphucap', 5, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('phongban_id','fk_phucap_phongban_id')->references('id')->on('phongban')->onUpdate('CASCADE');
            $table->foreign('chucvu_id','fk_phucap_chucvu_id')->references('id')->on('chucvu')->onUpdate('CASCADE');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phucap', function(Blueprint $table)
        {
            $table->dropForeign('fk_phucap_phongban_id');
            $table->dropForeign('fk_phucap_chucvu_id');
            $table->dropColumn('phongban_id');
            $table->dropColumn('chucvu_id');
        });
        Schema::dropIfExists('phucap');
    }
}
