<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminIntoUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert([
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),  # new \Datetime()
            'name'=>'admin',
            'email'=>config('admin.notifications.postStatus.email'),
            'password'=> \Hash::make('12345'),
            'role_id'=>1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
