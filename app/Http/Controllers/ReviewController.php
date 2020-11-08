<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            "user_id" => "required|integer",
            "course_id" => "required|integer",
            "rating" => "required|integer|min:1|max:5",
            "note" => "string"
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

        $isReviewExists = Review::where('user_id', $userId)->where('course_id', $courseId)->exists();

        if($isReviewExists)
        {
            return response()->json([
                "status" => "error",
                "message" => "User already review this course"
            ], 409);
        }

        $review = Review::create($data);
        return response()->json(["status" => "success", "data" => $review]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "rating" => "integer|min:1|max:5",
            "note" => "string"
        ];

        $data = $request->except('user_id', 'course_id');
        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }
        $review = Review::find($id);

        if(!$review)
        {
            return response()->json([
                "status" => "error",
                "message" => "review not found"
            ]);
        }

        $review->fill($data);
        $review->save();

        return response()->json(["status" => "success", "data" => $review]);
    }

    public function destroy($id)
    {
        $review = Review::find($id);

        if(!$review)
        {
            return response()->json([
                "status" => "error",
                "message" => "Review not found" 
            ], 404);
        }

        $review->delete();
        return response()->json(['status' => "success", 'message' => "review deleted"], 200);
    }
}
