<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
//    'djs_ems';
//    '202.86.218.2';//202.86.218.2
//    'root';
//    'sr4UAicU7JZwVx2'; //sr4UAicU7JZwVx2

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['courseList', 'programList',
            'semesterList', 'batchList', 'ScheduleList']]);
    }

    public function courseList(Request $request)
    {
        return DB::table('aca_course')
            ->select('Name as label', 'ID as value')
//            ->where('SemesterID', $request->input('semester_id'))
            ->get();

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function programList(Request $request)
    {
        return DB::table('aca_program')->select('Name as label', 'ID as value')->get();

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function semesterList(Request $request)
    {
        return DB::table('aca_semester')->select('Name as label', 'ID as value')->get();

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function batchList(Request $request)
    {
        return DB::table('aca_batch')->select('Name as label', 'ID as value')->get();

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function ScheduleList(Request $request)
    {
        $semester_id = $request->input('semester_id');
        $course_id = $request->input('course_id');
        $batch_id = $request->input('batch_id');
        $where_cond = "";
        if ($course_id != "") {
            $where_cond .= " AND mst.CourserID = ".$course_id;
        }
        if ($batch_id != "") {
            $where_cond .= " AND mst.BatchID = ".$batch_id;
        }

         $sql = "SELECT dtl.DayID, dtl.RoomID, dtl.TimeID, dtl.date, mst.ProgramID, mst.SemesterID, 
        mst.CourserID, mst.BatchID,
        aca_days.Day day_name, aca_time_slot.Name time_slot, aca_room.Name room_name, aca_program.Name program_name,
        aca_semester.Name semester_name, aca_course.Name course_name, aca_batch.Name batch_name
        FROM aca_class_schedule_master mst
        INNER JOIN aca_class_schedule_details dtl on dtl.ClassScheduID = mst.ID
        INNER JOIN aca_days on aca_days.ID = dtl.DayID
        INNER JOIN aca_room on aca_room.ID = dtl.RoomID
        INNER JOIN aca_time_slot on aca_time_slot.ID = dtl.TimeID
        INNER JOIN aca_program on aca_program.ID = mst.ProgramID
        INNER JOIN aca_semester on aca_semester.ID = mst.SemesterID
        INNER JOIN aca_course on aca_course.ID = mst.CourserID
        INNER JOIN aca_batch on aca_batch.ID = mst.BatchID
        WHERE mst.SemesterID = $semester_id " . $where_cond;
        return DB::select($sql);

        return response()->json(['error' => 'Unauthorized'], 401);
    }

}
