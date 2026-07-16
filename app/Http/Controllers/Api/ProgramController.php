<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::active()->with('media')->get()->map(fn($p) => [
            'id'          => $p->id,
            'code'        => $p->code,
            'name'        => $p->name,
            'duration'    => $p->duration,
            'eligibility' => $p->eligibility,
            'seats'       => $p->seats,
            'description' => $p->description,
            'brochure_url' => $p->getFirstMediaUrl('brochure'),
        ]);

        return response()->json(['data' => $programs]);
    }
}
