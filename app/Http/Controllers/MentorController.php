<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    public function index()
    {
        $mentor = Mentor::all();

        return response()->json([
            'status' => "success",
            "data" => $mentor
        ], 200);
    }

    public function show($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                "status" => "error",
                "message" => "Mentor tidak ditemukan"
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "data" => $mentor
        ], 200);
    }

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


    public function update(Request $request, $id)
    {
        $role = [
            "name" => "string",
            "email" => "email",
            "profile" => "url",
            "profession" => "string"
        ];

        $data = $request->all();

        $valid = Validator::make($data, $role);

        if ($valid->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $valid->errors()
            ], 400);
        }

        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                "status" => "error",
                "message" => "Mentor tidak ditemukan"
            ], 404);
        }

        $mentor->fill($data)->save();

        return response()->json([
            "status" => "success",
            "data" => $mentor
        ], 200);
    }

    public function destroy($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                "status" => "error",
                "message" => "Mentor tidak ditemukan"
            ], 404);
        }

        $mentor->delete();

        return response()->json([
            "status" => "success",
            "message" => "Mentor berhasil dihapus"
        ], 200);
    }
}
