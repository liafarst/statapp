<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpeningHoursToLocations extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('locations', function (Blueprint $table) {
            $table->string('opening_hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('opening_hours');
        });
    }
}
