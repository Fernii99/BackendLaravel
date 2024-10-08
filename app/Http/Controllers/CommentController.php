<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Comment;
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

    public function postComment(Request $request){

        $validRequest = $request->validate([
            'user' => 'required|string',
            "comment_text" => 'required|string',
            "carId" => "required|string",
        ]);

        $car = Car::findOrFail($validRequest['carId']);

        $newComment = new Comment();
        $newComment->car_id = $validRequest['carId'];
        $newComment->comment_text = $validRequest['comment_text'];
        $newComment->user = $validRequest['user'];

        $newComment->save();

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => $newComment,
        ], 201);

    }
}
