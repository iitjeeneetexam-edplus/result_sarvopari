<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
        });
    }

    
    public function down(): void
    {
        Schema::table('marks', function (Blueprint $table) {
        });
    }
};
