<?php

namespace Database\Seeders;

use App\Models\GraduateProfile;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class GraduateProfileSeeder extends Seeder
{
    public function run(): void
    {
        $ilmuKomputer = StudyProgram::where('code', 'ILK')->first();

        if (!$ilmuKomputer) {
            $this->command->error('Study Program Ilmu Komputer not found. Please run StudyProgramSeeder first.');
            return;
        }

        $profiles = [
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL01',
                'name' => 'Software Engineer',
                'description' => 'Lulusan yang mampu merancang, mengembangkan, dan memelihara perangkat lunak berkualitas tinggi menggunakan metodologi modern dan best practices dalam software engineering.',
                'career_prospects' => 'Backend Developer, Frontend Developer, Full-stack Developer, Mobile App Developer, Software Architect, DevOps Engineer',
                'work_areas' => 'Software Development Company, IT Consultant, Startup Technology, Banking & Finance, E-commerce',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL02',
                'name' => 'Data Scientist',
                'description' => 'Lulusan yang mampu mengolah, menganalisis, dan mengekstrak insight dari big data menggunakan teknik machine learning, deep learning, dan statistical analysis untuk mendukung pengambilan keputusan bisnis.',
                'career_prospects' => 'Data Scientist, Data Analyst, Machine Learning Engineer, AI Engineer, Business Intelligence Analyst, Data Engineer',
                'work_areas' => 'Technology Company, Financial Services, Healthcare, E-commerce, Consulting Firm, Research Institution',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL03',
                'name' => 'Network & Security Specialist',
                'description' => 'Lulusan yang mampu merancang, mengimplementasikan, dan mengamankan infrastruktur jaringan komputer serta melakukan security assessment dan incident response terhadap ancaman cyber.',
                'career_prospects' => 'Network Engineer, Security Analyst, Cybersecurity Consultant, Penetration Tester, Security Operations Center (SOC) Analyst, Network Administrator, IT Security Manager',
                'work_areas' => 'Cybersecurity Company, Banking & Finance, Government Agency, Telecommunication, IT Infrastructure, Security Consultant',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL04',
                'name' => 'Web & Mobile Developer',
                'description' => 'Lulusan yang mampu mengembangkan aplikasi web dan mobile yang responsif, user-friendly, dan performant menggunakan teknologi frontend dan backend modern serta mengikuti best practices dalam UI/UX design.',
                'career_prospects' => 'Web Developer, Mobile App Developer, Frontend Developer, Backend Developer, Full-stack Developer, UI/UX Developer',
                'work_areas' => 'Digital Agency, Software House, Startup Technology, E-commerce, Media & Entertainment, Freelancer',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL05',
                'name' => 'Database Administrator & Engineer',
                'description' => 'Lulusan yang mampu merancang, mengimplementasikan, dan mengelola sistem basis data yang efisien, aman, dan scalable untuk mendukung aplikasi enterprise serta melakukan optimasi performa database.',
                'career_prospects' => 'Database Administrator, Database Engineer, Data Warehouse Engineer, Database Architect, ETL Developer',
                'work_areas' => 'Enterprise Company, Banking & Finance, Healthcare, Government, IT Consultant, Cloud Service Provider',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL06',
                'name' => 'IT Project Manager',
                'description' => 'Lulusan yang mampu merencanakan, mengelola, dan memimpin proyek teknologi informasi dari tahap inisiasi hingga deployment dengan memastikan proyek selesai tepat waktu, sesuai budget, dan memenuhi requirement.',
                'career_prospects' => 'IT Project Manager, Scrum Master, Product Owner, Technical Lead, Program Manager, IT Consultant',
                'work_areas' => 'IT Consulting, Software Company, Enterprise Corporation, Startup, Government Project, Digital Transformation',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL07',
                'name' => 'Cloud Solutions Architect',
                'description' => 'Lulusan yang mampu merancang dan mengimplementasikan solusi cloud computing yang scalable, reliable, dan cost-effective menggunakan platform cloud seperti AWS, Azure, atau Google Cloud.',
                'career_prospects' => 'Cloud Solutions Architect, Cloud Engineer, DevOps Engineer, Site Reliability Engineer (SRE), Cloud Consultant',
                'work_areas' => 'Cloud Service Provider, Enterprise Technology, Digital Transformation, IT Consulting, Startup Technology',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL08',
                'name' => 'Technology Entrepreneur',
                'description' => 'Lulusan yang mampu mengidentifikasi peluang bisnis berbasis teknologi, merancang model bisnis, dan membangun startup teknologi dengan menggabungkan kompetensi teknis dan entrepreneurial skills.',
                'career_prospects' => 'Startup Founder, Technology Entrepreneur, Product Manager, Innovation Manager, Business Development Manager, Tech Consultant',
                'work_areas' => 'Technology Startup, Innovation Hub, Digital Business, Venture Capital, Incubator/Accelerator, Tech Consulting',
                'order' => 8,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL09',
                'name' => 'Computer Vision & AI Engineer',
                'description' => 'Lulusan yang mampu mengembangkan sistem computer vision dan artificial intelligence untuk aplikasi seperti face recognition, object detection, autonomous systems, dan intelligent automation.',
                'career_prospects' => 'Computer Vision Engineer, AI Engineer, Deep Learning Engineer, Research Scientist, AI Product Engineer',
                'work_areas' => 'AI Research Lab, Technology Company, Autonomous Systems, Healthcare Technology, Security & Surveillance, Robotics',
                'order' => 9,
                'is_active' => true,
            ],
            [
                'study_program_id' => $ilmuKomputer->id,
                'code' => 'PL10',
                'name' => 'IoT & Embedded Systems Developer',
                'description' => 'Lulusan yang mampu merancang dan mengimplementasikan sistem Internet of Things dan embedded systems untuk smart devices, automation, dan monitoring systems dengan mengintegrasikan hardware dan software.',
                'career_prospects' => 'IoT Developer, Embedded Systems Engineer, IoT Solutions Architect, Firmware Engineer, Smart Device Developer',
                'work_areas' => 'IoT Company, Smart Home/City Solutions, Industrial Automation, Agriculture Technology, Healthcare IoT, Automotive',
                'order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($profiles as $profileData) {
            GraduateProfile::create($profileData);
        }

        $this->command->info('Graduate Profiles seeded successfully for Ilmu Komputer!');
    }
}
