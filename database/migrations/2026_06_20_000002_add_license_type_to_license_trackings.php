<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('license_trackings', function (Blueprint $table) {
            $table->enum('license_type', ['paid', 'free', 'opensource'])->default('free')->after('unique_code');
        });
    }

    public function down()
    {
        Schema::table('license_trackings', function (Blueprint $table) {
            $table->dropColumn('license_type');
        });
    }
};
