<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'string',
            'sort' => 'string',
        ]);

        $query = Post::query();

        if ($request->has('search')) {
            $query = $query
                ->where(
                    'user_name',
                    'like',
                    '%'.$request->search.'%'
                )
                ->orWhere(
                    'message',
                    'like',
                    '%'.$request->search.'%'
                );
        }

        if ($request->has('sort')) {
            $order = 'asc';
            $column = $request->sort;

            if (strpos($column, '-') === 0) {
                $order = 'desc';
                $column = substr($column, 1);
            }

            $query = $query->orderBy($column, $order);
        }

        return PostResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
