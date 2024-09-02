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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable();
            $table->string('nama');
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama', ['Islam', 'Kristen', 'Budha', 'Khonghucu','Katolik','Hindu'])->default('Islam');
            $table->string('kelamin',['Laki - Laki', 'Perempuan'])->default('Laki - Laki');
            $table->integer('kontak')->nullable();
            $table->text('alamat')->nullable();
            $table->string('profil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
