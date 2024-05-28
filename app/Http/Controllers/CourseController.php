<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    public function create(Request $request)
    {

        $role = [
            "name" => "required|string",
            "certificate" => "required|boolean",
            "thumbnail" => "required",
            "type" => "required|string",
            "status" => "required|string",
            "price" => "required|integer",
            "level" => "required|string",
            "description" => "required|string"
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors()
            ], 400);
        }

        $course = Course::create($data);

        return response()->json([
            "status" => "success",
            "data" => $course
        ], 200);
    }
}
