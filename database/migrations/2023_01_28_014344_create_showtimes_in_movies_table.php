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
            $table->boolean('is_shown_in_weekly')->default(false);
            $table->string('showntimes_in_weekly', 5);
            $table->boolean('showntimes_in_weekday')->default(false);
            $table->string('showntimes_in_day', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('is_shown_in_weekly');
        $table->dropColumn('showntimes_in_weekly');
        $table->dropColumn('showntimes_in_weekday');
        $table->dropColumn('showntimes_in_day');
        $table->dropColumn('showntimes_in_day');
        }
};
