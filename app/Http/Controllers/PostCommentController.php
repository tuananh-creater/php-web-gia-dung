<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCommentRequest;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\RedirectResponse;

class PostCommentController extends Controller
{
    public function store(PostCommentRequest $request, Post $post): RedirectResponse
    {
        PostComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'is_visible' => true,
        ]);

        return back()->with('success', 'Bình luận của bạn đã được gửi.');
    }

    public function destroy(PostComment $comment): RedirectResponse
    {
        abort_if($comment->user_id !== auth()->id(), 403);

        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận.');
    }
}