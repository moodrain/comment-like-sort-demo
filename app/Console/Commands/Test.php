<?php

namespace App\Console\Commands;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Test extends Command
{
    protected $signature = 'test';
    public function handle()
    {
        $this->like(104, 1);
    }

    private function like($commentId, $count)
    {
        Comment::query()->where('id', $commentId)->update([
            'like_count' => DB::raw('like_count + ' . $count),
            'like_count_updated_at' => Carbon::now(),
        ]);
    }
}
