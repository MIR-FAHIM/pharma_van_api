<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('upazilas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('district_id');
            $table->string('name', 50);
            $table->string('bn_name', 50);
            $table->string('url', 50);
        });

        DB::table('upazilas')->insert([
            'id' => 1,
            'district_id' => 1,
            'name' => 'Debidwar',
            'bn_name' => 'দেবিদ্বার',
            'url' => 'debidwar.comilla.gov.bd',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upazilas');
    }
};
