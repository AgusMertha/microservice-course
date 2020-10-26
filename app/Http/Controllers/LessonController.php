<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $lessons = Lesson::query();
        $chapterId = $request->query('chapter_id');

        $lessons->when($chapterId, function($query) use ($chapterId){
            return $query->where('chapter_id', $chapterId);
        });
        return response()->json([
            "status" => "success",
            "data" => $lessons->get()
        ]);
    }

    public function show($id)
    {
        $lesson = Lesson::find($id);
        
        if(!$lesson)
        {
            return response()->json([
                "status" => "error",
                "message" => "Lesson not found" 
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "data" => $lesson
        ], 200);
    }

    public function create(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "video" => "required|string|url",
            "chapter_id" => "required|integer"
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
        
        $chapterId = $request->input('chapter_id');
        $chapter = Chapter::find($chapterId);

        if(!$chapter)
        {
            return response()->json([
                "status" => "error",
                "message" => "Chapter not found" 
            ], 404);
        }

        $lesson = Lesson::create($data);
        return response()->json(['status' => "success", 'data' => $lesson], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "string",
            "video" => "string|url",
            "chapter_id" => "integer"
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

        $lesson = Lesson::find($id);
        if(!$lesson)
        {
            return response()->json([
                "status" => "error",
                "message" => "Lesson not found" 
            ], 404);
        }

        $chapter = Chapter::find($request->input('chapter_id'));

        if(!$chapter)
        {
            return response()->json([
                "status" => "error",
                "message" => "Chapter not found" 
            ], 404);
        }

        $lesson->fill($data);
        $lesson->save();

        return response()->json(['status' => "success", 'data' => $lesson], 200);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);

        if(!$lesson)
        {
            return response()->json([
                "status" => "error",
                "message" => "Lesson not found" 
            ], 404);
        }

        $lesson->delete();
        return response()->json(['status' => "success", 'message' => "Lesson deleted"], 200);
    }
}
