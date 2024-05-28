<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    public function create(Request $request)
    {
        $role = [
            "name" => "required|string",
            "email" => "required|email",
            "profile" => "required|url",
            "profession" => "required|string"
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors()
            ], 400);
        }

        $mentor = Mentor::create($data);

        return response()->json([
            "status" => "success",
            "data" => $mentor
        ], 200);
    }
}
