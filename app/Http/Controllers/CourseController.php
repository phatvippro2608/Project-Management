<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseModel;

class CourseController extends Controller
{
    function getViewCourses()
    {
        $courses = CourseModel::all();
        return view('auth.lms.course', ['courses' => $courses]);
    }
    function getViewCourseSection()
    {
        return view('auth.lms.course_section');
    }
    function getCourseSection($id)
    {
        return null;
    }
    function create(Request $request)
    {
        $course_name = $request->input('course_name');
        if (CourseModel::where('course_name', $course_name)->exists()) {
            return response()->json(['success' => false, 'message' => 'Course name already exists!']);
        }
        if ($course_name == null) {
            return response()->json(['success' => false, 'message' => 'Course name is required!']);
        }
        $description = $request->input('course_description');
        $course = new CourseModel([
            'course_name' => $course_name,
            'description' => $description,
        ]);
        $course->save();

        if ($request->hasFile('course_img')) {
            $file = $request->file('course_img');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/course/' . $course->course_id), $fileName);
            $course_image = $course->course_id.'/'.$fileName;
        } else {
            $course_image = null;
        }

        $course->course_image = $course_image;
        $course->save();
        $courses = CourseModel::all();
        return response()->json(['success' => true,'message' => 'Add new course success!', 'courses' => $courses]);
    }
    
}
