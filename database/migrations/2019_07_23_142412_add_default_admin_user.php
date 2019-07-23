<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            // ±K½X¬°admin
            DB::table('users')->insert([
                'name' => 'admin',
                'student_id' => 'admin',
                'password' => '$2y$10$RCj2W2ix19QruJ60bFaUFOX2.XoX5jPiZxDr9y1m1bH8W.0QsjKM2',
                'is_admin' => 1
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
        if (Schema::hasTable('users')) {
            DB::table('users')->where('student_id', '=', 'admin')->delete();
        }
    }
}
