<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mentor;
use App\Models\Course;
use App\Models\Review;
use App\Models\MyCourse;
use App\Models\Chapter;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query();

        $q = $request->query('q');
        $status = $request->query('status');

        $courses->when($q, function($query) use ($q){
            return $query->whereRaw("name LIKE '%".strtolower($q)."%'");
        });

        $courses->when($status, function($query) use ($status){
            return $query->where('status', $status);
        });

        return response()->json([
            "status" => "success",
            "data" => $courses->paginate(10)
        ], 200);
    }

    public function show($id)
    {
        $course = Course::with('chapters.lessons')->with('mentor')->with('images')->find($id);

        if(!$course)
        {
            return response()->json([
                "status" => "error",
                "message" => "Course not found" 
            ], 404);
        }

        $reviews = Review::where('course_id', $id)->get()->toArray();
        if(count($reviews) > 0)
        {
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);

            if($users['status'] === "error")
            {
                $reviews = [];
            }
            else{
              foreach($reviews as $key => $review)
              {
                $userIndex = array_search($review['user_id'], array_column($users['data']['users'], 'id'));
                $reviews[$key]['users'] = $users['data']['users'][$userIndex];
              }  
            }
        }

        $totalStudent = MyCourse::where('course_id', $id)->count();
        $totalVideos = Chapter::where('course_id', $id)->withCount('lessons')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideos, 'lessons_count'));

        $course['reviews'] = $reviews;
        $course['total_student'] = $totalStudent;
        $course['total_videos'] = $finalTotalVideos;

        return response()->json([
            "status" => "success",
            "data" => $course
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "certificate" => "required|boolean",
            "thumbnail" => "string|url",
            "type" => "required|in:free,premium",
            "status" => "required|in:draft,published",
            "level" => "required|in:all-level,beginner,intermediate,advance",
            "mentor_id" => "required|integer",
            "description" => "string"
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

        // cek mentor
        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);

        if(!$mentor)
        {
            return response()->json([
                "status" => "error",
                "message" => "Mentor not found" 
            ], 404);
        }

        $course = Course::create($data);
        return response()->json(['status' => "success", 'data' => $course], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "string",
            "certificate" => "boolean",
            "thumbnail" => "string|url",
            "type" => "in:free,premium",
            "status" => "in:draft,published",
            "level" => "in:all-level,beginner,intermediate,advance",
            "mentor_id" => "integer",
            "description" => "string"
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

        $course = Course::find($id);
        if(!$course)
        {
            return response()->json([
                "status" => "error",
                "message" => "Course not found" 
            ], 404);
        }

        // cek mentor
        if($request->input('mentor_id'))
        {
            $mentorId = $request->input('mentor_id');
            $mentor = Mentor::find($mentorId);

            if(!$mentor)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Mentor not found" 
                ], 404);
            }
        }

        $course->fill($data);
        $course->save();
        return response()->json(['status' => "success", 'data' => $course], 200);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if(!$course)
        {
            return response()->json([
                "status" => "error",
                "message" => "Course not found" 
            ], 404);
        }

        $course->delete();
        return response()->json(['status' => "success", 'message' => "course deleted"], 200);
    }
}
