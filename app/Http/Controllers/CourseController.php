<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseModel;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    function getViewCourses()
    {
        $courses=DB::table('courses')
        ->join('course_types','courses.course_type_id','=','course_types.course_type_id')
        ->select('courses.*','course_types.type_name')
        ->get();
        $getTypeName = DB::table('course_types')->get();

        return view('auth.lms.course', ['courses' => $courses, 'getTypeName' => $getTypeName]);
    }
    function getCourseView($id)
    {
        $course=DB::table('courses')
        ->where('course_id',$id)
        ->get();
        $getTypeName = DB::table('course_types')->get();
        return view('auth.lms.course_section', ['course' => $course, 'getTypeName' => $getTypeName, 'id' => $id]);
    }
    function getCourseSection(Request $requet)
    {
        $id = $requet->input('id');
        $type = $requet->input('type');
        if($type==null){
            $sections=DB::table('courses_section')
            ->select('courses_section_id','section_name')
            ->where('course_id',$id)
            ->get();
        }else{
            $sections=DB::table('courses_section')
            ->where('courses_section_id',$id)
            ->get();
        }
        return response()->json(['success' => true, 'sections' => $sections]);
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
        $course_type=$request->input('course_type');
        $course = new CourseModel([
            'course_name' => $course_name,
            'description' => $description,
            'course_type_id' => $course_type,
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
        $course=DB::table('courses')
        ->join('course_types','courses.course_type_id','=','course_types.course_type_id')
        ->select('courses.*','course_types.type_name')
        ->where('course_id',$id)
        ->get();
        $getTypeName = DB::table('course_types')->get();
        return response()->json(['success' => true, 'course' => $course, 'getTypeName' => $getTypeName]);
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
        $course_type=$request->input('course_type');
        $course = CourseModel::find($course_id);
        $course->course_name = $course_name;
        $course->description = $description;
        $course->course_type_id = $course_type;
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
        if($request->input('type')=="single")
        {
            $courses=DB::table('courses')
            ->select('courses.*')
            ->where('course_id',$course_id)
            ->get();
        }else{
            $courses=DB::table('courses')
            ->join('course_types','courses.course_type_id','=','course_types.course_type_id')
            ->select('courses.*','course_types.type_name')
            ->get();
        }
        $getTypeName = DB::table('course_types')->get();
        return response()->json(['success' => true,'message' => 'Update course success!', 'courses' => $courses, 'getTypeName' => $getTypeName]);
    }

    function createSection(Request $request)
    {
        $course_id = $request->input('course_id');
        $section_name = $request->input('section_name');
        DB::table('courses_section')->insert([
            'course_id' => $course_id,
            'section_name' => $section_name,
        ]);
        $sections = DB::table('courses_section')->where('course_id',$course_id)->get();
        return response()->json(['success' => true,'message' => 'Add new section success!', 'sections' => $sections]);
    }
    function updateSection(Request $request)
    {
        $section_id = $request->input('section_id');
        $section_name = $request->input('section_name');
        $section_detail = $request->input('section_description');
        DB::table('courses_section')
        ->where('courses_section_id',$section_id)
        ->update([
            'section_name' => $section_name,
            'detail' => $section_detail,
        ]);
        $sections = DB::table('courses_section')->where('courses_section_id',$section_id)->get();
        return response()->json(['success' => true,'message' => 'Update section success!', 'sections' => $sections]);
    }
}
