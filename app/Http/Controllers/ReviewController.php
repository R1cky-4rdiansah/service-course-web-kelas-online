<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Course;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $role = [
            "user_id" => "integer|required",
            "course_id" => "integer|required",
            "rating" => "integer|required|min:1|max:5",
            "note" => "string|required"
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors(),
            ], 404);
        }

        $userId = $request->input("user_id");
        $user = getUser($userId);
        if ($user["status"] === "error") {
            return response()->json([
                "status" => $user['status'],
                "message" => $user["message"],
            ], $user["http_code"]);
        }


        $courseId = $request->input("course_id");
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                "status" => "error",
                "message" => "Data course tidak ada"
            ], 404);
        }

        $existReview = Review::where("user_id", $userId)->where("course_id", $courseId)->exists();

        if ($existReview) {
            return response()->json([
                "status" => "error",
                "message" => "Review telah ada"
            ], 404);
        }

        $review = Review::create($data);

        return response()->json([
            "status" => "success",
            "data" => $review,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $role = [
            "rating" => "integer|min:1|max:5",
            "note" => "string"
        ];

        $data = $request->except("user_id", "course_id");

        $valid = Validator::make($data, $role);
        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors()
            ], 404);
        }

        $review = Review::find($id);
        if (!$review) {
            return response()->json([
                "status" => "error",
                "message" => "Data review tidak ada"
            ], 404);
        }

        $review->fill($data)->save();

        return response()->json([
            "status" => "success",
            "data" => $review
        ], 200);
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json([
                "status" => "error",
                "message" => "Data review tidak ada"
            ], 404);
        }

        $review->delete();

        return response()->json([
            "status" => "success",
            "message" => "Data review telah terhapus"
        ], 200);
    }
}
