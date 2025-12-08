<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        University::create([
            'code' => 'MNCU',
            'name' => 'MNC University',
            'logo' => null,
            'address' => 'Jl. Panjang Blok A8, Green Garden, Jakarta Barat, DKI Jakarta 11520',
            'phone' => '0811-1531-889',
            'email' => 'infopmb@mncu.ac.id',
            'website' => 'https://mncu.ac.id',
            'accreditation' => 'Baik Sekali',
            'accreditation_date' => '2024-01-15',
            'vision' => 'Menjadi perguruan tinggi terkemuka yang menghasilkan lulusan berkualitas tinggi dan siap kerja melalui integrasi pendidikan dengan industri, khususnya dalam bidang media, hiburan, dan bisnis kreatif.',
            'mission' => [
                '1. Menyelenggarakan pendidikan tinggi berkualitas yang berorientasi pada kebutuhan industri dan perkembangan teknologi',
                '2. Mengintegrasikan pembelajaran dengan praktik industri melalui kerjasama dengan MNC Group dan perusahaan terkemuka lainnya',
                '3. Mengembangkan kompetensi mahasiswa melalui program magang, praktik kerja, dan akses langsung ke dunia industri',
                '4. Menyelenggarakan penelitian dan pengabdian masyarakat yang berdampak positif bagi kemajuan ilmu pengetahuan dan kesejahteraan masyarakat',
                '5. Menciptakan lingkungan akademik yang kondusif, inovatif, dan berbasis teknologi untuk mendukung pengembangan potensi mahasiswa',
            ],
            'objectives' => [
                '1. Menghasilkan lulusan yang kompeten, profesional, dan memiliki daya saing tinggi di pasar kerja nasional maupun internasional',
                '2. Menghasilkan penelitian dan karya ilmiah yang berkontribusi pada pengembangan ilmu pengetahuan dan teknologi',
                '3. Memberikan kontribusi nyata kepada masyarakat melalui program pengabdian dan kemitraan strategis',
                '4. Membangun ekosistem pembelajaran yang terintegrasi dengan industri untuk memfasilitasi transisi mahasiswa dari kampus ke dunia kerja',
                '5. Mengembangkan karakter dan kepribadian mahasiswa yang berintegritas, kreatif, dan memiliki jiwa kewirausahaan',
            ],
            'description' => "MNC University (MNCU) adalah perguruan tinggi terbaik milik MNC Group yang mengusung konsep 'The Real Media & Entertainment Campus'. Pembelajaran di MNCU terintegrasi langsung dengan 83 perusahaan milik MNC Group dalam 4 kelompok usaha: Media & Entertainment, Financial Services, Tourism & Hospitality, serta Energy Investment. Dengan 40% dosen praktisi, program magang sejak tahun ke-3, dan akses kerja langsung setelah lulus.\n\nRektor: Dr. Dendi Pratama\nWakil Rektor 1: Dr. Bernadetta Kwintiana Ane",
            'rector_name' => 'Dr. Dendi Pratama',
            'is_active' => true,
        ]);
    }
}
