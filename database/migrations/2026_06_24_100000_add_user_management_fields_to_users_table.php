<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('role_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->unsignedTinyInteger('status')->default(1)->after('password');
        });

        if (Schema::hasColumn('users', 'name')) {
            foreach (DB::table('users')->orderBy('id')->get() as $user) {
                $parts = explode(' ', (string) $user->name, 2);
                DB::table('users')->where('id', $user->id)->update([
                    'first_name' => $parts[0] ?? 'User',
                    'last_name' => $parts[1] ?? '',
                ]);
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('role_id');
        });

        foreach (DB::table('users')->orderBy('id')->get() as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'name' => trim("{$user->first_name} {$user->last_name}"),
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone', 'address', 'status']);
        });
    }
};
