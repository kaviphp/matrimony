<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgeOnPartnerExpectationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_expectations', function (Blueprint $table) {
            $table->bigInteger('city_id')->nullable();
            $table->string('age')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_expectations', function (Blueprint $table) {
            $table->dropColumn('city_id');
            $table->dropColumn('age');
        });
    }
}
