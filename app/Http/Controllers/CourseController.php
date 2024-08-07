<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    function getViewCourse()
    {
        return view('auth.lms.course_section');
    }
    function getViewCourseSection()
    {
        return view('auth.lms.course_section');
    }
    function getCourseSection($id){
        return null;
    }
}
