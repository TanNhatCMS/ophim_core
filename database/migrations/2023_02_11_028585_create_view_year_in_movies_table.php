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
        // if you've ended up with duplicate entries (both for App\User and App\Models\BackpackUser)
        // we can just delete them
        $moviesEntries = DB::table('movies')->get();
        foreach ($moviesEntries as $entry) {
            Log::info('update view id '.$entry->id);
            DB::table('movies')
                ->where('id', $entry->id)
                ->update([
                    'view_year' => ($entry->view_total - $entry->view_month),
                ]);
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
