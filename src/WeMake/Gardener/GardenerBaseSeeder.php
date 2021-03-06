<?php

namespace WeMake\Gardener;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class GardenerBaseSeeder
 *
 * @package WeMake\Gardener
 */
class GardenerBaseSeeder extends Migration
{
    /**
     * @param array  $data
     * @param string $table
     * @param bool   $enableIdentityInsert
     */
    protected function insert(array $data, $table, $enableIdentityInsert = false)
    {
        if ($enableIdentityInsert) {
            $this->enableIdentityInsert($table);
        }

        DB::table($table)->insert($data);

        if ($enableIdentityInsert) {
            $this->disableIdentityInsert($table);
        }
    }

    /**
     * @param string $table
     */
    protected function enableIdentityInsert($table)
    {
        if (env('DB_CONNECTION') == 'sqlsrv') {
            DB::unprepared('SET IDENTITY_INSERT ' . $table . ' ON');
        }
    }

    /**
     * @param string $table
     */
    protected function disableIdentityInsert($table)
    {
        if (env('DB_CONNECTION') == 'sqlsrv') {
            DB::unprepared('SET IDENTITY_INSERT ' . $table . ' OFF');
        }
    }

    /**
     * @param string $table
     * @param int    $start
     */
    protected function reseedTable($table, $start = 0)
    {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement("ALTER TABLE $table AUTO_INCREMENT = $start");
        }

        if (env('DB_CONNECTION') == 'sqlsrv') {
            DB::statement("DBCC CHECKIDENT ($table, RESEED, $start)");
        }
    }
}