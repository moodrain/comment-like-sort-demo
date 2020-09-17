<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class IndexController extends Controller
{
    public function index()
    {

    }

    public function list()
    {
        $builder = Comment::query();
        $builder->orderByDesc('like_count')
            ->orderByDesc('id');
        $pager = $builder->paginate(10);
        return view('list', compact('pager'));
    }

    public function list2() {
        $size = 10;
        $validate = Validator::make(request()->all(), [
            'last_like_at' => 'date_format:"Y-m-d H:i:s"',
            'like_count' => 'int|min:0',
            'last_id' => 'exists:comment,id'
        ]);
        if ($validate->fails()) {
            return $validate->errors()->first();
        }
        $builder = function() {
            return Comment::query()->where('like_count_updated_at', '<', request('last_like_at', Carbon::now()));
        };
        if (! request('like_count')) {
            $list = $builder()->orderByDesc('like_count')
                ->orderByDesc('id')
                ->take($size)
                ->get();
        } else {
            $list = $builder()->where('like_count', request('like_count'))
                ->where('id', '<', request('last_id'))
                ->orderByDesc('id')
                ->take($size)
                ->get();
            if ($list->count() < $size) {
                $add = $builder()->where('like_count', '<', request('like_count'))
                    ->whereNotIn('id', $list->pluck('id')->all())
                    ->orderByDesc('like_count')
                    ->orderByDesc('id')
                    ->take($size - $list->count())
                    ->get();
                $list = $list->merge($add);
            }
        }
        return view('list2', compact('list'));
    }

    public function getCommentsIds()
    {
        $validate = Validator::make(request()->all(), [
            'last_ids' => 'array',
            'last_ids.*' => 'int|exists:comment,id',
        ]);
        if ($validate->fails()) {
            return $validate->errors()->first();
        }
        $size = 100;
        if (! request('last_ids')) {
            $list = Comment::query()->where('like_count', '>=', 10)
                ->orderByDesc('like_count')
                ->orderByDesc('id')
                ->take($size)
                ->pluck('id');
            if ($list->count() < $size) {
                $add = Comment::query()->where('like_count', '<', 10)
                    ->whereNotIn('id', $list->all())
                    ->orderByDesc('id')
                    ->take($size - $list->count())
                    ->pluck('id');
                $list = $list->merge($add);
                return response()->json($list);
            }
            return response()->json($list);
        }
        $lastComment = Comment::query()->whereIn('id', request('last_ids'))
            ->orderByDesc('like_count')
            ->orderByDesc('id')
            ->first();
        $lastLikeCount = $lastComment->like_count;
        $list = Comment::query()->where('like_count', '<=', $lastLikeCount)
            ->where('like_count', '>=', 10)
            ->whereNotIn('id', request('last_ids'))
            ->orderByDesc('like_count')
            ->orderByDesc('id')
            ->take($size)
            ->pluck('id');
        if ($list->count() < $size) {
            $add = Comment::query()->where('like_count', '<', 10)
                ->whereNotIn('id', $list->all())
                ->orderByDesc('id')
                ->take($size - $list->count())
                ->pluck('id');
            $list = $list->merge($add);
        }
        return response()->json($list);
    }

    public function getCommentsByIds()
    {
        $comments = Comment::query()->whereIn('id', request('ids'))->get();
        $commentsSort = [];
        foreach (request('ids') as $id) {
            $comment = $comments->where('id', $id)->first();
            $comment && $commentsSort[] = $comment;
        }
        return response()->json($commentsSort);
    }

}
