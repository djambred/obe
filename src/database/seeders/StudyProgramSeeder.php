<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run(): void
    {
        $fbk = Faculty::where('code', 'FBK')->first();
        $fik = Faculty::where('code', 'FIK')->first();

        if (!$fbk || !$fik) {
            $this->command->error('Faculties not found. Please run FacultySeeder first.');
            return;
        }

        // Program Studi Fakultas Bisnis dan Keuangan
        StudyProgram::create([
            'faculty_id' => $fbk->id,
            'code' => 'MNJ',
            'name' => 'Manajemen',
            'level' => 'S1',
            'degree_awarded' => 'S.M.',
            'accreditation' => 'Baik Sekali',
            'accreditation_date' => '2024-03-01',
            'vision' => 'Menjadi program studi manajemen yang unggul dalam menghasilkan sarjana manajemen yang profesional, berintegritas, dan siap bersaing di era digital dengan fokus pada industri media dan bisnis kreatif.',
            'mission' => "1. Menyelenggarakan pendidikan manajemen berkualitas yang terintegrasi dengan praktik industri\n2. Mengembangkan riset di bidang manajemen strategik, pemasaran, keuangan, dan SDM\n3. Memfasilitasi pengembangan kompetensi manajerial mahasiswa melalui program magang dan studi kasus industri\n4. Membentuk lulusan yang memiliki jiwa kepemimpinan, entrepreneurship, dan kemampuan berinovasi\n5. Menjalin kerjasama dengan dunia usaha untuk penempatan kerja dan pengembangan kurikulum",
            'objectives' => "1. Menghasilkan lulusan yang kompeten dalam manajemen bisnis dan mampu beradaptasi dengan perubahan industri\n2. Menghasilkan penelitian yang berkontribusi pada pengembangan ilmu manajemen\n3. Menghasilkan lulusan yang memiliki soft skill komunikasi, leadership, dan problem solving\n4. Memfasilitasi mahasiswa untuk mendapatkan pengalaman praktis di perusahaan-perusahaan MNC Group",
            'description' => 'Program Studi Manajemen dengan fokus pada manajemen bisnis media dan entertainment, dilengkapi dengan akses magang ke perusahaan-perusahaan MNC Group.',
            'head_of_program' => 'Dr. Agus Wibowo, S.E., M.M.',
            'secretary' => 'Rina Wijayanti, S.E., M.M.',
            'standard_study_period' => 8,
            'is_active' => true,
        ]);

        StudyProgram::create([
            'faculty_id' => $fbk->id,
            'code' => 'AKT',
            'name' => 'Akuntansi',
            'level' => 'S1',
            'degree_awarded' => 'S.Ak.',
            'accreditation' => 'Baik Sekali',
            'accreditation_date' => '2024-03-01',
            'vision' => 'Menjadi program studi akuntansi yang menghasilkan akuntan profesional yang berintegritas tinggi, kompeten dalam praktik akuntansi modern, dan siap menghadapi tantangan bisnis global.',
            'mission' => "1. Menyelenggarakan pendidikan akuntansi yang berkualitas sesuai standar nasional dan internasional\n2. Mengembangkan riset di bidang akuntansi keuangan, manajemen, perpajakan, dan audit\n3. Membekali mahasiswa dengan keterampilan praktis akuntansi melalui laboratorium dan program magang\n4. Membentuk lulusan yang memiliki integritas, etika profesi, dan kemampuan analisis keuangan yang kuat\n5. Mempersiapkan mahasiswa untuk sertifikasi profesi akuntansi",
            'objectives' => "1. Menghasilkan lulusan yang kompeten dalam penyusunan laporan keuangan, audit, perpajakan, dan sistem informasi akuntansi\n2. Menghasilkan penelitian akuntansi yang aplikatif dan bermanfaat bagi dunia usaha\n3. Memfasilitasi mahasiswa untuk memperoleh sertifikasi profesi seperti Brevet Pajak, CA, CPA\n4. Menghasilkan lulusan yang memiliki kemampuan berpikir kritis dan etika profesi yang tinggi",
            'description' => 'Program Studi Akuntansi menyiapkan akuntan profesional dengan kompetensi akuntansi keuangan, manajemen, perpajakan, audit, dan sistem informasi akuntansi.',
            'head_of_program' => 'Dr. Siti Nurjanah, S.E., M.Ak., CA.',
            'secretary' => 'Dedi Prasetyo, S.E., M.Ak.',
            'standard_study_period' => 8,
            'is_active' => true,
        ]);

        StudyProgram::create([
            'faculty_id' => $fbk->id,
            'code' => 'PMT',
            'name' => 'Pendidikan Matematika',
            'level' => 'S1',
            'degree_awarded' => 'S.Pd.',
            'accreditation' => 'Baik',
            'accreditation_date' => '2023-06-15',
            'vision' => 'Menjadi program studi pendidikan matematika yang unggul dalam menghasilkan pendidik matematika yang profesional, inovatif, dan mampu mengintegrasikan teknologi dalam pembelajaran.',
            'mission' => "1. Menyelenggarakan pendidikan calon guru matematika yang berkualitas dengan pendekatan pedagogis modern\n2. Mengembangkan riset di bidang pendidikan matematika dan pembelajaran berbasis teknologi\n3. Membekali mahasiswa dengan kompetensi pedagogik, profesional, kepribadian, dan sosial\n4. Memfasilitasi pengembangan media pembelajaran matematika yang inovatif dan interaktif\n5. Mempersiapkan lulusan untuk mengikuti program Pendidikan Profesi Guru (PPG)",
            'objectives' => "1. Menghasilkan guru matematika yang kompeten dalam merancang dan melaksanakan pembelajaran matematika\n2. Menghasilkan penelitian dalam bidang pendidikan matematika yang inovatif\n3. Menghasilkan lulusan yang mampu mengembangkan media dan teknologi pembelajaran matematika\n4. Memfasilitasi mahasiswa untuk mengikuti program magang mengajar di sekolah-sekolah mitra",
            'description' => 'Program Studi Pendidikan Matematika menghasilkan calon guru matematika profesional dengan kompetensi pedagogik dan teknologi pembelajaran.',
            'head_of_program' => 'Dr. Ahmad Fauzi, S.Pd., M.Pd.',
            'secretary' => 'Lina Marlina, S.Pd., M.Pd.',
            'standard_study_period' => 8,
            'is_active' => true,
        ]);

        // Program Studi Fakultas Industri Kreatif
        StudyProgram::create([
            'faculty_id' => $fik->id,
            'code' => 'PBI',
            'name' => 'Pendidikan Bahasa Inggris',
            'level' => 'S1',
            'degree_awarded' => 'S.Pd.',
            'accreditation' => 'Baik',
            'accreditation_date' => '2023-06-15',
            'vision' => 'Menjadi program studi pendidikan bahasa Inggris yang unggul dalam menghasilkan pendidik bahasa Inggris profesional yang mampu mengintegrasikan teknologi dan media dalam pembelajaran.',
            'mission' => "1. Menyelenggarakan pendidikan calon guru bahasa Inggris yang berkualitas dengan pendekatan komunikatif dan berbasis teknologi\n2. Mengembangkan riset di bidang pembelajaran bahasa Inggris, linguistik, dan sastra\n3. Membekali mahasiswa dengan kompetensi bahasa Inggris yang tinggi dan kemampuan mengajar yang efektif\n4. Memfasilitasi program pertukaran mahasiswa dan pengalaman mengajar di luar negeri\n5. Mempersiapkan lulusan untuk Program Pendidikan Profesi Guru (PPG) dan sertifikasi TOEFL/IELTS",
            'objectives' => "1. Menghasilkan guru bahasa Inggris yang kompeten dalam listening, speaking, reading, writing, dan teaching methodology\n2. Menghasilkan penelitian dalam bidang pendidikan bahasa Inggris dan linguistik terapan\n3. Menghasilkan lulusan yang memiliki skor TOEFL/IELTS tinggi dan siap berkompetisi global\n4. Memfasilitasi mahasiswa untuk pengalaman mengajar di sekolah mitra dan lembaga kursus",
            'description' => 'Program Studi Pendidikan Bahasa Inggris menghasilkan calon guru bahasa Inggris profesional dengan kompetensi linguistik tinggi dan metodologi pengajaran modern.',
            'head_of_program' => 'Dr. Eka Safitri, S.S., M.Pd.',
            'secretary' => 'Robert Simanjuntak, S.Pd., M.TESOL.',
            'standard_study_period' => 8,
            'is_active' => true,
        ]);

        StudyProgram::create([
            'faculty_id' => $fik->id,
            'code' => 'IKO',
            'name' => 'Sains Komunikasi',
            'level' => 'S1',
            'degree_awarded' => 'S.I.Kom.',
            'accreditation' => 'Unggul',
            'accreditation_date' => '2024-03-01',
            'vision' => 'Menjadi program studi komunikasi terdepan yang menghasilkan komunikator profesional dengan keahlian di bidang media, jurnalistik, public relations, dan komunikasi digital.',
            'mission' => "1. Menyelenggarakan pendidikan komunikasi yang terintegrasi dengan industri media massa dan digital\n2. Mengembangkan riset di bidang komunikasi massa, media digital, dan public relations\n3. Memfasilitasi mahasiswa untuk praktik langsung di media-media MNC Group (RCTI, MNC TV, GTV, iNews)\n4. Membentuk lulusan yang memiliki kemampuan storytelling, content creation, dan media relations\n5. Mengembangkan keterampilan komunikasi multiplatform: TV, radio, online, social media",
            'objectives' => "1. Menghasilkan lulusan yang kompeten sebagai jurnalis, content creator, PR specialist, atau broadcaster\n2. Menghasilkan karya jurnalistik dan konten kreatif yang berkualitas\n3. Memberikan akses magang dan kerja di stasiun TV, radio, media online, dan agency PR\n4. Menghasilkan lulusan yang memahami etika komunikasi dan media literacy",
            'description' => 'Program Studi Sains Komunikasi dengan akses langsung ke industri media MNC Group (RCTI, MNC TV, GTV, iNews) untuk pengalaman praktis broadcasting, jurnalistik, dan content creation.',
            'head_of_program' => 'Dr. Diana Puspitasari, S.Sos., M.Si.',
            'secretary' => 'Andika Pratama, S.I.Kom., M.Med.Kom.',
            'standard_study_period' => 8,
            'is_active' => true,
        ]);

        StudyProgram::create([
            'faculty_id' => $fik->id,
            'code' => 'DKV',
            'name' => 'Desain Komunikasi Visual',
            'level' => 'S1',
            'degree_awarded' => 'S.Ds.',
            'accreditation' => 'Baik Sekali',
            'accreditation_date' => '2024-03-01',
            'vision' => 'Menjadi program studi desain komunikasi visual yang menghasilkan desainer kreatif, inovatif, dan profesional yang mampu bersaing di industri kreatif nasional dan internasional.',
            'mission' => "1. Menyelenggarakan pendidikan desain yang berkualitas dengan penguasaan software dan teknologi terkini\n2. Mengembangkan karya desain yang inovatif, estetis, dan fungsional\n3. Memfasilitasi mahasiswa untuk praktik di agency kreatif, production house, dan departemen kreatif perusahaan\n4. Membentuk lulusan yang memiliki kemampuan visual thinking, branding, dan multimedia design\n5. Mengembangkan portfolio profesional mahasiswa untuk persiapan karir",
            'objectives' => "1. Menghasilkan lulusan yang kompeten dalam graphic design, branding, UI/UX design, motion graphics, dan advertising\n2. Menghasilkan karya desain yang memenangkan kompetisi dan diakui industri\n3. Memberikan akses magang di creative agency dan production house MNC Group\n4. Menghasilkan lulusan yang siap bekerja atau berwirausaha di bidang desain",
            'description' => 'Program Studi Desain Komunikasi Visual menghasilkan desainer profesional dengan keahlian graphic design, branding, UI/UX design, motion graphics, dan advertising.',
            'head_of_program' => 'Rendi Saputra, S.Ds., M.Ds.',
            'secretary' => 'Indah Permatasari, S.Ds., M.Sn.',
            'standard_study_period' => 8,
            'is_active' => true,
        ]);

        StudyProgram::create([
            'faculty_id' => $fik->id,
            'code' => 'SIF',
            'name' => 'Sistem Informasi',
            'level' => 'S1',
            'degree_awarded' => 'S.Kom.',
            'accreditation' => 'Baik Sekali',
            'accreditation_date' => '2024-03-01',
            'vision' => 'Menjadi program studi sistem informasi yang unggul dalam menghasilkan profesional IT yang mampu merancang, mengembangkan, dan mengelola sistem informasi untuk mendukung proses bisnis di era digital.',
            'mission' => "1. Menyelenggarakan pendidikan sistem informasi yang berkualitas dengan kurikulum berbasis kebutuhan industri\n2. Mengembangkan riset di bidang sistem informasi, business intelligence, dan enterprise system\n3. Membekali mahasiswa dengan kompetensi analisis bisnis, database management, dan pengembangan aplikasi\n4. Memfasilitasi mahasiswa untuk sertifikasi IT internasional dan pengalaman magang di perusahaan teknologi\n5. Mengembangkan soft skill project management, teamwork, dan communication",
            'objectives' => "1. Menghasilkan lulusan yang kompeten sebagai system analyst, business analyst, database administrator, atau IT consultant\n2. Menghasilkan solusi sistem informasi yang inovatif untuk permasalahan bisnis\n3. Memberikan akses magang di departemen IT perusahaan-perusahaan besar dan startup\n4. Menghasilkan lulusan yang mampu mengelola proyek IT dari perencanaan hingga implementasi",
            'description' => 'Program Studi Sistem Informasi fokus pada analisis bisnis, pengembangan aplikasi, database management, dan business intelligence untuk mendukung transformasi digital perusahaan.',
            'head_of_program' => 'Dr. Budi Santoso, S.Kom., M.T.',
            'secretary' => 'Andi Wijaya, S.Kom., M.Kom.',
            'standard_study_period' => 8,
            'is_active' => true,
        ]);

        StudyProgram::create([
            'faculty_id' => $fik->id,
            'code' => 'ILK',
            'name' => 'Ilmu Komputer',
            'level' => 'S1',
            'degree_awarded' => 'S.Kom.',
            'accreditation' => 'Baik Sekali',
            'accreditation_date' => '2024-03-01',
            'vision' => 'Menjadi program studi ilmu komputer yang terdepan dalam menghasilkan software engineer dan data scientist yang profesional, inovatif, dan mampu bersaing di era artificial intelligence dan big data.',
            'mission' => "1. Menyelenggarakan pendidikan ilmu komputer yang berkualitas dengan fokus pada programming, AI, dan data science\n2. Mengembangkan riset di bidang artificial intelligence, machine learning, computer vision, dan big data\n3. Membekali mahasiswa dengan kompetensi pemrograman tingkat lanjut dan computational thinking\n4. Memfasilitasi mahasiswa untuk pengalaman praktis di tech company dan startup unicorn\n5. Mengembangkan mindset problem solving, innovation, dan entrepreneurship",
            'objectives' => "1. Menghasilkan lulusan yang kompeten sebagai software engineer, data scientist, AI engineer, atau mobile developer\n2. Menghasilkan karya inovasi teknologi seperti aplikasi, AI system, atau data analytics solution\n3. Memberikan akses magang di perusahaan teknologi terkemuka dan startup\n4. Menghasilkan lulusan yang memiliki portfolio project dan siap bekerja di industri tech global",
            'description' => 'Program Studi Ilmu Komputer menghasilkan software engineer dan data scientist dengan keahlian AI, machine learning, web/mobile development, dan data analytics.',
            'head_of_program' => 'Dr. Ir. Hendro Gunawan, S.Kom., M.T.',
            'secretary' => 'Fajar Ramadhan, S.Kom., M.Sc.',
            'standard_study_period' => 8,
            'is_active' => true,
        ]);
    }
}
