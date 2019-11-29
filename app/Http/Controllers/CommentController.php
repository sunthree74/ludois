<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Activity;
use App\Comment;
use App\User;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'activity_id' => 'required',
            'comment' => 'required|min:3',
        ]);

        try {
            $cmt = new Comment();
            $cmt->activity_id = $request->input('activity_id');
            $cmt->comment = $request->input('comment');
            $cmt->save();

            return response()->json([
                'message' => 'Success creating comment'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Creating Comment Failed!'], 409);
        }
    }

    public function show($id)
    {
        try {
            $act = Comment::findOrFail($id);

            return \response()->json([
                'comment' => $act
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Showing Comment Failed!'], 409);
        }
    }

    public function destroy($id)
    {
        try {
            $act = Comment::findOrFail($id);
            $act->delete();

            return response()->json([
                'message' => 'Success deleting comment!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Deleting Comment Failed!'], 409);
        }
    }
}
