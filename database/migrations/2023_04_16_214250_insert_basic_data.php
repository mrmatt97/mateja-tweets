<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user = "INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Jane Dane', 'johnny@wow.com', '2022-10-18 23:54:05', '$2y$10$5kMPSvLE5DyNPs5bKa8eIeiIlb/Yr4TaD7XwpE54CKaydrlOplaXm', NULL, '2022-10-18 23:54:05', NULL);";
        $admin = "INSERT INTO `users` (`id`, `name`, `role`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('2', 'Admin', 'admin','admin@wow.com', '2022-10-18 23:54:05', '$2y$10$5kMPSvLE5DyNPs5bKa8eIeiIlb/Yr4TaD7XwpE54CKaydrlOplaXm', NULL, '2022-10-18 23:54:05', NULL);";
        $tweets = "INSERT INTO `tweets` (`user_id`, `body`, `likes`, `deleted_at`, `created_at`, `updated_at`) VALUES ('1', 'Hello Twitter', '345', NULL, now(), now()), ('1', 'Hello Twitter, This is another test Tweet!!', '15', NULL, now(), now());";

        DB::statement($user);
        DB::statement($admin);
        DB::statement($tweets);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
