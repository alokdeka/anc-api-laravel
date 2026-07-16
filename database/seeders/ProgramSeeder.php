<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'code'        => 'GNM',
                'name'        => 'General Nursing and Midwifery',
                'duration'    => '3.5 Years (including 6 months internship)',
                'eligibility' => "- Minimum age: 17 years\n- Pass in 10+2 or equivalent with English, Physics, Chemistry, Biology\n- Minimum 40% aggregate marks (45% for PCB)\n- Medical fitness certificate",
                'seats'       => 0,
                'description' => 'GNM is a diploma-level nursing program that trains students in general nursing and midwifery practices. The program prepares nurses for work in hospitals, community health centers, and primary health care settings across Assam and India.',
                'is_active'   => true,
                'sort_order'  => 1,
            ],
            [
                'code'        => 'ANM',
                'name'        => 'Auxiliary Nurse Midwife',
                'duration'    => '2 Years',
                'eligibility' => "- Minimum age: 17 years\n- Pass in Class 10 (High School) with English\n- Minimum 40% aggregate marks\n- Medical fitness certificate\n- Female candidates only",
                'seats'       => 0,
                'description' => 'The ANM program trains female health workers for community health services, maternal and child health care, family welfare, immunization, and primary health care. ANMs are deployed at sub-centers and PHCs across rural Assam.',
                'is_active'   => true,
                'sort_order'  => 2,
            ],
            [
                'code'        => 'LHV',
                'name'        => 'Lady Health Visitor',
                'duration'    => '1 Year (Post ANM)',
                'eligibility' => "- Must hold valid ANM Registration\n- Minimum 2 years of experience as ANM\n- Age below 45 years\n- Currently employed in public health sector (preferred)",
                'seats'       => 0,
                'description' => 'The LHV course is a post-basic program for registered ANMs seeking advanced training in community health supervision, supervisory nursing, and leadership of health teams at the block and district level.',
                'is_active'   => true,
                'sort_order'  => 3,
            ],
            [
                'code'        => 'DPN',
                'name'        => 'Diploma in Psychiatric Nursing',
                'duration'    => '2 Years (Post Basic)',
                'eligibility' => "- Must hold valid GNM/B.Sc. Nursing Registration\n- Minimum 1 year of clinical experience\n- Age below 45 years",
                'seats'       => 0,
                'description' => 'DPN is a post-basic diploma program designed for registered nurses specializing in mental health and psychiatric nursing. The program focuses on psychiatric disorders, therapeutic communication, psychosocial rehabilitation, and community mental health.',
                'is_active'   => true,
                'sort_order'  => 4,
            ],
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(['code' => $program['code']], $program);
        }
    }
}
