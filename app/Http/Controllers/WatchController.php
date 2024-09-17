<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WatchController extends Controller
{
    public function create(Request $request)
    {
        $role = [
            "user_id" => "integer|required",
            "lesson_id" => "integer|required",
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors(),
            ], 404);
        }

        $duplicate = Watch::where("user_id", $request->user_id)->where("lesson_id", $request->lesson_id)->count();
        if ($duplicate > 0) {
            return response()->json([
                "status" => "success",
                "message" => "Lesson sudah ditonton"
            ], 200);
        }

        $watch = Watch::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $watch
        ], 200);
    }
}
