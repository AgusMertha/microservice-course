<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\MyCourse;
use Illuminate\Support\Facades\Validator;


class MyCourseController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            "course_id" => "required|integer",
            "user_id" => "required|integer"
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        // cek course
        if(!$course)
        {
            return response()->json([
                "status" => "error",
                "message" => "Course not found" 
            ], 404);
        }

        // cek user
        $userId = $request->input('user_id');
        $user = getUser($userId);

        if($user['status'] === "error")
        {
            return response()->json([
                "status" => $user['status'],
                "message" => $user['message']
            ], $user['http_code']);
        }

        $isExistMyCourse = MyCourse::where('course_id', $courseId)->where('user_id', $userId)->exists();

        if($isExistMyCourse)
        {
            return response()->json([
                "status" => "error",
                "message" => "User already take this course"
            ], 409);
        }

        $myCourse = MyCourse::create($data);
        return response()->json(['status' => "success", 'message' => $myCourse], 200);
    }
}
