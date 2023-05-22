<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function index()
    {
        $response = Http::acceptJson()->get(config('hosts.posts') . '/posts');

        if ($response->status() === 200) {
            $posts = $response->json();
            foreach ($posts as $key => $post) {
                $comments = Http::acceptJson()->get(config('hosts.comments') . "/{$post['id']}/comments");
                $posts[$key]["comments"] = $comments->json();
            }
            return $posts;
        } else {
            return response()->json([
                'message' => $response->json('message'),
            ], $response->status());
        }
    }

    public function single(string $slug)
    {
        $response = Http::acceptJson()->get(config('hosts.posts') . "/posts/$slug");

        if ($response->status() === 200) {
            $post = $response->json();
            $comments = Http::acceptJson()->get(config('hosts.comments') . "/{$post['id']}/comments");
            $post["comments"] = $comments->json();
            return $post;
        } else {
            return response()->json([
                'message' => $response->json('message'),
            ], $response->status());
        }
    }

    public function store(Request $request)
    {
        $response = Http::withToken($request->bearerToken())
                        ->acceptJson()
                        ->get(config('hosts.users') . '/user');

        if($user = $response->json('user'))
        {
            $request->merge(['author' => $user['name']]);
            $response = Http::acceptJson()->post(config('hosts.posts') . '/posts', $request->all());

            if ($response->status() === 201) {
                return $response->json();
            } else {
                return response()->json([
                    'message' => $response->json('message'),
                ], $response->status());
            }
        }
    }
}
