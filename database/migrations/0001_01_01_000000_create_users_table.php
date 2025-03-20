<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_teams', function (Blueprint $table) {
            $table->id(); // Tự động tạo cột int(11), primary key
            $table->string('name', 128);
            $table->integer('ins_id');
            $table->integer('upd_id')->nullable();
            $table->dateTime('ins_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('upd_datetime')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->char('del_flag', 1)->default('0');
        });

        Schema::create('m_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('m_teams')->onDelete('cascade');
            $table->string('email', 128);
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->string('password', 64);
            $table->char('gender', 1)->comment('1/ Male, 2/ Female');
            $table->date('birthday');
            $table->string('address', 256);
            $table->string('avatar', 128);
            $table->integer('salary');
            $table->char('position', 1)->comment('1/ Manager, 2/ Team leader, 3/ BSE, 4/ Dev, 5/ Tester');
            $table->char('status', 1)->comment('1/ On Working, 2/ Retired');
            $table->char('type_of_work', 1)->comment('1/ Fulltime, 2/ Partime, 3/ Probationary Staff, 4/ Intern');
            $table->integer('ins_id');
            $table->integer('upd_id')->nullable();
            $table->dateTime('ins_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('upd_datetime')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->char('del_flag', 1)->default('0')->comment('0/ Active, 1/ Deleted');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_teams');
        Schema::dropIfExists('m_employees');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
