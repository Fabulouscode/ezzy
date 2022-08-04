<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Carbon\Carbon as Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       	DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categories')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $categories = [
            ['id'=> 1, 'name'=> 'Heathcare Provider', 'parent_id' => NULL, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 2, 'name'=> 'Medicine', 'parent_id' => NULL, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 3, 'name'=> 'Laboratories', 'parent_id' => NULL, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],

            ['id'=> 4, 'name'=> 'Doctor', 'parent_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 5, 'name'=> 'Nurses', 'parent_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 6, 'name'=> 'Massage Therapist', 'parent_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],

            ['id'=> 7, 'name'=> 'Pharmacist', 'parent_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],

            ['id'=> 8, 'name'=> 'Physiotherapist', 'parent_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 9, 'name'=> 'Pathologist & Lab Scientists', 'parent_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 10, 'name'=> 'X-Ray & Scan', 'parent_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],

            ['id'=> 11, 'name'=> 'General Practitioner(GP)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 12, 'name'=> 'Sickle Cell (Haematologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 13, 'name'=> 'Child Health(Paediatrician)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 14, 'name'=> 'Women Health (OB/GYN)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 15, 'name'=> 'Heart & Blood Pressure (Cardiologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 16, 'name'=> 'Ulcer, Heapatitis (Gasrtrienterologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 17, 'name'=> 'Kidney Specialist (Nephrologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 18, 'name'=> 'Diabetes & Endocrine (Endocrinologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 19, 'name'=> 'Urinary Specialist (Urologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 20, 'name'=> 'Skin Specialist (Dermatologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 21, 'name'=> 'General Surgeon', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 22, 'name'=> 'Dentist', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 23, 'name'=> 'Eye Specialist (Opthalmologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 24, 'name'=> 'Mental Health (Psychiatrist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 25, 'name'=> 'Psychologist', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 26, 'name'=> 'Lungs & Breathing (Pulmonologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 27, 'name'=> 'Bone & Joints (Orthhopedic Surgeon)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 28, 'name'=> 'Ear Nose & Throat (ENT)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 29, 'name'=> 'Stroke & Nerves (Neurologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 30, 'name'=> 'HIV & Infections (Infections Diseases)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 31, 'name'=> 'Plastic Surgeon', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 32, 'name'=> 'Neurosurgeon', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 33, 'name'=> 'Paediatric Surgeon', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 34, 'name'=> 'Cancer (Oncologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 35, 'name'=> 'Laboratory Doctor (Pathologist)', 'parent_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],

            ['id'=> 36, 'name'=> 'General Nurse', 'parent_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 37, 'name'=> 'Midwife', 'parent_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 38, 'name'=> 'Community Health Worker', 'parent_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 39, 'name'=> 'Plastic Nurse', 'parent_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 40, 'name'=> 'Trauma Nurse', 'parent_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
            ['id'=> 41, 'name'=> 'ICU Nurse', 'parent_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
        ];

        Category::insert($categories);
    }
}
