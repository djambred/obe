<?php

namespace Database\Seeders;

use App\Models\ProgramLearningOutcome;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class ProgramLearningOutcomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * CPL (Capaian Pembelajaran Lulusan) untuk Program Studi Ilmu Komputer
     */
    public function run(): void
    {
        $ilmuKomputer = StudyProgram::where('code', 'ILK')->first();

        if (!$ilmuKomputer) {
            $this->command->error('Study Program Ilmu Komputer not found. Please run StudyProgramSeeder first.');
            return;
        }

        $cplData = [
            // SIKAP (S)
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-S01',
                'description' => 'Bertakwa kepada Tuhan Yang Maha Esa dan mampu menunjukkan sikap religius',
                'category' => 'Sikap',
                'bloom_affective_level' => 'A3',
                'sndikti_reference' => 'SN-Dikti Pasal 5 ayat 1a',
                'kkni_level' => 'Level 6',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-S02',
                'description' => 'Menjunjung tinggi nilai kemanusiaan dalam menjalankan tugas berdasarkan agama, moral, dan etika',
                'category' => 'Sikap',
                'bloom_affective_level' => 'A4',
                'sndikti_reference' => 'SN-Dikti Pasal 5 ayat 1b',
                'kkni_level' => 'Level 6',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-S03',
                'description' => 'Berkontribusi dalam peningkatan mutu kehidupan bermasyarakat, berbangsa, bernegara, dan kemajuan peradaban berdasarkan Pancasila',
                'category' => 'Sikap',
                'bloom_affective_level' => 'A4',
                'sndikti_reference' => 'SN-Dikti Pasal 5 ayat 1c',
                'kkni_level' => 'Level 6',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-S04',
                'description' => 'Berperan sebagai warga negara yang bangga dan cinta tanah air, memiliki nasionalisme serta rasa tanggungjawab pada negara dan bangsa',
                'category' => 'Sikap',
                'bloom_affective_level' => 'A3',
                'sndikti_reference' => 'SN-Dikti Pasal 5 ayat 1d',
                'kkni_level' => 'Level 6',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-S05',
                'description' => 'Menghargai keanekaragaman budaya, pandangan, agama, dan kepercayaan, serta pendapat atau temuan orisinal orang lain',
                'category' => 'Sikap',
                'bloom_affective_level' => 'A3',
                'sndikti_reference' => 'SN-Dikti Pasal 5 ayat 1e',
                'kkni_level' => 'Level 6',
                'order' => 5,
                'is_active' => true,
            ],

            // PENGETAHUAN (P)
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-P01',
                'description' => 'Menguasai konsep teoretis sains alam, aplikasi matematika rekayasa, prinsip-prinsip rekayasa (engineering principles), sains rekayasa dan perancangan rekayasa yang diperlukan untuk analisis dan perancangan sistem komputasi',
                'category' => 'Pengetahuan',
                'bloom_cognitive_level' => 'C4',
                'sndikti_reference' => 'SN-Dikti bidang Ilmu Komputer',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'ACM Computing Curricula 2020',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-P02',
                'description' => 'Menguasai pengetahuan tentang konsep teoritis bidang pengetahuan Ilmu Komputer secara umum dan konsep teoritis bagian khusus dalam bidang pengetahuan tersebut secara mendalam',
                'category' => 'Pengetahuan',
                'bloom_cognitive_level' => 'C5',
                'sndikti_reference' => 'SN-Dikti bidang Ilmu Komputer',
                'kkni_level' => 'Level 6',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-P03',
                'description' => 'Menguasai pengetahuan tentang algoritma, kompleksitas algoritma, dan pemrograman untuk menyelesaikan permasalahan komputasi',
                'category' => 'Pengetahuan',
                'bloom_cognitive_level' => 'C5',
                'sndikti_reference' => 'Core Computing - Algorithm and Complexity',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'IEEE-CS/ACM Computing Curricula',
                'order' => 8,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-P04',
                'description' => 'Menguasai pengetahuan tentang konsep dan arsitektur sistem komputasi dari perangkat lunak, perangkat keras, dan jaringan komputer',
                'category' => 'Pengetahuan',
                'bloom_cognitive_level' => 'C4',
                'sndikti_reference' => 'Core Computing - Systems',
                'kkni_level' => 'Level 6',
                'order' => 9,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-P05',
                'description' => 'Menguasai konsep data science, machine learning, dan artificial intelligence untuk analisis dan pengambilan keputusan berbasis data',
                'category' => 'Pengetahuan',
                'bloom_cognitive_level' => 'C5',
                'sndikti_reference' => 'Core Computing - Data Science & AI',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'CRISP-DM, MLOps',
                'order' => 10,
                'is_active' => true,
            ],

            // KETERAMPILAN UMUM (KU)
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KU01',
                'description' => 'Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam konteks pengembangan atau implementasi ilmu pengetahuan dan teknologi yang memperhatikan dan menerapkan nilai humaniora',
                'category' => 'Keterampilan Umum',
                'bloom_psychomotor_level' => 'P4',
                'sndikti_reference' => 'SN-Dikti Pasal 9 ayat 1a',
                'kkni_level' => 'Level 6',
                'order' => 11,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KU02',
                'description' => 'Mampu menunjukkan kinerja mandiri, bermutu, dan terukur',
                'category' => 'Keterampilan Umum',
                'bloom_psychomotor_level' => 'P4',
                'sndikti_reference' => 'SN-Dikti Pasal 9 ayat 1b',
                'kkni_level' => 'Level 6',
                'order' => 12,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KU03',
                'description' => 'Mampu mengkaji implikasi pengembangan atau implementasi ilmu pengetahuan teknologi yang memperhatikan dan menerapkan nilai humaniora sesuai dengan keahliannya',
                'category' => 'Keterampilan Umum',
                'bloom_psychomotor_level' => 'P3',
                'sndikti_reference' => 'SN-Dikti Pasal 9 ayat 1c',
                'kkni_level' => 'Level 6',
                'order' => 13,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KU04',
                'description' => 'Mampu menyusun deskripsi saintifik hasil kajian tersebut di atas dalam bentuk skripsi atau laporan tugas akhir, dan mengunggahnya dalam laman perguruan tinggi',
                'category' => 'Keterampilan Umum',
                'bloom_psychomotor_level' => 'P4',
                'sndikti_reference' => 'SN-Dikti Pasal 9 ayat 1d',
                'kkni_level' => 'Level 6',
                'order' => 14,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KU05',
                'description' => 'Mampu mengambil keputusan secara tepat dalam konteks penyelesaian masalah di bidang keahliannya, berdasarkan hasil analisis informasi dan data',
                'category' => 'Keterampilan Umum',
                'bloom_psychomotor_level' => 'P4',
                'sndikti_reference' => 'SN-Dikti Pasal 9 ayat 1e',
                'kkni_level' => 'Level 6',
                'order' => 15,
                'is_active' => true,
            ],

            // KETERAMPILAN KHUSUS (KK)
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KK01',
                'description' => 'Mampu menganalisis permasalahan komputasi kompleks dan menerapkan prinsip-prinsip komputasi serta disiplin ilmu lain yang relevan untuk mengidentifikasi solusi',
                'category' => 'Keterampilan Khusus',
                'bloom_psychomotor_level' => 'P4',
                'sndikti_reference' => 'Computing Professional Skills',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'SFIA (Skills Framework for Information Age)',
                'order' => 16,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KK02',
                'description' => 'Mampu merancang, mengimplementasi, dan mengevaluasi solusi berbasis komputasi dengan menggunakan teknik, keterampilan, dan tools modern yang sesuai',
                'category' => 'Keterampilan Khusus',
                'bloom_psychomotor_level' => 'P5',
                'sndikti_reference' => 'System Design & Implementation',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'Software Engineering Body of Knowledge (SWEBOK)',
                'order' => 17,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KK03',
                'description' => 'Mampu berkomunikasi secara efektif dengan berbagai pemangku kepentingan (stakeholder) dalam tim untuk mencapai tujuan bersama',
                'category' => 'Keterampilan Khusus',
                'bloom_psychomotor_level' => 'P4',
                'sndikti_reference' => 'Professional Communication',
                'kkni_level' => 'Level 6',
                'order' => 18,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KK04',
                'description' => 'Mampu mengenali tanggung jawab profesional, etika, dan membuat penilaian berdasarkan informasi dengan mempertimbangkan dampak solusi komputasi dalam konteks global, ekonomi, lingkungan, dan sosial',
                'category' => 'Keterampilan Khusus',
                'bloom_psychomotor_level' => 'P4',
                'sndikti_reference' => 'Professional Ethics & Responsibility',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'ACM Code of Ethics',
                'order' => 19,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KK05',
                'description' => 'Mampu berfungsi secara efektif sebagai anggota atau pemimpin dalam tim yang terlibat dalam aktivitas yang sesuai dengan bidang program studi',
                'category' => 'Keterampilan Khusus',
                'bloom_psychomotor_level' => 'P4',
                'sndikti_reference' => 'Teamwork & Leadership',
                'kkni_level' => 'Level 6',
                'order' => 20,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KK06',
                'description' => 'Mampu mengembangkan sistem perangkat lunak yang kompleks dengan menerapkan prinsip-prinsip software engineering, termasuk analisis kebutuhan, perancangan, implementasi, testing, dan maintenance',
                'category' => 'Keterampilan Khusus',
                'bloom_psychomotor_level' => 'P5',
                'sndikti_reference' => 'Software Engineering',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'SWEBOK, ISO/IEC 25010',
                'order' => 21,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KK07',
                'description' => 'Mampu mengolah, menganalisis, dan memvisualisasikan big data menggunakan teknik data science, machine learning, dan artificial intelligence untuk mendukung pengambilan keputusan',
                'category' => 'Keterampilan Khusus',
                'bloom_psychomotor_level' => 'P5',
                'sndikti_reference' => 'Data Science & AI',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'CRISP-DM, TensorFlow, PyTorch',
                'order' => 22,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'CPL-KK08',
                'description' => 'Mampu merancang, mengimplementasi, dan mengelola infrastruktur jaringan komputer yang aman, handal, dan optimal dengan mempertimbangkan aspek keamanan informasi',
                'category' => 'Keterampilan Khusus',
                'bloom_psychomotor_level' => 'P5',
                'sndikti_reference' => 'Network & Security',
                'kkni_level' => 'Level 6',
                'industry_reference' => 'NIST Cybersecurity Framework, ISO 27001',
                'order' => 23,
                'is_active' => true,
            ],
        ];

        foreach ($cplData as $cpl) {
            ProgramLearningOutcome::create($cpl);
        }

        $this->command->info('Program Learning Outcomes (CPL) seeded successfully for Ilmu Komputer!');
        $this->command->info('Total CPL: ' . count($cplData));
    }
}
