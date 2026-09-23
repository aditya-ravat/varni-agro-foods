<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::published()
            ->with('author')
            ->when($request->tag, fn ($q, $tag) => $q->whereJsonContains('tags', $tag))
            ->orderByDesc('published_at')
            ->paginate(12);

        return PostResource::collection($posts);
    }

    public function show(string $slug)
    {
        $post = Post::published()->with('author')->where('slug', $slug)->firstOrFail();
        return new PostResource($post);
    }
}
