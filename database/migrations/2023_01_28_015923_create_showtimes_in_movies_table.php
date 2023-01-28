<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('showntimes_in_weekly');
            $table->dropColumn('showntimes_in_weekday');
            $table->dropColumn('showntimes_in_day');
            $table->string('showntimes_in_weekly', 5)->nullable();
            $table->boolean('showntimes_in_weekday',1)->default(1);
            $table->string('showntimes_in_day', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('is_shown_in_weekly');
            $table->dropColumn('showntimes_in_weekly');
            $table->dropColumn('showntimes_in_weekday');
            $table->dropColumn('showntimes_in_day');
         });
    }
};
