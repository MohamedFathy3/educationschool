<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\CourseDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Stages
        $stages = [
            ['name' => 'Primary Stage', 'image' => 'stages/primary.png', 'country_id' => 1, 'postion' => 1],
            ['name' => 'Secondary Stage', 'image' => 'stages/secondary.png', 'country_id' => 1, 'postion' => 2],
        ];

        foreach ($stages as $stage) {
            Stage::create($stage);
        }

        // Subjects
        $subjects = [
            ['name' => 'Mathematics', 'image' => 'subjects/math.png', 'stage_id' => 1, 'postion' => 1],
            ['name' => 'Science', 'image' => 'subjects/science.png', 'stage_id' => 1, 'postion' => 2],
            ['name' => 'Physics', 'image' => 'subjects/physics.png', 'stage_id' => 2, 'postion' => 1],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        // Teachers
        $teachers = [
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed@example.com',
                'phone' => '01012345678',
                'national_id' => '12345678901234',
                'image' => 'teachers/profile/ahmed.png',
                'certificate_image' => 'teachers/certificates/ahmed_cert.png',
                'experience_image' => 'teachers/experience/ahmed_exp.png',
                'country_id' => 1,
                'stage_id' => 1,
                'subject_id' => 1,
                'total_rate' => 4,
                'password' => Hash::make('123456'),

                'bank_name' => 'CIB',
                'account_holder_name' => 'Ahmed Ali',
                'account_number' => '1234567890',
                'iban' => 'EG1234567890',
                'swift_code' => 'CIBEGCXXXX',
                'branch_name' => 'Nasr City',

                'wallets_name' => 'Vodafone Cash',
                'wallets_number' => '01012345678',
            ],
            [
                'name' => 'Sara Mohamed',
                'email' => 'sara@example.com',
                'phone' => '01198765432',
                'national_id' => '98765432109876',
                'image' => 'teachers/profile/sara.png',
                'certificate_image' => 'teachers/certificates/sara_cert.png',
                'experience_image' => 'teachers/experience/sara_exp.png',
                'country_id' => 1,
                'stage_id' => 2,
                'subject_id' => 3,
                'password' => Hash::make('123456'),
                'total_rate' => 3,

                'bank_name' => 'Banque Misr',
                'account_holder_name' => 'Sara Mohamed',
                'account_number' => '9876543210',
                'iban' => 'EG9876543210',
                'swift_code' => 'BMISXXXX',
                'branch_name' => 'Heliopolis',

                'wallets_name' => 'Etisalat Cash',
                'wallets_number' => '01198765432',
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }

        // Example Course & Course Details
        $course = Course::create([
            'teacher_id' => 1,
            'stage_id' => 1,
            'subject_id' => 1,
            'country_id' => 1,
            'title' => 'Basic Mathematics',
            'description' => 'Introduction to Math',
            'type' => 'recorded',
            'price' => 200,
            'discount' => 20,
            'what_you_will_learn' => 'Addition, Subtraction, Multiplication, Division',
            'image' => 'courses/math_intro.png',
            'intro_video_url' => 'https://youtube.com/example',
        ]);

        $details = [
            [
                'course_id' => $course->id,
                'title' => 'Lesson 1: Addition',
                'description' => 'Learn basic addition',
                'content_type' => 'video',
                'content_link' => 'https://youtube.com/addition',
            ],
            [
                'course_id' => $course->id,
                'title' => 'Lesson 2: Subtraction',
                'description' => 'Learn basic subtraction',
                'content_type' => 'pdf',
                'file_path' => 'courses/files/subtraction.pdf',
            ],
        ];

        foreach ($details as $detail) {
            CourseDetail::create($detail);
        }


         {
        // مراحل stages
        $stages = [];
        for ($i = 1; $i <= 6; $i++) {
            $stages[] = [
                'name' => "Stage $i",
                'image' => "https://picsum.photos/seed/stage$i/400/300",
                'country_id' => 1,
                'postion' => $i,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('stages')->insert($stages);

        // مواد subjects
        $subjects = [];
        for ($i = 1; $i <= 6; $i++) {
            $subjects[] = [
                'name' => "Subject $i",
                'image' => "https://picsum.photos/seed/subject$i/400/300",
                'stage_id' => rand(1, 6),
                'postion' => $i,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('subjects')->insert($subjects);

        // معلمين teachers
        $teachers = [];
        for ($i = 1; $i <= 6; $i++) {
            $teachers[] = [
                'name' => "Teacher $i",
                'total_rate' => rand(3, 5),
                'email' => "teacher$i@example.com",
                'phone' => "01000000$i",
                'national_id' => "123456789$i",
                'image' => "https://i.pravatar.cc/150?img=$i",
                'certificate_image' => "https://picsum.photos/seed/cert$i/400/300",
                'experience_image' => "https://picsum.photos/seed/exp$i/400/300",
                'country_id' => 1,
                'stage_id' => rand(1, 6),
                'subject_id' => rand(1, 6),
                'active' => 1,
                'password' => Hash::make('123456'),

                'bank_name' => "Bank $i",
                'account_holder_name' => "Holder $i",
                'account_number' => "ACC000$i",
                'iban' => "IBAN000$i",
                'swift_code' => "SWFT$i",
                'branch_name' => "Branch $i",

                'wallets_name' => "Vodafone Cash",
                'wallets_number' => "01000000$i",

                'commission' => 50,
                'amount' => rand(100, 1000),

                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('teachers')->insert($teachers);

        // كورسات courses
        $courses = [];
        for ($i = 1; $i <= 6; $i++) {
            $courses[] = [
                'teacher_id' => rand(1, 6),
                'stage_id' => rand(1, 6),
                'subject_id' => rand(1, 6),
                'country_id' => 1,
                'title' => "Course Title $i",
                'description' => "This is description for Course $i",
                'type' => $i % 2 == 0 ? 'online' : 'recorded',
                'course_type' => $i % 2 == 0 ? 'private' : 'group',
                'count_student' => rand(10, 100),
                'original_price' => rand(500, 1000),
                'price' => rand(200, 500),
                'discount' => rand(0, 50),
                'currency' => "USD",
                'what_you_will_learn' => "Learning points for course $i",
                'image' => "https://picsum.photos/seed/course$i/400/300",
                'intro_video_url' => "https://www.w3schools.com/html/mov_bbb.mp4",
                'views_count' => rand(100, 1000),
                'subscribers_count' => rand(10, 200),
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('courses')->insert($courses);

        // طلاب students
        $students = [];
        for ($i = 1; $i <= 6; $i++) {
            $students[] = [
                'name' => "Student $i",
                'total_rate' => rand(3, 5),
                'email' => "student$i@example.com",
                'qr_code' => "QR$i",
                'password' => Hash::make('123456'),
                'image' => "https://i.pravatar.cc/150?img=" . ($i+10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('students')->insert($students);

        // ربط الطلاب بالكورسات course_student
        $pivot = [];
        for ($i = 1; $i <= 12; $i++) {
            $pivot[] = [
                'course_id' => rand(1, 6),
                'student_id' => rand(1, 6),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('course_student')->insert($pivot);
    }
    }
}
