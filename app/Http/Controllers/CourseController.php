<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    public function index(Request $request)
    {
        $q = $request->query("q");
        $status = $request->query("status");

        $courses = Course::query();

        $courses->when($q, function ($query) use ($q) {
            $query->where("name", "like", "%" . strtolower($q) . "%");
        });

        $courses->when($status, function ($query) use ($status) {
            $query->where("status", $status);
        });

        return response()->json([
            'status' => 'success',
            'data' => $courses->paginate(10),
        ], 200);
    }

    public function show($id)
    {
        $course = Course::with('mentor')->with('chapter.lesson.watch')->with('images')->find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data course tidak ditemukan'
            ], 404);
        }

        $review = Review::where('course_id', $id)->get()->toArray();
        $totlaStudent = MyCourse::where('course_id', $id)->count();

        if (count($review) > 0) {
            $reviewUserId = array_column($review, 'user_id');
            $user = getUserIds($reviewUserId);
            // echo "<pre>" . print_r($user, 1) . "</pre>";
            if ($user['status'] === 'error') {
                $review = "Servis user mati";
            } else {
                foreach ($review as $key => $value) {
                    $userIdIndex = array_search($value["user_id"], array_column($user["data"], "id"));
                    $review[$key]["users"] = $user["data"][$userIdIndex];
                }
            }
        }

        $course['total-student'] = $totlaStudent;
        $course['review'] = $review;

        return response()->json([
            'status' => 'success',
            'data' => $course
        ], 200);

    }

    public function create(Request $request)
    {

        $role = [
            "name" => "required|string",
            "certificate" => "required|boolean",
            "thumbnail" => "string|url",
            "type" => "required|in:free,premium",
            "status" => "required|in:draft,publish",
            "price" => "required|integer",
            "level" => "required|in:all-level,beginner,intermediate,advance",
            "description" => "required|string",
            "mentor_id" => "required|integer",
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors()
            ], 400);
        }

        $mentor = Mentor::find($request->input("mentor_id"));

        if (!$mentor) {
            return response()->json([
                "status" => "error",
                'message' => "Data mentor tidak ditemukan",
            ], 404);
        }

        $course = Course::create($data);

        return response()->json([
            "status" => "success",
            "data" => $course
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $role = [
            "name" => "string",
            "certificate" => "boolean",
            "thumbnail" => "string|url",
            "type" => "in:free,premium",
            "status" => "in:draft,publish",
            "price" => "integer",
            "level" => "in:all-level,beginner,intermediate,advance",
            "description" => "string",
            "mentor_id" => "integer",
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors()
            ], 400);
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data course tidak ditemukan',
            ], 404);
        }

        $mentorId = $request->input('mentor_id');
        if ($mentorId) {
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                return response()->json([
                    'status' => 'error',
                    'data' => 'Data mentor tidak ditemukan'
                ], 404);
            }
        }

        $course->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'data' => $course
        ], 200);

    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data course tidak ditemukan'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data course berhasil dihapus'
        ], 200);
    }
}
