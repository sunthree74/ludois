<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Activity;
use App\User;

class ActivityController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'activity' => 'required|min:3',
            'description' => 'required|min:3',
        ]);

        try {
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->activity = $request->input('activity');
            $activity->description = $request->input('description');
            $activity->contributors = $request->input('contributors');
            $activity->save();

            return response()->json([
                'message' => 'Success creating activity'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Creating Activity Failed!'], 409);
        }
    }

    public function show($id)
    {
        try {
            $act = Activity::with('comments')->findOrFail($id);

            if (isset($act->contributors)) {
                $d = array();
                $c = json_decode($act->contributors);
                foreach ($c as $key ) {
                    $d[] = array(User::findOrFail($key));
                }
                $act->contributors = $d;
            }

            return \response()->json([
                'activity' => $act
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Showing Activity Failed!'], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $act = Activity::findOrFail($id);
            $act->activity = $request->input('activity');
            $act->description = $request->input('description');
            $act->contributors = $request->input('contributors');
            $act->save();

            return response()->json([
                'message' => 'Success updating acivity'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Updating Activity Failed!'], 409);
        }
    }

    public function completeActivity($id)
    {
        try {
            $act = Activity::findOrFail($id);
            $act->isconfirmed = true;
            $act->save();

            return response()->json([
                'message' => 'You Have done the activity'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Completing Activity Failed!'], 409);
        }
    }

    public function getAllActivityByUser()
    {
        try {
            $act = Activity::with('comments')->where('user_id', Auth::user()->id)->get();

            $d = array();
            foreach ($act as $sku){ 
                if (isset($sku->contributors)) {
                    $c = json_decode($sku->contributors);
                    foreach ($c as $key ) {
                        $d[] = array(User::findOrFail($key));
                    }
                    $sku->contributors = $d;
                }
            }

            return response()->json([
                'activity' => $act
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Showing Activity Failed!'], 409);
        }
    }

    public function destroy($id)
    {
        try {
            $act = Activity::findOrFail($id);
            $act->delete();

            return response()->json([
                'message' => 'Success deleting activity!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Deleting Activity Failed!'], 409);
        }
    }

    public function changeContributor(Request $request,$id)
    {
        try {
            $act = Activity::findOrFail($id);
            $act->contributors = $request->input("contributors");
            $act->save();

            return response()->json([
                'message' => 'Changing Contributors success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Changing Activity Failed!'], 409);
        }
    }
}
