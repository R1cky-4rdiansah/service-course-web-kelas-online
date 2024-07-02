<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\MyCourse;
use App\Models\Course;

class MyCourseController extends Controller
{
    public function index(Request $request)
    {
        $myCourse = MyCourse::query()->with("course");
        $userId = $request->query("user_id");

        $myCourse->when($userId, function ($query) use ($userId) {
            $query->where("user_id", $userId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $myCourse->get()
        ]);
    }
    public function create(Request $request)
    {
        $role = [
            "course_id" => "integer|required",
            "user_id" => "integer|required",
        ];

        $data = $request->all();

        $vald = Validator::make($data, $role);

        if ($vald->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $vald->errors(),
            ], 400);
        }

        $courseId = $request->input("course_id");
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                "status" => "error",
                "message" => "Data course tidak ditemukan"
            ], 404);
        }

        $userId = $request->input("user_id");
        $user = getUser($userId);

        if ($user["status"] === "error") {
            return response()->json([
                "status" => $user["status"],
                "message" => $user["message"]
            ], $user["http_code"]);
        }

        $existCourse = MyCourse::where("user_id", $userId)->where("course_id", $courseId)->exists();

        if ($existCourse) {
            return response()->json([
                "status" => "error",
                "message" => "Akun telah mengambil course"
            ], 409);
        }

        $myCourse = MyCourse::create($data);

        return response()->json([
            "status" => "success",
            "data" => $myCourse
        ], 200);
    }
}
