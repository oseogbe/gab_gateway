<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $response = Http::acceptJson()->post(config('hosts.comments') . "/{$postId}/comment", [
            'content' => $request->content,
        ]);

        return $response->json();
    }

    public function storeReply(Request $request, $commentId)
    {
        $response = Http::acceptJson()->post(config('hosts.comments') . "/{$commentId}/reply", [
            'content' => $request->content,
        ]);

        return $response->json();
    }
}
