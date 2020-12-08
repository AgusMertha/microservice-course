<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $chapters = Chapter::query();
        $courseId = $request->query('course_id');

        $chapters->when($courseId, function($query) use ($courseId) {
            return $query->where('course_id', $courseId);
        });

        return response()->json([
            "status" => "success",
            "data" => $chapters->get()
        ]);
    }

    public function show($id)
    {
        $chapter = Chapter::find($id);

        if(!$chapter)
        {
            return response()->json([
                "status" => "error",
                "message" => "Chapter not found" 
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "data" => $chapter
        ], 200);
    }

    public function create(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "course_id" => "required|integer"
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "messages" => $validator->errors()
            ], 400);
        }

        $course = Course::find($request->input('course_id'));

        if(!$course)
        {
            return response()->json([
                "status" => "error",
                "message" => "Course not found" 
            ], 404);
        }

        $chapter = Chapter::create($data);
        return response()->json(['status' => "success", 'data' => $chapter], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "required|string",
            "course_id" => "integer"
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "messages" => $validator->errors()
            ], 400);
        }

        $chapter = Chapter::find($id);
        if(!$chapter)
        {
            return response()->json([
                "status" => "error",
                "message" => "Chapter not found" 
            ], 404);
        }

        if($request->input("course_id"))
        {
            $course = Course::find($request->input('course_id'));
            if(!$course)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Course not found" 
                ], 404);
            }
        }

        $chapter->fill($data);
        $chapter->save();

        return response()->json(['status' => "success", 'data' => $chapter], 200);
    }

    public function destroy($id)
    {
        $chapter = Chapter::find($id);

        if(!$chapter)
        {
            return response()->json([
                "status" => "error",
                "message" => "Chapter not found" 
            ], 404);
        }

        $chapter->delete();
        return response()->json(['status' => "success", 'message' => "Chapter deleted"], 200);
    }
}
