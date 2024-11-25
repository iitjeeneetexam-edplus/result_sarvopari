<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('krupa_sidhi_gun', function (Blueprint $table) {
            $table->enum('is_optional', [1, 0])->default(0)->after('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krupa_sidhi_gun', function (Blueprint $table) {
            //
        });
    }
};
