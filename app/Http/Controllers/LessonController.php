<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $lessson = Lesson::query();
        $chapterId = $request->input('chapter_id');

        $lessson->when($chapterId, function ($query) use ($chapterId) {
            $query->where('chapter_id', $chapterId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $lessson->get()
        ], 200);
    }

    public function show($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data lesson tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ], 200);
    }
    public function create(Request $request)
    {
        $role = [
            "name" => "required|string",
            "video" => "required|string",
            "chapter_id" => "required|integer"
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors()
            ], 400);
        }

        $chapterId = $request->input("chapter_id");
        $chapter = Chapter::find($chapterId);
        if (!$chapter) {
            return response()->json([
                "status" => "error",
                "message" => "Data chapter tidak ditemukan"
            ], 404);
        }

        $lesson = Lesson::create($data);

        return response()->json([
            "status" => "success",
            "data" => $lesson
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $role = [
            "name" => "string",
            "video" => "string",
            "chapter_id" => "integer"
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors()
            ], 400);
        }

        $chapterId = $request->input("chapter_id");
        if ($chapterId) {
            $chapter = Chapter::find($chapterId);
            if (!$chapter) {
                return response()->json([
                    "status" => "error",
                    "message" => "Data chapter tidak ditemukan"
                ], 404);
            }
        }

        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json([
                "status" => "error",
                "message" => "Data lesson tidak ditemukan"
            ], 404);
        }

        $lesson->fill($data)->save();

        return response()->json([
            "status" => "success",
            "data" => $lesson
        ], 200);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json([
                "status" => "error",
                "message" => "Data lesson tidak ditemukan"
            ], 404);
        }

        $lesson->delete();

        return response()->json([
            "status" => "success",
            "message" => 'Data lesson telah dihapus'
        ], 200);
    }
}
