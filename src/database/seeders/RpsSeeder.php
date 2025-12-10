<?php

namespace Database\Seeders;

use App\Models\Rps;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Curriculum;
use Illuminate\Database\Seeder;

class RpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * RPS untuk beberapa mata kuliah
     */
    public function run(): void
    {
        // RPS untuk Algoritma dan Pemrograman
        $algoProg = Course::where('code', 'ILK.102')->first();
        $lecturer = Lecturer::where('nidn', '0306067009')->first();
        $curriculum = Curriculum::where('code', 'K2025')->first();

        if ($algoProg && $lecturer && $curriculum) {
            $facultyId = $algoProg->studyProgram->faculty_id ?? 2; // FIK
            $studyProgramId = $algoProg->study_program_id ?? 8; // Ilmu Komputer
            $this->seedAlgoritmaPemrogramanRPS($algoProg, $lecturer, $curriculum, $facultyId, $studyProgramId);
        }

        // RPS untuk Machine Learning (Kecerdasan Buatan)
        $ml = Course::where('code', 'ILK.401')->first();
        if ($ml && $lecturer && $curriculum) {
            $facultyId = $ml->studyProgram->faculty_id ?? 2; // FIK
            $studyProgramId = $ml->study_program_id ?? 8; // Ilmu Komputer
            $this->seedMachineLearningRPS($ml, $lecturer, $curriculum, $facultyId, $studyProgramId);
        }

        $this->command->info('RPS seeded successfully!');
    }

    private function seedAlgoritmaPemrogramanRPS($course, $lecturer, $curriculum, $facultyId, $studyProgramId)
    {
        $weeklyPlan = [
            [
                'week' => 1,
                'topics' => ['Pengenalan Pemrograman', 'Konsep Algoritma', 'Variabel dan Tipe Data'],
                'sub_cpmk' => ['Sub-CPMK-01.1'],
                'methods' => ['Ceramah', 'Diskusi', 'Praktikum'],
                'materials' => ['Slide perkuliahan', 'Modul praktikum', 'Video tutorial'],
                'assessment' => ['Quiz', 'Latihan pemrograman'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat menjelaskan konsep pemrograman dan membuat program sederhana'
            ],
            [
                'week' => 2,
                'topics' => ['Operator dan Ekspresi', 'Input Output', 'Formatting'],
                'sub_cpmk' => ['Sub-CPMK-01.2'],
                'methods' => ['Praktikum', 'Problem-based Learning'],
                'materials' => ['Modul praktikum', 'Exercise sheets'],
                'assessment' => ['Tugas pemrograman', 'Praktikum'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat menggunakan berbagai operator dalam program'
            ],
            [
                'week' => 3,
                'topics' => ['Struktur Percabangan', 'If-Else Statement', 'Switch-Case'],
                'sub_cpmk' => ['Sub-CPMK-02.1'],
                'methods' => ['Praktikum', 'Studi Kasus'],
                'materials' => ['Slide', 'Source code examples', 'Problem sets'],
                'assessment' => ['Praktikum', 'Quiz'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat mengimplementasikan decision making dalam program'
            ],
            [
                'week' => 4,
                'topics' => ['Struktur Perulangan', 'For Loop', 'While Loop', 'Do-While'],
                'sub_cpmk' => ['Sub-CPMK-02.2'],
                'methods' => ['Praktikum', 'Problem Solving'],
                'materials' => ['Modul praktikum', 'Coding exercises'],
                'assessment' => ['Praktikum', 'Coding challenge'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat mengimplementasikan iterasi dalam program'
            ],
            [
                'week' => 5,
                'topics' => ['Nested Loop', 'Kombinasi Percabangan dan Perulangan', 'Pattern Printing'],
                'sub_cpmk' => ['Sub-CPMK-02.3'],
                'methods' => ['Praktikum', 'Project-based Learning'],
                'materials' => ['Problem sets', 'Pattern examples'],
                'assessment' => ['Mini project', 'Praktikum'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat menyelesaikan masalah kompleks dengan nested loop'
            ],
            [
                'week' => 6,
                'topics' => ['Fungsi dan Prosedur', 'Parameter', 'Return Value', 'Scope Variable'],
                'sub_cpmk' => ['Sub-CPMK-03.1'],
                'methods' => ['Praktikum', 'Modular Programming'],
                'materials' => ['Slide', 'Function examples', 'Best practices guide'],
                'assessment' => ['Praktikum', 'Code review'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat membuat fungsi modular yang reusable'
            ],
            [
                'week' => 7,
                'topics' => ['Rekursi', 'Recursive Thinking', 'Base Case dan Recursive Case'],
                'sub_cpmk' => ['Sub-CPMK-03.2'],
                'methods' => ['Problem-based Learning', 'Praktikum'],
                'materials' => ['Recursion examples', 'Visualization tools'],
                'assessment' => ['Praktikum', 'Problem solving'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat menerapkan konsep rekursi untuk menyelesaikan masalah'
            ],
            [
                'week' => 8,
                'topics' => ['Ujian Tengah Semester (UTS)'],
                'sub_cpmk' => ['All Sub-CPMK minggu 1-7'],
                'methods' => ['Ujian Tertulis', 'Ujian Praktik'],
                'materials' => ['Soal UTS'],
                'assessment' => ['UTS Teori 40%', 'UTS Praktik 60%'],
                'duration' => 150,
                'learning_indicators' => 'Evaluasi pemahaman mahasiswa pada materi minggu 1-7'
            ],
            [
                'week' => 9,
                'topics' => ['Array 1 Dimensi', 'Deklarasi dan Inisialisasi', 'Akses Element'],
                'sub_cpmk' => ['Sub-CPMK-04.1'],
                'methods' => ['Praktikum', 'Hands-on Exercise'],
                'materials' => ['Modul array', 'Code examples'],
                'assessment' => ['Praktikum', 'Quiz'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat menggunakan array 1 dimensi'
            ],
            [
                'week' => 10,
                'topics' => ['Array Multidimensi', 'Matrix Operations'],
                'sub_cpmk' => ['Sub-CPMK-04.2'],
                'methods' => ['Praktikum', 'Problem Solving'],
                'materials' => ['Matrix examples', 'Problem sets'],
                'assessment' => ['Praktikum', 'Tugas'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat bekerja dengan array multidimensi'
            ],
            [
                'week' => 11,
                'topics' => ['Searching Algorithms', 'Linear Search', 'Binary Search'],
                'sub_cpmk' => ['Sub-CPMK-04.3'],
                'methods' => ['Ceramah', 'Praktikum', 'Algorithm Analysis'],
                'materials' => ['Algorithm slides', 'Pseudocode'],
                'assessment' => ['Praktikum', 'Algorithm implementation'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat mengimplementasikan algoritma pencarian'
            ],
            [
                'week' => 12,
                'topics' => ['Sorting Algorithms', 'Bubble Sort', 'Selection Sort', 'Insertion Sort'],
                'sub_cpmk' => ['Sub-CPMK-04.4'],
                'methods' => ['Praktikum', 'Algorithm Visualization'],
                'materials' => ['Sorting animations', 'Complexity analysis'],
                'assessment' => ['Praktikum', 'Algorithm comparison'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat mengimplementasikan algoritma sorting'
            ],
            [
                'week' => 13,
                'topics' => ['String Processing', 'String Functions', 'String Manipulation'],
                'sub_cpmk' => ['Sub-CPMK-05.1'],
                'methods' => ['Praktikum', 'Case Study'],
                'materials' => ['String library documentation', 'Examples'],
                'assessment' => ['Praktikum', 'Mini project'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat melakukan operasi string'
            ],
            [
                'week' => 14,
                'topics' => ['File Processing', 'Read/Write File', 'File Handling'],
                'sub_cpmk' => ['Sub-CPMK-05.2'],
                'methods' => ['Praktikum', 'Real-world Application'],
                'materials' => ['File I/O examples', 'Data files'],
                'assessment' => ['Praktikum', 'Data processing task'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat bekerja dengan file eksternal'
            ],
            [
                'week' => 15,
                'topics' => ['Final Project Development', 'Integration', 'Testing'],
                'sub_cpmk' => ['All Sub-CPMK'],
                'methods' => ['Project-based Learning', 'Mentoring'],
                'materials' => ['Project guidelines', 'Code templates'],
                'assessment' => ['Project progress', 'Code review'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat mengembangkan aplikasi terintegrasi'
            ],
            [
                'week' => 16,
                'topics' => ['Ujian Akhir Semester (UAS)', 'Final Project Presentation'],
                'sub_cpmk' => ['All Sub-CPMK'],
                'methods' => ['Ujian', 'Presentasi'],
                'materials' => ['Soal UAS', 'Project documentation'],
                'assessment' => ['UAS Teori 30%', 'UAS Praktik 30%', 'Final Project 40%'],
                'duration' => 150,
                'learning_indicators' => 'Evaluasi akhir kemampuan pemrograman mahasiswa'
            ],
        ];

        $assessmentPlan = [
            [
                'component' => 'Kehadiran',
                'weight' => 5,
                'description' => 'Minimal kehadiran 80% untuk mengikuti ujian'
            ],
            [
                'component' => 'Tugas & Praktikum',
                'weight' => 25,
                'description' => 'Tugas mingguan dan praktikum'
            ],
            [
                'component' => 'Quiz',
                'weight' => 10,
                'description' => 'Quiz setiap 2-3 minggu'
            ],
            [
                'component' => 'UTS (Teori + Praktik)',
                'weight' => 25,
                'description' => 'Ujian Tengah Semester'
            ],
            [
                'component' => 'UAS (Teori + Praktik)',
                'weight' => 20,
                'description' => 'Ujian Akhir Semester'
            ],
            [
                'component' => 'Final Project',
                'weight' => 15,
                'description' => 'Proyek akhir terintegrasi'
            ],
        ];

        $rpsData = [
            'faculty_id' => $facultyId,
            'study_program_id' => $studyProgramId,
            'course_id' => $course->id,
            'lecturer_id' => $lecturer->id,
            'coordinator_id' => $lecturer->id,
            'head_of_program_id' => $lecturer->id,
            'curriculum_id' => $curriculum->id,
            'academic_year' => '2024/2025',
            'semester' => 'Genap',
            'version' => '1.0',
            'class_code' => 'A',
            'student_quota' => 40,
            'course_description' => 'Mata kuliah ini membahas konsep dasar pemrograman, algoritma, dan struktur data dasar. Mahasiswa akan mempelajari cara berpikir algoritmik, merancang solusi, dan mengimplementasikannya dalam bahasa pemrograman. Topik meliputi variabel, tipe data, operator, struktur kontrol, fungsi, array, searching, sorting, dan file processing.',
            'learning_materials' => 'Slide presentasi, modul praktikum, video tutorial, source code examples, problem sets, coding exercises',
            'prerequisites' => 'Pengantar Teknologi Informasi',
            'clo_list' => ['CPMK-01', 'CPMK-02', 'CPMK-03', 'CPMK-04', 'CPMK-05'],
            'plo_mapped' => ['CPL-P03', 'CPL-KK01', 'CPL-KK02', 'CPL-KU01', 'CPL-KU02'],
            'study_field_mapped' => ['SF-001', 'SF-002', 'SF-003'],
            'performance_indicators' => 'IK-01: Ketepatan pemahaman konsep (30%), IK-02: Kemampuan implementasi praktikum (50%), IK-03: Kualitas dokumentasi kode (20%)',
            'weekly_plan' => $weeklyPlan,
            'assessment_plan' => $assessmentPlan,
            'grading_system' => 'A: 85-100, AB: 80-84, B: 75-79, BC: 70-74, C: 65-69, D: 60-64, E: <60',
            'main_references' => [
                'Deitel, P., & Deitel, H. (2020). C How to Program (9th ed.). Pearson.',
                'Cormen, T. H., et al. (2022). Introduction to Algorithms (4th ed.). MIT Press.',
                'Sedgewick, R., & Wayne, K. (2011). Algorithms (4th ed.). Addison-Wesley.',
            ],
            'supporting_references' => [
                'Kernighan, B. W., & Ritchie, D. M. (1988). The C Programming Language (2nd ed.). Prentice Hall.',
                'Skiena, S. S. (2020). The Algorithm Design Manual (3rd ed.). Springer.',
                'Online resources: GeeksforGeeks, LeetCode, HackerRank',
            ],
            'learning_media' => 'Slide presentasi, video tutorial, coding platform (Replit, VS Code), whiteboard',
            'learning_software' => 'GCC Compiler, Visual Studio Code, Code::Blocks, Dev-C++, Git',
            'status' => 'Approved',
            'is_active' => true,
        ];

        Rps::create($rpsData);
    }

    private function seedMachineLearningRPS($course, $lecturer, $curriculum, $facultyId, $studyProgramId)
    {
        $weeklyPlan = [
            [
                'week' => 1,
                'topics' => ['Introduction to Machine Learning', 'Types of Learning', 'ML Pipeline'],
                'sub_cpmk' => ['Sub-CPMK-01.1'],
                'methods' => ['Ceramah', 'Diskusi', 'Video Tutorial'],
                'materials' => ['Slide', 'ML overview videos', 'Case studies'],
                'assessment' => ['Quiz', 'Discussion'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa memahami konsep dasar machine learning'
            ],
            [
                'week' => 2,
                'topics' => ['Python for Machine Learning', 'NumPy', 'Pandas', 'Matplotlib'],
                'sub_cpmk' => ['Sub-CPMK-01.2'],
                'methods' => ['Praktikum', 'Hands-on Coding'],
                'materials' => ['Jupyter Notebooks', 'Python libraries documentation'],
                'assessment' => ['Praktikum', 'Coding exercise'],
                'duration' => 150,
                'learning_indicators' => 'Mahasiswa dapat menggunakan Python untuk analisis data'
            ],
            // ... (dapat dilanjutkan untuk minggu 3-16)
        ];

        $assessmentPlan = [
            [
                'component' => 'Kehadiran',
                'weight' => 5,
                'description' => 'Minimal kehadiran 80% untuk mengikuti ujian'
            ],
            [
                'component' => 'Tugas & Praktikum',
                'weight' => 30,
                'description' => 'Implementasi algoritma ML dan analisis dataset'
            ],
            [
                'component' => 'Quiz',
                'weight' => 10,
                'description' => 'Quiz teori ML'
            ],
            [
                'component' => 'UTS',
                'weight' => 25,
                'description' => 'Ujian Tengah Semester'
            ],
            [
                'component' => 'UAS + Final Project',
                'weight' => 30,
                'description' => 'Ujian Akhir Semester dan Proyek ML'
            ],
        ];

        $rpsData = [
            'faculty_id' => $facultyId,
            'study_program_id' => $studyProgramId,
            'course_id' => $course->id,
            'lecturer_id' => $lecturer->id,
            'coordinator_id' => $lecturer->id,
            'head_of_program_id' => $lecturer->id,
            'curriculum_id' => $curriculum->id,
            'academic_year' => '2024/2025',
            'semester' => 'Ganjil',
            'version' => '1.0',
            'class_code' => 'A',
            'student_quota' => 40,
            'course_description' => 'Mata kuliah ini membahas konsep fundamental machine learning termasuk supervised learning, unsupervised learning, data preprocessing, feature engineering, dan model evaluation. Mahasiswa akan mempelajari berbagai algoritma ML dan mengimplementasikannya menggunakan Python dan library populer seperti Scikit-learn, TensorFlow, dan PyTorch.',
            'learning_materials' => 'Jupyter Notebooks, Dataset samples, Video tutorials, Code examples, Research papers',
            'prerequisites' => 'Algoritma dan Pemrograman, Struktur Data, Statistika',
            'clo_list' => ['CPMK-01', 'CPMK-02', 'CPMK-03', 'CPMK-04', 'CPMK-05'],
            'plo_mapped' => ['CPL-P05', 'CPL-KK01', 'CPL-KK02', 'CPL-KK07'],
            'study_field_mapped' => ['SF-007', 'SF-008'],
            'performance_indicators' => 'IK-01: Pemahaman algoritma ML (30%), IK-02: Implementasi dan eksperimen (50%), IK-03: Analisis hasil dan dokumentasi (20%)',
            'weekly_plan' => $weeklyPlan,
            'assessment_plan' => $assessmentPlan,
            'grading_system' => 'A: 85-100, AB: 80-84, B: 75-79, BC: 70-74, C: 65-69, D: 60-64, E: <60',
            'main_references' => [
                'Géron, A. (2022). Hands-On Machine Learning with Scikit-Learn, Keras, and TensorFlow (3rd ed.). O\'Reilly.',
                'Murphy, K. P. (2022). Probabilistic Machine Learning: An Introduction. MIT Press.',
                'Hastie, T., et al. (2017). The Elements of Statistical Learning (2nd ed.). Springer.',
            ],
            'supporting_references' => [
                'Bishop, C. M. (2006). Pattern Recognition and Machine Learning. Springer.',
                'Online courses: Coursera Machine Learning by Andrew Ng, Fast.ai',
            ],
            'learning_media' => 'Jupyter Notebook, Google Colab, Slide presentasi, Interactive visualizations',
            'learning_software' => 'Python, Jupyter, Scikit-learn, TensorFlow, PyTorch, Pandas, NumPy',
            'status' => 'Approved',
            'is_active' => true,
        ];

        Rps::create($rpsData);
    }
}
