<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhautruTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khautru', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nhanvien_id');
            $table->unsignedInteger('loaibaohiem_id');
            $table->float('mucdong', 5, 2);
            $table->integer('thang');
            $table->integer('nam');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('nhanvien_id','fk_khautru_nhanvien_id')->references('id')->on('nhanvien')->onUpdate('CASCADE');
            $table->foreign('loaibaohiem_id','fk_khautru_loaibaohiem_id')->references('id')->on('loaibaohiem')->onUpdate('CASCADE');
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
        Schema::table('khautru', function(Blueprint $table)
        {
            $table->dropForeign('fk_khautru_nhanvien_id');
            $table->dropForeign('fk_khautru_loaibaohiem_id');
            $table->dropColumn('nhanvien_id');
            $table->dropColumn('loaibaohiem_id');
        });
        Schema::dropIfExists('khautru');
    }
}
