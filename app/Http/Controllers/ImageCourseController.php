<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\ImageCourse;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    public function create(Request $request)
    {
        $role = [
            'image' => 'required|string',
            'course_id' => 'required|integer'
        ];

        $data = request()->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $valid->errors()
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

        $imageCourse = ImageCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $imageCourse
        ], 200);
    }

    public function destroy($id)
    {
        $imageCourse = ImageCourse::find($id);

        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data image course tidak ditemukan'
            ], 404);
        }

        $imageCourse->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Image course telah terhapus'
        ], 200);
    }
}
