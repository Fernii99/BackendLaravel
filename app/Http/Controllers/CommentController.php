<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Exception;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function findComments($id)
    {
        $comments = Comment::where('car_id', $id)->get();

        if ($comments) {
            return response()->json($comments);
        } else {
            return response()->json(['message' => 'no comments for this car'], 404);
        }
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'car_id' => 'required',
            'user' => 'required',
            'comment_text' => 'required',
        ]);

        $comment = new Comment();
        $comment::create($validatedData);

    }
}
