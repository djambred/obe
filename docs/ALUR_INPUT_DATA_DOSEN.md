# Panduan Alur Input Data OBE - Dari Dosen hingga Matrix Generation

## 📋 Daftar Isi
1. [Overview Proses](#overview-proses)
2. [Tahap 1: Setup Data Master](#tahap-1-setup-data-master)
3. [Tahap 2: Input RPS oleh Dosen](#tahap-2-input-rps-oleh-dosen)
4. [Tahap 3: Input Bobot Penilaian](#tahap-3-input-bobot-penilaian)
5. [Tahap 4: Mapping CPMK ke CPL](#tahap-4-mapping-cpmk-ke-cpl)
6. [Tahap 5: Validation & Export](#tahap-5-validation--export)

---

## Overview Proses

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ALUR INPUT DATA OBE SISTEM                       │
└─────────────────────────────────────────────────────────────────────┘

TAHAP 1: SETUP DATA MASTER (Admin/Kurikulum)
├── Universitas, Fakultas, Program Studi
├── Curriculum & Study Field
├── Courses (Mata Kuliah Master)
├── CPL (Program Learning Outcome)
└── CPMK (Course Learning Outcome)

           ↓

TAHAP 2: INPUT RPS (Dosen/Pengajar)
├── Pilih Mata Kuliah
├── Input CPL & CPMK
├── Input Rencana Pembelajaran (16 minggu)
├── Input Metode Pembelajaran
└── Generate PDF untuk verifikasi

           ↓

TAHAP 3: INPUT BOBOT PENILAIAN (Dosen)
├── Tambah Performance Indicators
├── Input 7 indikator:
│   ├── Tugas Besar (20%)
│   ├── UTS (30%)
│   ├── UAS (30%)
│   ├── Quiz (10%)
│   └── Tugas Individu (10%)
└── Pilih Grade Scale (Universitas/Fakultas/Prodi)

           ↓

TAHAP 4: MAPPING CPMK KE CPL (Dosen/Reviewer)
├── Tentukan CPMK mana yang berkontribusi ke CPL mana
├── View Matriks CPMK → CPL
└── Confirm mapping (1 = berkontribusi, 0 = tidak)

           ↓

TAHAP 5: VALIDATION & EXPORT
├── View Assessment Matrix (penilaian vs CPMK)
├── View CPMK Contribution Matrix (CPMK vs CPL)
├── Validate total bobot = 100%
├── Export ke Excel/PDF
└── Submit/Approve RPS
```

---

## Tahap 1: Setup Data Master

**Dilakukan oleh**: Admin/Kurikulum
**Menu**: Administration → [Master Data Items]

### 1.1 Universitas & Fakultas
```
Menu: Administration → Universitas
- Nama universitas (sudah ada: Esa Unggul)
- Logo, visi, misi, akreditasi
```

### 1.2 Program Studi
```
Menu: Administration → Program Studi
1. Create new: "Teknik Informatika"
   - Kode: TIK
   - Nama: Teknik Informatika
   - Jenjang: S1
   - Status: Active
   - Akreditasi: A

2. Set CPL (Capaian Pembelajaran Lulusan):
   Contoh untuk Teknik Informatika:
   - CPL-KK01: Menguasai prinsip-prinsip sistem komputer
   - CPL-KK02: Menguasai algoritma dan struktur data
   - CPL-KK03: Menguasai pemrograman
   ... (hingga 15 CPL)
```

### 1.3 Courses (Mata Kuliah Master)
```
Menu: Academic Management → Courses (atau 📚 Kurikulum → Courses)
1. Create new: "Algoritma dan Pemrograman"
   - Kode: ILK.102
   - Nama: Algoritma dan Pemrograman
   - Kredit: 3
   - Semester: 1
   - Tipe: Theory (Teori)
   - Status: Active

2. Set CPMK untuk mata kuliah:
   - CPMK-01: Memahami konsep algoritma
   - CPMK-02: Menerapkan struktur kontrol
   - CPMK-03: Implementasi array dan function
   - CPMK-04: Debugging dan testing
   - CPMK-05: Dokumentasi code
```

**OUTPUT**: Database sudah memiliki struktur master data

---

## Tahap 2: Input RPS oleh Dosen

**Dilakukan oleh**: Dosen/Pengajar
**Menu**: 📝 RPS → Rencana Pembelajaran Semester
**Status**: Draft → Submitted → Approved

### 2.1 Buka Form Input RPS
```
Langkah:
1. Login sebagai Dosen
2. Navigasi: 📝 RPS → Rencana Pembelajaran Semester
3. Click "Create" atau "Add RPS"
```

### 2.2 Tab 1: Informasi Dasar
```
Form yang diisi:
┌─────────────────────────────────────┐
│ Informasi RPS                       │
├─────────────────────────────────────┤
│ Mata Kuliah: [Pilih Algoritma...]   │
│ Kurikulum: [Otomatis, 2024]         │
│ Konsentrasi: [Pilih jika ada]       │
│ Semester: [1]                       │
│ Tahun Akademik: [2024/2025]         │
│ Minggu Efektif: [16] (default)      │
└─────────────────────────────────────┘
```

### 2.3 Tab 2: Capaian Pembelajaran
```
2.3.1 Pilih CPL (Checkbox List)
Contoh: Periksa box untuk CPL yang relevan:
☑ CPL-KK02: Menguasai algoritma dan struktur data
☑ CPL-KK03: Menguasai pemrograman
☐ CPL-P01: Kepribadian

2.3.2 Pilih CPMK (Checkbox List dengan search)
Setiap CPMK menampilkan:
[CPMK-01: Memahami konsep algoritma | Bobot: 20% | Bloom: C3]

Periksa semua 5 CPMK:
☑ CPMK-01: Memahami konsep algoritma | Bobot: 20% | Bloom: C3
☑ CPMK-02: Menerapkan struktur kontrol | Bobot: 20% | Bloom: C3
☑ CPMK-03: Implementasi array & function | Bobot: 25% | Bloom: C3
☑ CPMK-04: Debugging & testing | Bobot: 25% | Bloom: C4
☑ CPMK-05: Dokumentasi code | Bobot: 10% | Bloom: C2

2.3.3 Pilih Bahan Kajian (Study Field)
☑ Fundamental Programming
☑ Data Structures
```

### 2.4 Tab 3: Rencana Pembelajaran (16 Minggu)
```
Repeater Form dengan fields:
┌──────┬──────────┬────────────────────┬──────────────────┐
│ Week │ Topic    │ Learning Outcomes   │ Teaching Method  │
├──────┼──────────┼────────────────────┼──────────────────┤
│ 1    │ Intro    │ CPMK-01, CPMK-02   │ Lecture, Demo    │
│ 2    │ Algorithm│ CPMK-01, CPMK-02   │ Lecture, Lab     │
│ 3    │ Control  │ CPMK-02, CPMK-03   │ Lecture, Lab     │
│ ...  │ ...      │ ...                │ ...              │
│ 16   │ Review   │ All CPMK           │ Discussion       │
└──────┴──────────┴────────────────────┴──────────────────┘
```

### 2.5 Tab 4: Rencana Penilaian
```
Informasi awal tentang assessment methods:
- Tugas Besar: 20%
- UTS: 30%
- UAS: 30%
- Quiz: 10%
- Tugas Individu: 10%

Note: Detail bobot per CPMK diisi di Performance Indicators (Tahap 3)
```

### 2.6 Tab 5: Referensi & Media
```
□ Referensi Utama:
  - Cormen, T. H. "Introduction to Algorithms"
  - Goodrich, M. T. "Data Structures and Algorithms"

□ Buku Pendukung:
  - Weiss, M. A. "Data Structures and Algorithm Analysis"

□ Journal/Paper:
  - IEEE Xplore articles

□ Software/Tools:
  - Visual Studio Code
  - Python 3.9+
  - Git & GitHub

□ Media Pembelajaran:
  - Lecture slides
  - Video recordings
  - Live coding demonstrations
```

### 2.7 Tab 6: Status & Persetujuan
```
Status: Draft
Koordinator: [Select nama dosen koordinator]
Kepala Program Studi: [Otomatis, untuk approval]

Actions:
- Save (Draft)
- Preview PDF
- Submit to Review
- Generate QR Code
```

### 2.8 Hasil Output Tab 2-7
```
Setelah SAVE, sistem akan:
✓ Validate input (tidak ada CPMK duplikat)
✓ Save ke database
✓ Generate unique RPS ID (RPS-ILK.102-2024-001)
✓ Hitung total minggu efektif
```

---

## Tahap 3: Input Bobot Penilaian

**Dilakukan oleh**: Dosen (same person as RPS)
**Menu**: 🎯 Learning Outcomes → Performance Indicators
**Waktu**: Bersamaan atau setelah RPS disubmit

### 3.1 Buka Performance Indicators
```
Menu: 🎯 Learning Outcomes → Performance Indicators
Status: List view semua indikator untuk semua mata kuliah
```

### 3.2 Create Performance Indicator
```
Workflow:
1. Click "+ Create Performance Indicator"
2. Pilih mata kuliah yang sama dengan RPS (ILK.102)
3. Pilih CPMK yang akan diukur
4. Isi formulir
```

### 3.3 Form Section 1: Informasi Dasar
```
┌────────────────────────────────────┐
│ Informasi Dasar                    │
├────────────────────────────────────┤
│ CPMK: [CPMK-01]                    │
│ Sub-CPMK: [Kosongkan atau pilih]   │
│ Kode: [TB]                         │
│ Urutan: [1]                        │
└────────────────────────────────────┘
```

### 3.4 Form Section 2: Deskripsi & Kriteria
```
┌────────────────────────────────────┐
│ Deskripsi & Kriteria               │
├────────────────────────────────────┤
│ Deskripsi Singkat:                 │
│ "Tugas Besar: Project komprehensif"│
│                                    │
│ Kriteria Penilaian:                │
│ "Proyek memenuhi:                  │
│  1. Analisis masalah yang tepat    │
│  2. Desain solusi yang efisien     │
│  3. Implementasi yang bersih       │
│  4. Dokumentasi lengkap"           │
└────────────────────────────────────┘
```

### 3.5 Form Section 3: Rubrik Penilaian OBE
```
┌────────────────────────────────────┐
│ Rubrik Penilaian (Default)         │
├────────────────────────────────────┤
│ - Sangat Baik (86-100):            │
│   Pemahaman sangat mendalam        │
│ - Baik (71-85):                    │
│   Pemahaman baik                   │
│ - Cukup (56-70):                   │
│   Pemahaman cukup                  │
│ - Kurang (0-55):                   │
│   Pemahaman tidak memadai          │
└────────────────────────────────────┘
(Opsi: Edit jika perlu customize)
```

### 3.6 Form Section 4: Penilaian & Bobot
```
┌────────────────────────────────────┐
│ Penilaian & Bobot                  │
├────────────────────────────────────┤
│ Jenis Penilaian: [Proyek]          │
│ Bobot (%): [20.00]                 │
│ Nilai Minimal Kelulusan: [56.00]   │
│ Status Aktif: [✓ Yes]              │
└────────────────────────────────────┘
```

### 3.7 Form Section 5: Skala Penilaian
```
┌────────────────────────────────────┐
│ Skala Penilaian (Grade Scale)      │
├────────────────────────────────────┤
│ Level Skala Nilai:                 │
│ [✓ Universitas] ☐ Fakultas ☐ Prodi│
│                                    │
│ Tabel Konversi Nilai (Grade):      │
│ A: 86-100                          │
│ B: 71-85                           │
│ C: 56-70                           │
│ D: 41-55                           │
│ E: 0-40                            │
│ (Default: Universitas standard)    │
└────────────────────────────────────┘
```

### 3.8 Create Semua 7 Indicators
Ulangi langkah 3.2-3.7 untuk:
```
1. TB (Tugas Besar)    - Jenis: Proyek     - Bobot: 20%
2. UTS                 - Jenis: Ujian Tulis - Bobot: 30%
3. UAS                 - Jenis: Ujian Tulis - Bobot: 30%
4. Q1 (Quiz 1)         - Jenis: Quiz       - Bobot: 5%
5. Q2 (Quiz 2)         - Jenis: Quiz       - Bobot: 5%
6. T1 (Tugas 1)        - Jenis: Tugas Ind. - Bobot: 5%
7. T2 (Tugas 2)        - Jenis: Tugas Ind. - Bobot: 5%

Total: 100% ✓
```

### 3.9 Distribusi ke CPMK
```
Saat create, system akan auto-distribute atau dosen pilih manual:

Contoh mapping:
TB  → CPMK-01 (20%)
UTS → CPMK-02 (30%)
UAS → CPMK-03 (30%)
Q1  → CPMK-04 (5%)
Q2  → CPMK-05 (5%)
T1  → CPMK-05 (5%)
T2  → CPMK-01 (5%)

TOTAL per CPMK:
CPMK-01: 25% (TB + T2)
CPMK-02: 30% (UTS)
CPMK-03: 30% (UAS)
CPMK-04: 5%  (Q1)
CPMK-05: 10% (Q2 + T1)
```

**OUTPUT**: Performance Indicators tersimpan dengan validasi bobot total = 100%

---

## Tahap 4: Mapping CPMK ke CPL

**Dilakukan oleh**: Dosen/Reviewer
**Menu**: 🎯 Learning Outcomes → Matriks CPMK → CPL
**Tujuan**: Tentukan kontribusi CPMK ke CPL

### 4.1 Buka Halaman Matriks CPMK → CPL
```
1. Login sebagai Dosen
2. Navigasi: 🎯 Learning Outcomes → Matriks CPMK → CPL
3. Atau: Academic Management → Matriks CPMK → CPL
```

### 4.2 Filter & Load Matrix
```
Form Filter:
┌──────────────────────────────────┐
│ Fakultas: [Teknik Informatika]   │
│ Program Studi: [Teknik Inf...]   │
│ Mata Kuliah: [ILK.102]           │
│                                  │
│ [Load Matriks] button            │
└──────────────────────────────────┘
```

### 4.3 Lihat Hasil Auto-Mapping
```
System sudah pre-populate berdasarkan seeder:

         CPL-1 CPL-2 CPL-3 ... CPL-9 CPL-13 CPL-14 CPL-15
CPMK-01   0     0     0         1      0      0      0
CPMK-02   0     0     0         1      0      0      0
CPMK-03   0     0     0         0      0      1      0
CPMK-04   0     0     0         0      0      0      1
CPMK-05   0     0     0         0      0      0      1

Legend:
1 = CPMK berkontribusi terhadap CPL
0 = CPMK tidak berkontribusi
```

### 4.4 Edit Mapping Jika Perlu
```
Opsi yang akan ditambahkan:
- Click cell untuk toggle 0↔1
- Edit multiple cells
- Bulk assign
- Validate bahwa setiap CPMK contribute ke minimal 1 CPL

Contoh edit:
CPMK-01 yang tadinya hanya ke CPL-9
Ditambah juga ke CPL-13:
CPMK-01: 1 (CPL-9) + 1 (CPL-13) = berkontribusi ke 2 CPL
```

### 4.5 Save & Confirm
```
Setelah edit:
- Automatic save (atau click Save button)
- System validates
- Display confirmation: "Mapping saved successfully"
- Show statistics:
  * Total CPMK: 5
  * Total CPL: 15
  * Total Kontribusi: 8
```

**OUTPUT**: CPMK-CPL relationship tersimpan di pivot table

---

## Tahap 5: Validation & Export

**Dilakukan oleh**: Dosen/Reviewer/Admin
**Menu**: Academic Management
**Tujuan**: Verifikasi data & generate matrix final

### 5.1 View Assessment Matrix
```
Menu: Academic Management → Assessment Matrix
1. Select Fakultas → Program Studi → Mata Kuliah
2. Click [Load Matrix]

OUTPUT:
         T1  T2  UTS UAS Q1  Q2  T3  T4
CPMK-01  5   5   -   -   -   -   -   -    (Total: 10%)
CPMK-02  -   -   10  -   -   -   -   -    (Total: 10%)
CPMK-03  -   -   -   10  -   -   -   -    (Total: 10%)
CPMK-04  -   -   -   -   5   -   -   -    (Total: 5%)
CPMK-05  -   -   -   -   -   5   5   -    (Total: 10%)

TOTAL:   10  10  10  10  5   5   -   -    (Total: 100%)

Validasi:
✓ Total per CPMK seimbang
✓ Total assessment = 100%
✓ Setiap assessment type covered
```

### 5.2 View CPMK Contribution Matrix
```
Menu: Academic Management → Matriks CPMK → CPL
1. Select Fakultas → Program Studi → Mata Kuliah
2. Matrix sudah ada dari Tahap 4

OUTPUT: Kontribusi CPMK ke CPL (seperti gambar reference)
```

### 5.3 Validate All Data
```
Checklist Dosen sebelum submit:

□ RPS Lengkap:
  □ CPL & CPMK dipilih
  □ 16 minggu pembelajaran terisi
  □ Metode pembelajaran jelas
  □ Referensi mencukupi

□ Performance Indicators:
  □ 7 indicators created
  □ Total bobot = 100%
  □ Grade scale defined
  □ Semua CPMK covered

□ CPMK-CPL Mapping:
  □ Setiap CPMK map ke CPL
  □ Kontribusi meaningful
  □ Tidak ada orphan CPMK

□ Matrix Validation:
  □ Assessment Matrix valid
  □ CPMK Contribution Matrix complete
```

### 5.4 Export to Excel
```
Menu: Assessment Matrix → [Export to Excel]

File: RPS_ILK102_2024_Matrix.xlsx
Contains:
  - Sheet 1: Assessment Matrix
  - Sheet 2: CPMK Contribution Matrix
  - Sheet 3: Performance Indicators
  - Sheet 4: Statistics
```

### 5.5 Export to PDF
```
Menu: Assessment Matrix → [Export to PDF]

File: RPS_ILK102_2024_Matrix.pdf
Layout:
  - Header: Course Info
  - Assessment Matrix (profesional format)
  - CPMK Contribution Matrix
  - Footer: Generated date, QR Code
```

### 5.6 Submit RPS for Approval
```
Menu: 📝 RPS → Rencana Pembelajaran Semester
1. Find RPS yang statusnya Draft
2. Click [Submit for Review]
3. Pilih Koordinator (auto-suggest)
4. Add notes: "RPS ready for review"
5. Click [Submit]

Status berubah: Draft → Submitted (Pending Review)
```

### 5.7 Approval Workflow
```
Coordinator/Head of Program akan menerima:
- Email notification
- Pending task di dashboard

Mereka dapat:
□ Review RPS (Tab-by-tab)
□ Check assessment matrix
□ Approve atau Request Revision

Approval flow:
Draft → Submitted → Approved (Published)
         ↓
       Revision Needed (back to Draft)
```

**OUTPUT**: RPS Published & accessible for students

---

## 📊 Data Flow Summary

```
┌──────────────────────────────────────────────────────────┐
│                    DATABASE TABLES                       │
└──────────────────────────────────────────────────────────┘

Input tahap 2 (RPS):
├── rps (Master RPS document)
├── rps_learning_plans (16 minggu)
└── rps_assessment_plans (metode penilaian overview)

Input tahap 3 (Performance Indicators):
├── performance_indicators (7 indikator: TB, UTS, UAS, Q, T)
└── Relationships ke CPMK via course_learning_outcome_id

Input tahap 4 (Mapping):
├── course_learning_outcome_program_learning_outcome (Pivot)
│   └── Links CPMK → CPL
└── Stored as: 1 (berkontribusi) or 0 (tidak)

Query Stages:
1. Performance Indicators + course_learning_outcomes
   → Assessment Matrix (Assessment Type × CPMK)

2. course_learning_outcome_program_learning_outcome
   → CPMK Contribution Matrix (CPMK × CPL)

3. Combine 1 & 2
   → Full OBE Matrix (Assessment × CPMK × CPL)
```

---

## 🔄 Timeline Contoh

```
Semester Ganjil 2024/2025:

Minggu 1 (15 Nov):
- Admin setup courses & CPMK master data
- Seeder auto-populate sample data

Minggu 2-3 (22-29 Nov):
- Dosen input RPS (Tab 1-6)
- Dosen create Performance Indicators (7 items)

Minggu 4 (6 Des):
- Dosen review CPMK-CPL mapping
- Edit jika perlu

Minggu 5 (13 Des):
- Submit RPS untuk review
- Generate final matrix

Minggu 6 (20 Des):
- Approval by Coordinator
- RPS Published

Semester dimulai (6 Jan):
- RPS sudah live untuk mahasiswa
- Assessment matrix ready untuk guidance
```

---

## 🎯 Checklist untuk Dosen

### Sebelum mulai input:
- [ ] Sudah punya course ID (cek dengan admin)
- [ ] CPMK list sudah disetujui oleh program
- [ ] CPL sudah defined oleh program studi

### Tahap 2 Input RPS:
- [ ] CPL yang relevan diceklis semua
- [ ] CPMK yang diajarkan diceklis semua
- [ ] 16 minggu pembelajaran terisi lengkap
- [ ] Setiap minggu ada CPMK link
- [ ] Referensi minimal 3 sumber
- [ ] Media pembelajaran jelas
- [ ] PDF preview sudah dilihat

### Tahap 3 Input Performance Indicators:
- [ ] 7 indikator created (TB, UTS, UAS, Q1, Q2, T1, T2)
- [ ] Total bobot 100%
- [ ] Setiap indikator map ke CPMK
- [ ] Grade scale A-E defined
- [ ] Passing grade 56% for all

### Tahap 4 CPMK-CPL Mapping:
- [ ] Setiap CPMK berkontribusi ke minimal 1 CPL
- [ ] Mapping meaningful (bukan random)
- [ ] Matrix tidak ada orphan CPMK
- [ ] Matrix sudah divalidasi

### Sebelum submit:
- [ ] Assessment Matrix total = 100%
- [ ] CPMK Contribution Matrix complete
- [ ] Export both matrices to verify
- [ ] Koordinator sudah diinformasikan

---

## 📞 Troubleshooting

### Error: "CPMK duplikasi di RPS"
**Solusi**: Tab 2 Capaian Pembelajaran - uncheck yang duplikat

### Error: "Total bobot ≠ 100%"
**Solusi**: Tab 4 Bobot Penilaian atau Performance Indicators - check setiap item

### CPMK tidak muncul di Performance Indicators
**Solusi**: Pastikan CPMK sudah diceklis di Tab 2 RPS

### Tidak bisa submit RPS
**Solusi**: Pastikan semua field Tab 1 & 2 terisi, tidak ada warning

### CPMK-CPL matrix kosong
**Solusi**: Run seeder `CpmkCplMappingSeeder` atau manual mapping

---

## 📚 Referensi Sistem

- **OBE Framework**: Outcome-Based Education
- **Learning Outcomes**: CPL (Lulusan), CPMK (Mata Kuliah), Sub-CPMK
- **Assessment Types**: TB (Proyek), UTS, UAS, Quiz, Tugas
- **Grade Scale**: A (86-100), B (71-85), C (56-70), D (41-55), E (0-40)
- **Matrix Types**:
  - Assessment Matrix: Assessment Type × CPMK
  - CPMK Matrix: CPMK × CPL
  - Combined Matrix: Assessment × CPMK × CPL

