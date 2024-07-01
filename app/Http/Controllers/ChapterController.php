<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Chapter;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->query("course_id");
        $chapters = Chapter::query();

        $chapters->when($courseId, function ($query) use ($courseId) {
            $query->where("course_id", $courseId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $chapters->get()
        ], 200);
    }
    public function create(Request $request)
    {
        $role = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $valid->error()
            ], 400);
        }

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data course tidak ditemukan'
            ], 404);
        }

        $chapter = Chapter::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $role = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $valid->error()
            ], 400);
        }

        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data chapter tidak ditemukan'
            ], 404);
        }

        $courseId = $request->input('course_id');

        if ($courseId) {
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data course tidak ditemukan'
                ], 404);
            }
        }

        $chapter->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'data' => $chapter,
        ], 200);
    }

    public function show($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data chapter tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ], 200);
    }

    public function destroy($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data chapter tidak ditemukan'
            ], 404);
        }

        $chapter->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data chapter telah dihapus'
        ], 200);
    }
}
