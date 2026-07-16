<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\Examination;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
    public function timetable(Request $request)
    {
        $query = Examination::active()->with('media');

        if ($request->filled('program_code')) {
            $query->where('program_code', $request->program_code);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        return response()->json([
            'data' => $query->get()->map(fn($e) => [
                'id'           => $e->id,
                'title'        => $e->title,
                'program_code' => $e->program_code,
                'exam_date'    => $e->exam_date?->format('Y-m-d'),
                'exam_time'    => $e->exam_time,
                'center'       => $e->center,
                'year'         => $e->year,
                'semester'     => $e->semester,
                'timetable_url' => $e->getFirstMediaUrl('timetable'),
            ]),
        ]);
    }

    public function results(Request $request)
    {
        $request->validate([
            'roll_no' => 'required|string|max:30',
        ]);

        $results = ExamResult::with('examination')
            ->byRollNumber($request->roll_no)
            ->get()
            ->map(fn($r) => [
                'roll_number'      => $r->roll_number,
                'candidate_name'   => $r->candidate_name,
                'exam_title'       => $r->examination->title,
                'program_code'     => $r->examination->program_code,
                'marks'            => $r->marks,
                'total_marks'      => $r->total_marks,
                'percentage'       => $r->percentage,
                'result'           => $r->result,
                'grade'            => $r->grade,
                'rank'             => $r->rank,
            ]);

        return response()->json(['data' => $results]);
    }
}
