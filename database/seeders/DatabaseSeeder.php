<?php

namespace Database\Seeders;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $count = 300;
        for ($i = 1;$i <= $count;$i++) {
            $lastLikeAt = Carbon::now()->subSeconds(mt_rand(0, 60 * 24 * 3));
            Comment::query()->create([
                'user_id' => 1,
                'subject_id' => 1,
                'like_count' => mt_rand(0, 1000),
                'like_count_updated_at' => $lastLikeAt,
            ]);
        }
    }
}
