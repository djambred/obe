# Instruksi Setup CPL, CPMK, Sub-CPMK, Indikator Kinerja, dan RPS PDF

## 1. Jalankan dengan Script Otomatis (Recommended)

Script ini akan menginstall DomPDF dan menjalankan semua seeder secara otomatis:

```bash
cd /root/perkuliahan/obe
chmod +x run-obe-seeders.sh
./run-obe-seeders.sh
```

Script akan:
- ✅ Install package DomPDF
- ✅ Seed CPL (23 records)
- ✅ Seed CPMK (25 records)
- ✅ Seed Sub-CPMK & Indikator Kinerja
- ✅ Seed RPS (2 records)

## 2. Install Package DomPDF (Manual)

Jika ingin install manual:

```bash
docker exec -it obe-php-1 composer require barryvdh/laravel-dompdf
```

## 3. Publish Config DomPDF (Opsional)

```bash
docker exec -it obe-php-1 php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## 4. Jalankan Database Seeder (Manual)

Seeder akan mengisi data:
- **CPL (Program Learning Outcomes)**: 23 CPL untuk Program Studi Ilmu Komputer
- **CPMK (Course Learning Outcomes)**: CPMK untuk 5 mata kuliah utama
- **Sub-CPMK**: Breakdown detail CPMK per pertemuan
- **Indikator Kinerja**: Metrik penilaian untuk setiap Sub-CPMK
- **RPS**: Rencana Pembelajaran Semester lengkap

```bash
# Satu per satu
docker exec -it obe-php-1 php artisan db:seed --class=ProgramLearningOutcomeSeeder
docker exec -it obe-php-1 php artisan db:seed --class=CourseLearningOutcomeSeeder
docker exec -it obe-php-1 php artisan db:seed --class=SubCourseLearningOutcomeSeeder
docker exec -it obe-php-1 php artisan db:seed --class=RpsSeeder
```

Atau jalankan semuanya sekaligus:

```bash
docker exec -it obe-php-1 php artisan db:seed
```

## 5. Cek Data yang Sudah Ter-seed

```bash
cd /root/perkuliahan/obe
chmod +x check-obe-data.sh
./check-obe-data.sh
```

Atau manual:

```bash
docker exec -it obe-php-1 php artisan tinker

# Di dalam tinker:
App\Models\ProgramLearningOutcome::count()  // CPL
App\Models\CourseLearningOutcome::count()   // CPMK
App\Models\SubCourseLearningOutcome::count() // Sub-CPMK
App\Models\PerformanceIndicator::count()    // Indikator
App\Models\Rps::count()                     // RPS
```

## 6. Struktur Data yang Dibuat

### A. CPL (Capaian Pembelajaran Lulusan)
- **Sikap (S)**: 5 CPL - S01 s/d S05
- **Pengetahuan (P)**: 5 CPL - P01 s/d P05
- **Keterampilan Umum (KU)**: 5 CPL - KU01 s/d KU05
- **Keterampilan Khusus (KK)**: 8 CPL - KK01 s/d KK08

**Total: 23 CPL** yang sesuai dengan SN-Dikti dan KKNI Level 6

### B. CPMK (Capaian Pembelajaran Mata Kuliah)
Dibuat untuk 5 mata kuliah:
1. **ILK101 - Algoritma dan Pemrograman** (5 CPMK)
2. **ILK201 - Struktur Data** (5 CPMK)
3. **ILK202 - Basis Data** (5 CPMK)
4. **ILK301 - Pemrograman Web** (5 CPMK)
5. **ILK401 - Machine Learning** (5 CPMK)

### C. Sub-CPMK dan Indikator Kinerja
- **Sub-CPMK**: Breakdown detail per minggu/topik
- **Indikator Kinerja**: 3 indikator standar untuk setiap Sub-CPMK
  - IK-01: Ketepatan pemahaman konsep (30%)
  - IK-02: Kemampuan implementasi praktikum (50%)
  - IK-03: Kualitas dokumentasi kode (20%)

### D. RPS (Rencana Pembelajaran Semester)
Dibuat untuk 2 mata kuliah dengan detail lengkap:
1. **Algoritma dan Pemrograman** (16 minggu)
2. **Machine Learning** (16 minggu)

Setiap RPS mencakup:
- Identitas mata kuliah
- Deskripsi dan tujuan pembelajaran
- Pemetaan CPMK ke CPL
- Rencana pembelajaran 16 minggu
- Sistem penilaian dan bobot
- Referensi utama dan pendukung
- Media dan software pembelajaran

## 7. Cara Menggunakan Fitur Download RPS PDF

### A. Melalui Admin Panel
1. Login ke aplikasi Filament Admin
2. Navigasi ke menu **RPS** di sidebar
3. Pada tabel RPS, klik tombol **Download PDF** (icon arrow down) pada baris RPS yang ingin didownload
4. File PDF akan otomatis terdownload

### B. Route URL
```
GET /rps/{id}/download-pdf
```

Contoh:
```
https://your-domain.com/rps/1/download-pdf
```

### C. Format Nama File PDF
```
RPS_{KODE_MK}_{TAHUN_AKADEMIK}_{SEMESTER}.pdf
```

Contoh:
```
RPS_ILK101_2024/2025_Ganjil.pdf
```

## 8. Struktur PDF RPS

PDF RPS yang dihasilkan mencakup:

1. **Header**
   - Judul RPS
   - Kode dan Nama Mata Kuliah
   - Program Studi dan Fakultas
   - Tahun Akademik dan Semester

2. **Identitas Mata Kuliah**
   - Kode, Nama, SKS
   - Koordinator/Dosen Pengampu
   - Kurikulum, Kelas, Kuota

3. **Deskripsi Mata Kuliah**
   - Deskripsi lengkap

4. **Capaian Pembelajaran**
   - Daftar CPMK
   - Pemetaan ke CPL

5. **Sistem Penilaian**
   - Tabel komponen penilaian dengan bobot
   - Konversi nilai (A-E)

6. **Rencana Pembelajaran Mingguan**
   - Tabel 16 minggu
   - Topik/Materi per minggu
   - Sub-CPMK
   - Metode pembelajaran
   - Penilaian
   - Durasi

7. **Referensi**
   - Referensi utama
   - Referensi pendukung

8. **Media dan Software**
   - Daftar media pembelajaran
   - Software/tools yang digunakan

9. **Persetujuan**
   - Tanda tangan Kaprodi
   - Tanda tangan Koordinator MK

## 9. Customisasi PDF

Untuk mengubah tampilan PDF, edit file:
```
resources/views/pdf/rps.blade.php
```

PDF sudah disesuaikan dengan format RPS standar:
- ✅ Format landscape A4 untuk tabel mingguan
- ✅ Header: **Data dari tabel `universities`** - Logo - Fakultas - Program Studi
- ✅ Tabel identitas MK dengan layout 2 kolom
- ✅ Tabel otorisasi dengan 4 kolom (Pengembang, Koordinator, Ketua Prodi)
- ✅ Tabel mingguan dengan 9 kolom sesuai standar RPS
- ✅ Font Times New Roman untuk tampilan formal
- ✅ Border hitam dan layout akademik profesional
- ✅ **Nama universitas diambil dari database** (`universities` table)
- ✅ **Logo universitas otomatis ditampilkan** (jika ada di field `logo`)
- ✅ **Alamat dan website universitas** ditampilkan di bawah header (jika ada)
- ✅ Format header hierarkis dengan uppercase text
- ✅ Menggunakan university data dari database yang aktif (`is_active = 1`)

## 9A. Data Universitas untuk PDF

PDF RPS secara otomatis mengambil data universitas dari tabel `universities`:

### Field yang Digunakan:
- **`name`**: Nama universitas (ditampilkan di header utama)
- **`logo`**: Path logo universitas (ditampilkan di atas nama universitas)
- **`address`**: Alamat universitas (ditampilkan di bawah header)
- **`website`**: Website universitas (ditampilkan di bawah header)
- **`is_active`**: Status aktif (PDF menggunakan university yang aktif)

### Cara Mengupdate Data Universitas:

**Via Tinker:**
```bash
docker exec -it obe_php php artisan tinker

# Update nama universitas
$uni = App\Models\University::first();
$uni->name = 'MNC University';
$uni->save();

# Update logo (setelah upload file ke storage/app/public/logos/)
$uni->logo = 'logos/mnc-logo.png';
$uni->save();

# Update alamat dan website
$uni->address = 'Jl. Kebon Sirih No. 17-19, Jakarta Pusat';
$uni->website = 'www.mncu.ac.id';
$uni->save();
```

**Via Seeder:**
Buat file `UniversitySeeder.php`:
```bash
docker exec -it obe_php php artisan make:seeder UniversitySeeder
```

### Upload Logo Universitas:
1. Upload file logo ke folder: `storage/app/public/logos/`
2. Pastikan symbolic link sudah dibuat: `php artisan storage:link`
3. Update field `logo` di database dengan path: `logos/nama-file.png`
4. Logo akan otomatis muncul di PDF RPS

### Format Logo yang Disarankan:
- **Format**: PNG atau JPG dengan background transparan
- **Ukuran**: Maksimal 120px (width) x 60px (height)
- **Resolusi**: 300 DPI untuk kualitas print terbaik

### Contoh Data University:
```php
[
    'code' => 'MNCU',
    'name' => 'MNC University',
    'logo' => 'logos/mnc-university-logo.png',
    'address' => 'Jl. Kebon Sirih No. 17-19, Jakarta Pusat 10340',
    'phone' => '(021) 3983 6666',
    'email' => 'info@mncu.ac.id',
    'website' => 'www.mncu.ac.id',
    'rector_name' => 'Prof. Dr. Ir. John Doe, M.Eng.',
    'accreditation' => 'A',
    'is_active' => true,
]
```

## 9C. Validasi Duplikasi RPS

Sistem memiliki **perlindungan berlapis** untuk mencegah duplikasi RPS:

### 1. Client-side Validation (Real-time)
- **Live Validation**: Form secara otomatis mengecek keberadaan RPS saat user mengisi:
  - Tahun Akademik (onBlur)
  - Semester
  - Kode Kelas (onBlur)
- **Visual Feedback**: Placeholder menampilkan status real-time:
  - ✅ "RPS belum ada" - Aman untuk create
  - ⚠️ "**RPS sudah ada!**" - Kombinasi sudah digunakan
- **Notification Warning**: Pop-up peringatan muncul saat duplikat terdeteksi dengan informasi lengkap

### 2. Server-side Validation (Before Save)
**CreateRps.php**:
- Hook `beforeCreate()` memvalidasi kombinasi unique sebelum menyimpan
- Mengecek: `course_id`, `academic_year`, `semester`, `class_code`
- Jika duplikat ditemukan:
  - Menampilkan notifikasi error persistent
  - Menghentikan proses penyimpanan dengan `halt()`
  - Mencegah data masuk ke database

**EditRps.php**:
- Hook `beforeSave()` memvalidasi saat update
- Mengecualikan record saat ini dari pengecekan duplikat
- Memungkinkan edit RPS yang sama tanpa error
- Mencegah konflik dengan RPS lain

### 3. Database Constraint (Last Defense)
- **Unique Index**: `unique_rps_period` pada kolom:
  - `course_id`
  - `academic_year`
  - `semester`
  - `class_code`
- Sebagai pengaman terakhir jika validasi lain gagal

### Error Message Example
```
⚠️ RPS Sudah Ada!
RPS untuk mata kuliah ini dengan tahun akademik 2024/2025, 
semester Ganjil, dan kelas A sudah ada dalam database. 
Silakan gunakan kelas yang berbeda atau edit RPS yang sudah ada.
```

### Cara Menangani Duplikasi
1. **Ubah Kode Kelas**: Gunakan B, C, D, dst untuk kelas paralel
2. **Edit RPS yang Ada**: Navigasi ke RPS existing dan lakukan update
3. **Ubah Tahun Akademik**: Jika membuat RPS untuk periode berbeda
4. **Ganti Semester**: Jika mata kuliah ditawarkan di semester berbeda

## 9D. Form RPS yang User-Friendly

Form RPS telah diperbaiki dengan struktur yang lebih mudah digunakan:

### Tab 1: 📋 Identitas RPS

**Section: Informasi Institusi**
- **Fakultas**: Select fakultas (required, live update)
  - Saat dipilih, otomatis reset Program Studi dan Mata Kuliah
  - Data dari tabel `faculties`
- **Program Studi**: Select program studi berdasarkan fakultas (required, live update)
  - Disabled jika fakultas belum dipilih
  - Filter: `where('faculty_id', $facultyId)`
  - Saat dipilih, otomatis reset Mata Kuliah dan Kurikulum
  - Data dari tabel `study_programs`

**Section: Informasi Mata Kuliah**
- **Mata Kuliah**: Select mata kuliah berdasarkan program studi (required)
  - Disabled jika program studi belum dipilih
  - Menampilkan: Kode - Nama Mata Kuliah
  - Filter: `where('study_program_id', $studyProgramId)`
  - Data dari tabel `courses`
- **Koordinator/Dosen Pengampu**: Select dosen
- **Kurikulum**: Select kurikulum (filter berdasarkan program studi)
  - Disabled jika program studi belum dipilih
  - Filter: `where('study_program_id', $studyProgramId)`
  - Menampilkan: Kode - Nama Kurikulum
  - Helper text: "Pilih program studi terlebih dahulu"
  - Data dari tabel `curriculums`
- **Tahun Akademik**: Format YYYY/YYYY (contoh: 2024/2025)
  - Live validation onBlur untuk cek duplikat
- **Semester**: Ganjil / Genap / Pendek
  - Live validation untuk cek duplikat
- **Versi RPS**: Default 1.0
- **Kode Kelas**: A, B, C, D (optional)
  - Live validation onBlur untuk cek duplikat
  - Placeholder menampilkan status duplikasi real-time
- **Kuota Mahasiswa**: Numeric (default: 40)

**Section: Deskripsi Mata Kuliah**
- Course Description, Learning Materials, Prerequisites

### Tab 2: 🎯 Capaian Pembelajaran
- **CPMK**: CheckboxList untuk memilih CPMK dari mata kuliah yang dipilih
  - Otomatis load CPMK sesuai course_id
  - Menampilkan: Kode, Deskripsi lengkap, Bobot, Bloom Level
  - Searchable dan bulk toggleable
- **CPL Mapping**: CheckboxList untuk memilih CPL dari database
  - Menampilkan semua CPL (S01-S05, P01-P05, KU01-KU05, KK01-KK08)
  - Menampilkan: Kode, Deskripsi lengkap, Kategori, KKNI Level
  - Dengan icon kategori (👤 Sikap, 📚 Pengetahuan, 🔧 KU, 💻 KK)
  - Searchable dan bulk toggleable
- **Bahan Kajian**: CheckboxList untuk memilih StudyField dari database
  - Menampilkan: Kode, Nama, Deskripsi
  - Data lengkap dari tabel study_fields
  - Searchable dan bulk toggleable

### Tab 3: 📅 Rencana Mingguan
Repeater dengan 16 item default untuk rencana pembelajaran mingguan:
- **Minggu Ke-**: Auto-increment (1-17)
- **Sub-CPMK**: Multiple Select dari database Sub-CPMK
  - Otomatis load Sub-CPMK sesuai course_id yang dipilih
  - Menampilkan kode dan deskripsi
  - Searchable dan preload
- **Indikator Penilaian**: Multiple Select dari PerformanceIndicator
  - Otomatis load indikator berdasarkan Sub-CPMK yang dipilih
  - Menampilkan kode, bobot, dan deskripsi
  - Searchable dan preload
- **Topik/Materi**: Tags input (required)
- **Bentuk Pembelajaran**: Text input (default: Perkuliahan)
- **Metode Pembelajaran**: Tags input (Ceramah, Diskusi, Praktik)
- **Rencana Tugas Mahasiswa**: Text input
- **Bentuk Penilaian**: Tags input (Quiz, Tugas, Presentasi)
- **Bobot (%)**: Numeric 0-100
- **Durasi (menit)**: Numeric (default: 150)

Tips:
- Minggu 8 = UTS (Evaluasi Tengah Semester)
- Minggu 17 = UAS (Evaluasi Akhir Semester)
- Collapsible untuk setiap pertemuan
- Item label menampilkan "Minggu X"

### Tab 4: 📊 Penilaian
**Komponen Penilaian** - Repeater dengan 5 item default:
- **Komponen**: Nama komponen (Kehadiran, Tugas, Quiz, UTS, UAS)
- **Bobot (%)**: Numeric 0-100 (pastikan total = 100%)
- **Deskripsi**: Penjelasan komponen

**Konversi Nilai**:
- Default: A: 85-100, AB: 80-84, B: 75-79, BC: 70-74, C: 65-69, D: 55-64, E: 0-54

### Tab 5: 📚 Referensi & Media
- **Referensi Utama**: Repeater simple (3 items default) - Format: Penulis. (Tahun). Judul. Penerbit.
- **Referensi Pendukung**: Repeater simple (2 items default)
- **Media Pembelajaran**: Textarea (Proyektor, Whiteboard, Video, PPT, Modul)
- **Software/Tools**: Textarea (VS Code, Python, Google Colab, GitHub)

### Tab 6: ✅ Status & Approval
- **Status**: Draft, Submitted, Reviewed, Approved, Rejected, Revision
- **Is Active**: Toggle (default: true)
- **Review**: Reviewer, Review Date, Review Notes
- **Approval**: Approved By, Approval Date, Approval Notes

### Fitur Form:
✅ **Tabs dengan Persist**: Tab tersimpan di URL query string
✅ **Repeater Collapsible**: Hemat ruang untuk data banyak
✅ **Tags Input**: Mudah input multiple items
✅ **Auto-increment**: Week number otomatis
✅ **Default Values**: Form sudah terisi dengan nilai default yang masuk akal
✅ **Validation**: Required fields, numeric validation, min/max values
✅ **Helper Text**: Setiap field ada petunjuk penggunaan
✅ **Sections**: Grouping field yang related
✅ **Data dari Database**: CPL, CPMK, Sub-CPMK, Indikator, Study Fields dipilih dari data yang sudah ada
✅ **CheckboxList & Multiple Select**: Interface yang user-friendly untuk memilih multiple items
✅ **Searchable**: Semua select field bisa dicari
✅ **Descriptions**: Setiap option menampilkan info tambahan (bobot, kategori, dll)
✅ **Reactive**: Sub-CPMK dan Indikator berubah otomatis sesuai pilihan sebelumnya

### Migration Tambahan:
```bash
# Field baru yang ditambahkan (migration 1):
- learning_materials (text) - Materi pembelajaran
- prerequisites (string) - Mata kuliah prasyarat
- performance_indicators (text) - Indikator kinerja

# Field baru yang ditambahkan (migration 2):
- faculty_id (foreignId, nullable) - FK ke faculties
- study_program_id (foreignId, nullable) - FK ke study_programs
```

Jalankan migration:
```bash
docker exec -it obe_php php artisan migrate
```

### Update RPS Seeder:
RPS seeder telah diperbarui untuk menyertakan:
- ✅ `faculty_id` dan `study_program_id` dari course relations
- ✅ `learning_materials` - Daftar materi pembelajaran
- ✅ `prerequisites` - Mata kuliah prasyarat
- ✅ `study_field_mapped` - Kode bahan kajian yang dipilih
- ✅ `performance_indicators` - Deskripsi indikator penilaian
- ✅ `assessment_plan` untuk semua RPS

Jalankan seeder:
```bash
docker exec -it obe_php php artisan db:seed --class=RpsSeeder
```

Data RPS yang ter-seed:
1. **Algoritma dan Pemrograman (ILK.102)**
   - Faculty: Fakultas Industri Kreatif (FIK)
   - Program Studi: Ilmu Komputer
   - 16 minggu rencana pembelajaran lengkap
   - Study fields: SF-001, SF-002, SF-003
   
2. **Kecerdasan Buatan (ILK.401)**
   - Faculty: Fakultas Industri Kreatif (FIK)
   - Program Studi: Ilmu Komputer
   - Materi Machine Learning
   - Study fields: SF-007, SF-008

### Data Integration:
Form RPS sekarang **terintegrasi penuh** dengan data yang sudah ada:

**Hierarki Pemilihan:**
1. **Fakultas** → `faculties` table
2. **Program Studi** → `study_programs` table (filter by faculty_id)
3. **Mata Kuliah** → `courses` table (filter by study_program_id)

**Data Pembelajaran:**
- **CPL**: Diambil dari tabel `program_learning_outcomes` (23 CPL)
- **CPMK**: Diambil dari tabel `course_learning_outcomes` sesuai mata kuliah yang dipilih
- **Sub-CPMK**: Diambil dari tabel `sub_course_learning_outcomes` sesuai mata kuliah
- **Indikator**: Diambil dari tabel `performance_indicators` sesuai Sub-CPMK yang dipilih
- **Bahan Kajian**: Diambil dari tabel `study_fields`
- **Kurikulum**: Diambil dari tabel `curriculums` (filter by study_program_id atau global)

**Form Features:**
- ✅ **Cascading Select**: Fakultas → Prodi → Mata Kuliah (reactive)
- ✅ **Auto-disable**: Field disabled jika parent belum dipilih
- ✅ **Auto-reset**: Child field otomatis reset saat parent berubah
- ✅ **Live Update**: Perubahan langsung update options di field berikutnya
- ✅ **Helper Text**: Petunjuk jelas untuk setiap field

**PDF Output** menampilkan data lengkap:
- ✅ Header dengan Universitas → Fakultas → Program Studi
- ✅ CPL dengan kode, kategori, dan deskripsi lengkap
- ✅ CPMK dengan kode, deskripsi, bobot, dan Bloom level
- ✅ Bahan Kajian dengan kode, nama, dan deskripsi
- ✅ Format tabel nested untuk menampilkan detail di PDF

## 10. Testing

### Test Download PDF
```bash
# Via browser (butuh login)
https://your-app-url/rps/1/download-pdf

# Via API (dengan auth token)
curl -H "Authorization: Bearer YOUR_TOKEN" \
     https://your-app-url/rps/1/download-pdf \
     --output test-rps.pdf
```

## 11. Troubleshooting

### Error: "Class 'Barryvdh\DomPDF\Facade\Pdf' not found"
```bash
composer require barryvdh/laravel-dompdf
php artisan config:clear
php artisan cache:clear
```

### Error: "View [pdf.rps] not found"
Pastikan file view ada di:
```
resources/views/pdf/rps.blade.php
```

### PDF tidak menampilkan data JSON dengan benar
Pastikan data JSON di-decode dengan benar di controller:
```php
$weeklyPlan = is_string($rps->weekly_plan) 
    ? json_decode($rps->weekly_plan, true) 
    : $rps->weekly_plan;
```

### PDF layout rusak
- Periksa CSS di view
- Pastikan tidak ada error PHP
- Cek log Laravel: `storage/logs/laravel.log`

## 12. Fitur Tambahan (Opsional)

### A. Bulk Download RPS
Tambahkan bulk action di RpsResource:

```php
Tables\Actions\BulkAction::make('downloadMultiplePdf')
    ->label('Download Selected as PDF')
    ->icon('heroicon-o-arrow-down-tray')
    ->action(function (Collection $records) {
        // Logic untuk zip multiple PDFs
    })
```

### B. Preview PDF sebelum Download
Tambahkan preview modal di view RPS page

### C. Email RPS PDF
Kirim RPS PDF via email ke dosen atau mahasiswa

### D. Versioning RPS
Track perubahan RPS dengan version control

### E. Approval Workflow
Implementasikan workflow persetujuan RPS:
- Draft → Submitted → Reviewed → Approved

## 13. Kesimpulan

Dengan implementasi ini, sistem OBE sudah memiliki:
✅ **CPL** lengkap sesuai SN-Dikti (23 CPL)
✅ **CPMK** untuk mata kuliah utama (25 CPMK)
✅ **Sub-CPMK** dengan detail per pertemuan
✅ **Indikator Kinerja** dengan rubrik penilaian
✅ **RPS** lengkap dengan rencana 16 minggu
✅ **Download PDF** dengan tampilan profesional

Sistem siap digunakan untuk pengelolaan kurikulum berbasis OBE!
