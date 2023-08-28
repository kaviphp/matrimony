<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFamilyInformationsOnMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('families', function (Blueprint $table) {
            $table->bigInteger('religion_id')->nullable();
            $table->bigInteger('family_value_id')->nullable();
            $table->bigInteger('family_status_id')->nullable();
            $table->string('no_of_brothers')->nullable();
            $table->string('no_of_sisters')->nullable();
            $table->string('parent_ph_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('families', function (Blueprint $table) {
            $table->dropColumn('religion_id');
            $table->dropColumn('family_value_id');
            $table->dropColumn('family_status_id');
            $table->dropColumn('no_of_brothers');
            $table->dropColumn('no_of_sisters');
            $table->dropColumn('parent_ph_no');
        });
    }
}
