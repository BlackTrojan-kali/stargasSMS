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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("article_id");
            $table->unsignedBigInteger("stock_id");
            $table->string("label");
            $table->double("entree");
            $table->double("sortie");
            $table->foreign("article_id")->references("id")->on("articles")->onDelete("cascade");
            $table->foreign("stock_id")->references("id")->on("stocks")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
