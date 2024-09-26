<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDataInPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $array = [
            [
                'id' => '67',
                'title' => 'pages_create',
            ],
            [
                'id' => '68',
                'title' => 'pages_edit',
            ],
            [
                'id' => '69',
                'title' => 'pages_show',
            ],
            [
                'id' => '70',
                'title' => 'pages_delete',
            ],
            [
                'id' => '71',
                'title' => 'pages_access',
            ],
        ];

        \App\Permission::insert($array);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission', function (Blueprint $table) {
            //
        });
    }
}
