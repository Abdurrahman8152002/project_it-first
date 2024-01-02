<?php

use App\Models\Medicine;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('medicine_storage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->references("id")->on("medicines");
            $table->foreignId('storage_id')->references("id")->on("storages");
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_storage');
    }
};
