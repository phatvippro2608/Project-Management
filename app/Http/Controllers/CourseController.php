<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseModel;

class CourseController extends Controller
{
    function getViewCourse()
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

    function getCourse($id)
    {
        $course = CourseModel::find($id);
        return response()->json(['success' => true, 'course' => $course]);
    }

    function updateCourse(Request $request)
    {
        $course_id = $request->input('course_id');
        $course_name = $request->input('course_name');
        if (CourseModel::where('course_name', $course_name)->whereNot('course_id',$course_id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Course name already exists!']);
        }
        if ($course_name == null) {
            return response()->json(['success' => false, 'message' => 'Course name is required!']);
        }
        $description = $request->input('course_description');
        $course = CourseModel::find($course_id);
        $course->course_name = $course_name;
        $course->description = $description;
        $course->save();

        if ($request->hasFile('course_img')) {
            $old_image = public_path('uploads/course/' . $course->course_image);
            if (file_exists($old_image)) {
                unlink($old_image);
            }
            $file = $request->file('course_img');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/course/' . $course->course_id), $fileName);
            $course_image = $course->course_id.'/'.$fileName;
            $course->course_image = $course_image;
            $course->save();
        }
        $courses = CourseModel::all();
        return response()->json(['success' => true,'message' => 'Update course success!', 'courses' => $courses]);
    }
}
