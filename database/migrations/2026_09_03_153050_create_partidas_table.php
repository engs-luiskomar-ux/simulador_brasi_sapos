<?php

use App\Enums\PartidaStatus;
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
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('rodada');
            $table->dateTime('data_partida')->nullable();
            $table->foreignId('mandante_id')->constrained('times')->restrictOnDelete();
            $table->foreignId('visitante_id')->constrained('times')->restrictOnDelete();
            $table->unsignedTinyInteger('gols_mandante')->nullable();
            $table->unsignedTinyInteger('gols_visitante')->nullable();
            $table->string('status')->default(PartidaStatus::AGENDADA->value);
            $table->foreignId('simulada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['rodada', 'mandante_id', 'visitante_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};
