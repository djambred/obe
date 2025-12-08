<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\University;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $university = University::where('code', 'MNCU')->first();

        if (!$university) {
            $this->command->error('University not found. Please run UniversitySeeder first.');
            return;
        }

        // Fakultas Bisnis dan Keuangan
        Faculty::create([
            'university_id' => $university->id,
            'code' => 'FBK',
            'name' => 'Fakultas Bisnis dan Keuangan',
            'logo' => null,
            'vision' => 'Menjadi fakultas terkemuka yang menghasilkan lulusan profesional di bidang bisnis dan keuangan yang siap bersaing di era industri 4.0 dan memiliki integritas tinggi.',
            'mission' => "1. Menyelenggarakan pendidikan berkualitas di bidang bisnis dan keuangan yang berorientasi pada praktik industri\n2. Mengembangkan penelitian dan inovasi dalam bidang manajemen bisnis, akuntansi, dan pendidikan matematika\n3. Membangun kemitraan strategis dengan industri untuk memfasilitasi program magang dan penyerapan lulusan\n4. Membentuk lulusan yang memiliki kompetensi profesional, entrepreneurial mindset, dan karakter berintegritas\n5. Berkontribusi pada pengembangan ekonomi masyarakat melalui program pengabdian dan konsultasi bisnis",
            'objectives' => "1. Menghasilkan lulusan yang kompeten dalam bidang manajemen, akuntansi, dan pendidikan matematika sesuai standar nasional dan internasional\n2. Menghasilkan penelitian yang berkontribusi pada pengembangan ilmu bisnis dan keuangan\n3. Membangun jejaring kerjasama dengan dunia usaha dan industri untuk penempatan kerja lulusan\n4. Mengembangkan program pembelajaran yang inovatif dan berbasis teknologi digital\n5. Meningkatkan kualitas SDM dosen dan tenaga kependidikan secara berkelanjutan",
            'description' => 'Fakultas Bisnis dan Keuangan menyelenggarakan program pendidikan S1 Manajemen, S1 Akuntansi, dan S1 Pendidikan Matematika dengan fokus pada integrasi teori dan praktik industri.',
            'dean_name' => 'Dr. Bambang Supriyanto, S.E., M.M.',
            'phone' => '0811-1531-890',
            'email' => 'fbk@mncu.ac.id',
            'accreditation' => 'Baik Sekali',
            'accreditation_date' => '2024-02-10',
            'is_active' => true,
        ]);

        // Fakultas Industri Kreatif
        Faculty::create([
            'university_id' => $university->id,
            'code' => 'FIK',
            'name' => 'Fakultas Industri Kreatif',
            'logo' => null,
            'vision' => 'Menjadi fakultas unggulan dalam menghasilkan SDM kreatif, inovatif, dan profesional di bidang komunikasi, desain, teknologi informasi, dan bahasa yang berdaya saing global.',
            'mission' => "1. Menyelenggarakan pendidikan tinggi berkualitas di bidang industri kreatif yang terintegrasi dengan perkembangan teknologi dan kebutuhan industri\n2. Mengembangkan riset dan karya kreatif yang inovatif dan bermanfaat bagi masyarakat\n3. Memfasilitasi pengembangan soft skill dan hard skill mahasiswa melalui praktik industri dan kolaborasi dengan media profesional\n4. Menciptakan ekosistem pembelajaran yang mendukung kreativitas, inovasi, dan kewirausahaan\n5. Membangun kemitraan strategis dengan industri kreatif, media, dan teknologi untuk memberikan pengalaman belajar praktis",
            'objectives' => "1. Menghasilkan lulusan yang kompeten di bidang komunikasi, desain, sistem informasi, ilmu komputer, dan bahasa Inggris\n2. Menghasilkan karya kreatif dan inovasi teknologi yang berkontribusi pada industri kreatif nasional\n3. Memberikan pengalaman praktis kepada mahasiswa melalui program magang di perusahaan media dan teknologi ternama\n4. Mengembangkan program studi yang responsif terhadap perkembangan industri kreatif dan teknologi digital\n5. Membangun reputasi internasional melalui kerjasama akademik dan riset dengan institusi global",
            'description' => 'Fakultas Industri Kreatif menyelenggarakan program S1 Pendidikan Bahasa Inggris, S1 Sains Komunikasi, S1 Desain Komunikasi Visual, S1 Sistem Informasi, dan S1 Ilmu Komputer dengan pendekatan learning by doing dan akses langsung ke industri media MNC Group.',
            'dean_name' => 'Dr. Irma Susanti, S.Sos., M.Si.',
            'phone' => '0811-1531-891',
            'email' => 'fik@mncu.ac.id',
            'accreditation' => 'Baik Sekali',
            'accreditation_date' => '2024-02-10',
            'is_active' => true,
        ]);
    }
}
