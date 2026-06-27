<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('software', function (Blueprint $table) {
            $table->string('category')->nullable()->after('nama_software');
        });
    }

    public function down()
    {
        Schema::table('software', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
