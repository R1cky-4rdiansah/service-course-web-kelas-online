<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mentor;
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
                'message' => "Mentor tidak ditemukan",
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
                'message' => 'Data tidak ditemukan',
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
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
