<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{


    public function index()
    {
        $blogPosts = BlogPost::all();
        $response = [
            'status' => 'success',
            'message' => 'Blog posts retrieved successfully.',
            'data' => [
                'blog_posts' => $blogPosts
            ]
        ];

        return response()->json($response, 200);
    }

    public function show($id)
    {
        $blogPost = BlogPost::find($id);
        $response = [
            'status' => 'success',
            'message' => 'Blog post retrieved successfully.',
            'data' => [
                'blog_post' => $blogPost
            ]
        ];

        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'author_id' => 'required|integer',
            'body' => 'required|string',
            'published_at' => 'required|date'
        ]);

        $blogPost = BlogPost::create([
            'title' => $fields['title'],
            'author_id' => $fields['author_id'],
            'body' => $fields['body'],
            'published_at' => $fields['published_at']
        ]);



        $response = [
            'status' => 'success',
            'message' => 'Blog post created successfully.',
            'data' => [
                'blog_post' => $blogPost
            ]
        ];

        return response()->json($response, 201);
    }

    public function updateById(Request $request, $id)
    {
        
        $fields = $request->validate([
            'title' => 'required|string',
            'author_id' => 'required|integer',
            'body' => 'required|string',
            'published_at' => 'required|date'
        ]);

        $blogPost = BlogPost::findAndUpdate($fields, $id);

        $response = [
            'status' => 'success',
            'message' => 'Blog post updated successfully.',
            'data' => [
                'blog_post' => $blogPost
            ]
        ];

        return response()->json($response, 200);
    }

    public function deleteById($id)
    {
        $blogPost = BlogPost::find($id);
        $blogPost->delete();

        $response = [
            'status' => 'success',
            'message' => 'Blog post deleted successfully.',
            'data' => [
                'blog_post' => $blogPost
            ]
        ];

        return response()->json($response, 200);
    }
}
