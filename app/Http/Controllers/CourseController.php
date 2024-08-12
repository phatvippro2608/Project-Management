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
        $account_id = session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = ?";
        $employee = DB::selectOne($sql_get_employee_id, [$account_id]);
        $employee_id = $employee->employee_id;
        $isExist = DB::table('courses')
            ->select('courses.*')
            ->join('courses_employees', 'courses.course_id', '=', 'courses_employees.course_id')
            ->where('courses.course_id', $id)
            ->where('courses_employees.employee_id', $employee_id)
            ->exists();
        $course=DB::table('courses')
            ->where('course_id',$id)
            ->get();
        $getTypeName = DB::table('course_types')->get();
        if ($isExist) {
            return view('auth.lms.course_section', ['show'=>true,'course' => $course, 'getTypeName' => $getTypeName, 'id' => $id]);
        } else {
            return view('auth.lms.course_section', ['show'=>false,'course' => $course,'id' => $id]);
        }
    }

    function joinCourse(Request $request){
        try {
            $course_id = $request->input('course_id');
            $account_id = $request->session()->get(\App\StaticString::ACCOUNT_ID);
            $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = ?";
            $employee = DB::selectOne($sql_get_employee_id, [$account_id]);
            $employee_id = $employee->employee_id;
            $date = date('Y-m-d');
            DB::table('courses_employees')->insert([
                'course_id' => $course_id,
                'employee_id' => $employee_id,
                'start_date' => $date,
                'end_date' => null,
            ]);
            return response()->json(['success' => true, 'message' => 'Join course success!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while joining the course. '.$e->getMessage()]);
        }
    }

    function create(Request $request)
    {
        try {
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
            $courses=DB::table('courses')
                ->join('course_types','courses.course_type_id','=','course_types.course_type_id')
                ->select('courses.*','course_types.type_name')
                ->get();
            return response()->json(['success' => true,'message' => 'Add new course success!', 'courses' => $courses]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while creating the course. '.$e->getMessage()]);
        }
    }

    function updateCourse(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the course. '.$e->getMessage()]);
        }
    }

    function deleteCourse(Request $request){
        try {
            $id = $request->input('course_id');
            $course = CourseModel::find($id);
            $old_image = public_path('uploads/course/' . $course->course_image);
            if (file_exists($old_image)) {
                unlink($old_image);
            }
            $course->delete();
            $courses=DB::table('courses')
                ->join('course_types','courses.course_type_id','=','course_types.course_type_id')
                ->select('courses.*','course_types.type_name')
                ->get();
            return response()->json(['success' => true, 'message' => 'Delete course success!', 'courses' => $courses]);
        } catch (\Exception $e) {
            $id = $request->input('course_id');
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the course. '.$e->getMessage()]);
        }
    }




    // Section
    // Section
    // Section
    // Section
    // Section

    function getCourseSection(Request $requet)
    {
        $id = $requet->input('id');
        $type = $requet->input('type');

        $account_id = session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = ?";
        $employee = DB::selectOne($sql_get_employee_id, [$account_id]);
        $employeeId = $employee->employee_id;

        if($type==null){
            $sections=DB::table('courses_section')
            ->select('courses_section_id','section_name','section_employees.course_employee_id')
            ->leftJoin('section_employees', function ($join) use ($id,$employeeId) {
            $join->on('courses_section.courses_section_id', '=', 'section_employees.section_id')
                 ->where('section_employees.course_employee_id', function ($query) use ($id,$employeeId) {
                 $query->select('courses_employees_id')
                       ->from('courses_employees')
                       ->where('course_id', $id)
                       ->where('employee_id', $employeeId);
                 });
            })
            ->where('course_id',$id)
            //thêm sắp xếp theo section_id
            ->orderBy('courses_section_id')
            ->get();
        }else{
            $sections=DB::table('courses_section')
            ->where('courses_section_id',$id)
            ->get();

            $courses_employees_id = DB::table('courses_employees')
                ->select('courses_employees_id')
                ->where('course_id',$sections[0]->course_id)
                ->where('employee_id',$employeeId)
                ->get();
            
            $isExist = DB::table('section_employees')
                ->where('course_employee_id',$courses_employees_id[0]->courses_employees_id)
                ->where('section_id',$id)
                ->exists();
            if(!$isExist){
                DB::table('section_employees')->insert([
                    'course_employee_id' => $courses_employees_id[0]->courses_employees_id,
                    'section_id' => $id,
                ]);
            }
            //update progress in courses_employees
            $num_section = DB::table('courses_section')
                ->select('courses_section_id')
                ->where('course_id',$sections[0]->course_id)
                ->count();
            $num_section_done = DB::table('section_employees')
                ->select('section_id')
                ->where('course_employee_id',$courses_employees_id[0]->courses_employees_id)
                ->count();
            $progress = ($num_section_done/$num_section)*100;
            //update progress in courses_employees
            DB::table('courses_employees')
                ->where('courses_employees_id',$courses_employees_id[0]->courses_employees_id)
                ->update([
                    'progress' => $progress,
                ]);

        }
        return response()->json(['success' => true, 'sections' => $sections]);
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

    function createSection(Request $request)
    {
        try {
            $course_id = $request->input('course_id');
            $section_name = $request->input('section_name');
            DB::table('courses_section')->insert([
                'course_id' => $course_id,
                'section_name' => $section_name,
            ]);
            $sections = DB::table('courses_section')->where('course_id',$course_id)->get();
            return response()->json(['success' => true,'message' => 'Add new section success!', 'sections' => $sections]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while creating the section. '.$e->getMessage()]);
        }
    }

    function updateSection(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the section. '.$e->getMessage()]);
        }
    }

    function deleteSection(Request $request){
        try {
            $id = $request->input('section_id');
            DB::table('courses_section')->where('courses_section_id',$id)->delete();
            $sections = DB::table('courses_section')->where('course_id',$id)->get();
            return response()->json(['success' => true, 'message' => 'Delete section success!', 'sections' => $sections]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the section. '.$e->getMessage()]);
        }
    }
}
