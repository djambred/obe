# Data Flow & Requirements - Alur Data OBE System

## 📊 Data Dependencies Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                       MASTER DATA                               │
│                   (Setup by Admin/Kurikulum)                    │
└─────────────────────────────────────────────────────────────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│  University  │────▶│   Faculties  │────▶│ Study Progs  │
│              │     │              │     │              │
└──────────────┘     └──────────────┘     └──────────────┘
                                                  │
                                                  ├─────────────┐
                                                  ▼             ▼
                                           ┌──────────┐   ┌─────────┐
                                           │   CPL    │   │Curriculum│
                                           │ (15 CPL) │   │ (2024)  │
                                           └──────────┘   └─────────┘
                                                                │
                                                                ▼
                                           ┌──────────────────────┐
                                           │   Courses            │
                                           │ (ILK.102, ILK.201,..)│
                                           └──────────────────────┘
                                                    │
                                                    ▼
                                           ┌──────────────────────┐
                                           │   CPMK (per Course)  │
                                           │ (CPMK-01 to CPMK-05) │
                                           └──────────────────────┘

                              ↓↓↓ DOSEN INPUT STARTS HERE ↓↓↓
                              
        ┌─────────────────────────────────────────────────────┐
        │           RPS INPUT (Tahap 2)                       │
        │   Dosen memilih Course → CPMK → CPL yang relevan   │
        └─────────────────────────────────────────────────────┘
                    ↓
        ┌─────────────────────────────────────────────────────┐
        │   PERFORMANCE INDICATORS INPUT (Tahap 3)            │
        │   Dosen buat 7 indicators (TB,UTS,UAS,Q,T)          │
        │   Mapping ke CPMK + Grade Scale                     │
        └─────────────────────────────────────────────────────┘
                    ↓
        ┌─────────────────────────────────────────────────────┐
        │   CPMK-CPL MAPPING (Tahap 4)                        │
        │   Tentukan CPMK → CPL contribution (1 or 0)        │
        └─────────────────────────────────────────────────────┘
                    ↓
        ┌─────────────────────────────────────────────────────┐
        │   MATRIX GENERATION (Tahap 5)                       │
        │   Assessment Matrix (Assessment × CPMK)             │
        │   CPMK Matrix (CPMK × CPL)                          │
        │   Export Excel/PDF                                  │
        └─────────────────────────────────────────────────────┘
                    ↓
        ┌─────────────────────────────────────────────────────┐
        │   RPS SUBMISSION & APPROVAL                         │
        │   Dosen submit → Coordinator approve → Published    │
        └─────────────────────────────────────────────────────┘
```

---

## 🔄 Data Requirements per Tahap

### TAHAP 0: Prerequisite (Setup oleh Admin)
```
✓ MUST EXIST SEBELUM DOSEN INPUT:
  ├─ University record
  ├─ Faculty(ies) - minimum 1
  ├─ Study Program(s) - minimum 1
  ├─ Curriculum - minimum 1 per Study Program
  ├─ Course(s) - Master course list
  ├─ CPL (Program Learning Outcomes) - 15 CPL per Program
  └─ CPMK per Course - minimum 3 CPMK per Course

✓ Data Volume Check:
  • University: 1
  • Faculties: 1-3
  • Study Programs: 2-5
  • Courses: 10-50
  • CPL: 15 per program
  • CPMK: 3-8 per course
```

### TAHAP 1: RPS Input Requirements
```
REQUIRED FIELDS:
┌────────────────────────────────┐
│ Informasi Dasar (Tab 1)        │
├────────────────────────────────┤
│ Course ID: [Must exist]        │
│ Curriculum ID: [Must exist]    │
│ Semester: [1-8]                │
│ Year: [Valid year]             │
│ Weeks: [16 default]            │
│ Status: [Draft]                │
└────────────────────────────────┘

SELECTION REQUIREMENTS (Tab 2):
├─ CPL Selection: Minimum 2 CPL
├─ CPMK Selection: Minimum 3 CPMK, All must exist
├─ Study Field: Minimum 1
└─ No duplicates allowed

LEARNING PLAN (Tab 3):
├─ 16 rows (1 per week)
├─ Each row MUST have:
│  ├─ Week number
│  ├─ Topic
│  ├─ Learning outcomes (CPMK links)
│  └─ Teaching method
└─ Total weeks must match Minggu Efektif

ASSESSMENT OVERVIEW (Tab 4):
├─ Display only (informational)
└─ Detail bobot di Performance Indicators

REFERENCES (Tab 5):
├─ Primary references: Min 3
├─ Supporting books: Min 2
├─ Tools/Software: Listed
└─ Media: Listed

APPROVAL (Tab 6):
├─ Coordinator: [Must select]
└─ Status: Draft (ready to submit)

OUTPUT:
├─ RPS ID: Auto-generated (RPS-{CourseCode}-{Year}-{Seq})
├─ Save to: rps table
├─ Related rows: rps_learning_plans (16 rows)
└─ Validation: No errors, Ready for PI input
```

### TAHAP 2: Performance Indicator Requirements
```
REQUIREMENT: 7 INDICATORS TOTAL (100% BOBOT)

Indicator Distribution:
│ Kode │ Nama          │ Jenis Penilaian  │ Bobot │ CPMK Link │
├──────┼───────────────┼──────────────────┼───────┼───────────┤
│ TB   │ Tugas Besar   │ Proyek           │ 20%   │ 1 CPMK    │
│ UTS  │ UTS           │ Ujian Tulis      │ 30%   │ 1 CPMK    │
│ UAS  │ UAS           │ Ujian Tulis      │ 30%   │ 1 CPMK    │
│ Q1   │ Quiz 1        │ Quiz             │ 5%    │ 1 CPMK    │
│ Q2   │ Quiz 2        │ Quiz             │ 5%    │ 1 CPMK    │
│ T1   │ Tugas 1       │ Tugas Individu   │ 5%    │ 1 CPMK    │
│ T2   │ Tugas 2       │ Tugas Individu   │ 5%    │ 1 CPMK    │
└──────┴───────────────┴──────────────────┴───────┴───────────┘

FIELDS PER INDICATOR:
├─ Code: [TB, UTS, UAS, Q1, Q2, T1, T2]
├─ Course Learning Outcome: [Link ke 1 CPMK dari RPS Tab 2]
├─ Description: [Text ≤500 chars]
├─ Criteria: [Text ≤1000 chars]
├─ Rubric: [4-level rubric, default provided]
├─ Weight: [Sum must = 100%]
├─ Assessment Type: [Must be from enum list]
├─ Passing Grade: [56.00 default]
├─ Grading Scale Level: [Universitas default]
├─ Grade Scale Text: [A: 86-100, B: 71-85, ...]
├─ Order: [1-7]
└─ Is Active: [TRUE]

VALIDATION RULES:
├─ ✓ Total weight = 100%
├─ ✓ No duplicate codes
├─ ✓ All CPMK must be from RPS (Tab 2)
├─ ✓ Assessment type must be valid
├─ ✓ Passing grade ≥ 0 and ≤ 100
├─ ✓ Grade scale format: "Grade: Min-Max"
└─ ✓ Min 3 entries in grade scale

OUTPUT:
├─ Save to: performance_indicators table
├─ Count: 7 rows per course/RPS
├─ Ready for: CPMK-CPL mapping & Assessment Matrix
└─ Validation: Total bobot = 100% ✓
```

### TAHAP 3: CPMK-CPL Mapping Requirements
```
SOURCE DATA NEEDED:
├─ CPMK list: [From RPS Tab 2 selection]
├─ CPL list: [From Study Program master]
└─ Existing mapping: [From seeder or previous input]

MAPPING TABLE (Pivot):
┌──────────┬──────────┬──────────┬──────────┐
│ CPMK ID  │ CPL ID   │ Created  │ Updated  │
├──────────┼──────────┼──────────┼──────────┤
│ CPMK-01  │ CPL-09   │ Datetime │ Datetime │
│ CPMK-01  │ CPL-13   │ Datetime │ Datetime │
│ CPMK-02  │ CPL-09   │ Datetime │ Datetime │
│ ... etc ...                               │
└──────────┴──────────┴──────────┴──────────┘

REQUIREMENT:
├─ Each CPMK ≥ 1 CPL link (no orphans)
├─ Each CPL link meaningful (pedagogically sound)
├─ No impossible combinations
└─ Documented & traceable

OUTPUT:
├─ Save to: course_learning_outcome_program_learning_outcome (pivot)
├─ Stored as: course_learning_outcome_id + program_learning_outcome_id
├─ Unique constraint: (cpmk_id, cpl_id)
└─ Ready for: Matrix visualization & reporting
```

### TAHAP 4: Matrix Generation Requirements
```
ASSESSMENT MATRIX INPUT:
├─ Performance Indicators: ✓ 7 items, total 100%
├─ CPMK list: ✓ From RPS Tab 2
└─ Assessment types: ✓ Categorized & counted

CPMK CONTRIBUTION MATRIX INPUT:
├─ CPMK-CPL mapping: ✓ From Tahap 3
├─ Each CPMK: ✓ ≥ 1 CPL link
└─ Meaningful distribution: ✓ Reviewed

MATRIX CALCULATIONS:
1. Assessment Matrix:
   └─ Sum weights by CPMK (assessment type) → distribution matrix
   
2. CPMK Contribution Matrix:
   └─ Count/visualize CPMK → CPL relationships (1 or 0)
   
3. Combined Analysis:
   └─ How each assessment type contributes to each CPMK
   └─ How each CPMK contributes to CPL
   └─ Overall program learning achievement potential

OUTPUT FILES:
├─ Excel: RPS_{CourseCode}_{Year}_Matrix.xlsx
│  ├─ Sheet 1: Assessment Matrix
│  ├─ Sheet 2: CPMK Contribution Matrix
│  ├─ Sheet 3: Performance Indicators (detail)
│  └─ Sheet 4: Statistics & Summary
│
└─ PDF: RPS_{CourseCode}_{Year}_Matrix.pdf
   ├─ Professional format
   ├─ Color-coded matrix
   ├─ Statistics cards
   └─ QR Code for verification

VALIDATION CHECKLIST:
├─ Total assessment weights = 100% ✓
├─ All CPMK covered in assessment ✓
├─ All CPMK linked to CPL ✓
├─ No orphan elements ✓
├─ Matrix dimensions correct ✓
└─ Export files generated ✓
```

---

## 📈 Data Quality Metrics

### Per RPS (Course):
```
✓ Completeness:
  ├─ All 6 tabs filled: 100%
  ├─ CPMK coverage: ≥90% of defined CPMK
  ├─ CPL coverage: ≥2 CPL per course
  └─ References: ≥5 total sources

✓ Validity:
  ├─ No null required fields
  ├─ All selections from valid lists
  ├─ No duplicate selections
  └─ All references are real/accessible

✓ Consistency:
  ├─ CPMK in Tab 2 = CPMK in Tab 3 (Weeks)
  ├─ CPMK in Tab 2 = CPMK in PI
  ├─ Assessment types match
  └─ No conflicting information
```

### Per Performance Indicator Set:
```
✓ Coverage:
  ├─ Exactly 7 indicators
  ├─ All major assessment types covered
  └─ Weight distribution balanced

✓ Completeness:
  ├─ 100% field fill rate
  ├─ Descriptive text adequate (>50 chars)
  ├─ Rubric clearly defined
  └─ Grade scale complete

✓ Validation:
  ├─ Total weight = 100%
  ├─ No duplicate codes
  ├─ All CPMK valid & from RPS
  └─ All assessment types valid
```

### Per CPMK-CPL Mapping:
```
✓ Coverage:
  ├─ Every CPMK ≥ 1 CPL link
  ├─ Meaningful distribution
  └─ No arbitrary mappings

✓ Validation:
  ├─ No orphan CPMK
  ├─ No impossible CPL links
  └─ Mapping reviewed & approved
```

---

## 🔍 Data Integrity Checks

### Before RPS Submission:
```
□ All 6 tabs completed
□ No red warning messages
□ CPMK consistency check passed
□ PDF preview generated successfully
□ Koordinator name exists in system
□ Course still active
□ Curriculum still valid
```

### Before PI Submission:
```
□ 7 indicators created (TB, UTS, UAS, Q1, Q2, T1, T2)
□ Total weight = 100.00%
□ All CPMK from RPS selection
□ Assessment types valid & categorized
□ Grade scales defined
□ No duplicate codes
```

### Before Mapping Submission:
```
□ All CPMK have ≥ 1 CPL link
□ No orphan CPMK
□ Mapping meaningful (pedagogically sound)
□ Total contribution > 0
□ Matrix visualizes correctly
```

### Before Export:
```
□ Assessment Matrix: Valid, total 100%
□ CPMK Matrix: All CPMK linked
□ Both matrices consistent
□ PDF/Excel generated without error
□ File size reasonable (not 0 bytes)
□ Can open in Excel/PDF reader
```

---

## 📊 Sample Data Volume

For testing/demo:
```
Database Size (Full Setup):
├─ Universities: 1
├─ Faculties: 3
├─ Study Programs: 5
├─ Curricula: 5
├─ Courses: 50
├─ CPL: 75 (15 per program)
├─ CPMK: 200 (4 per course avg)
├─ RPS (single cycle): 50
├─ Performance Indicators: 350 (7 per RPS)
├─ CPMK-CPL links: 400 (8 per CPMK avg)
└─ Total Records: ~1100

Database Size (Peak Load):
├─ All tables above × 3 (3 years history)
├─ Plus deleted/archived
├─ Total ~3500 records
└─ Performance: Still acceptable (<5 sec query)
```

---

## 🔐 Data Ownership & Permissions

```
MASTER DATA (Admin Only):
├─ Universities
├─ Faculties
├─ Study Programs
├─ Curricula
├─ Courses
├─ CPL
└─ CPMK

RPS (Dosen Owner + Coordinator Review):
├─ Created by: Dosen (Lecturer)
├─ Reviewed by: Koordinator/Kaprodi
├─ Can edit: Dosen (Draft stage)
├─ Can approve: Koordinator/Kaprodi
└─ Can view: Self, Koordinator, Admin

PERFORMANCE INDICATORS (Dosen Owner):
├─ Created by: Dosen
├─ Can edit: Dosen
├─ Can delete: Dosen (if RPS Draft)
└─ Can view: Self, Koordinator, Admin

CPMK-CPL MAPPING (Dosen + Academic):
├─ Created by: System (seeder) or Dosen
├─ Can edit: Dosen, Academic Staff
├─ Reviewed by: Kaprodi
└─ Can view: All authenticated users

MATRIX REPORTS (View Only):
├─ Generated from: PI + CPMK-CPL data
├─ Can view: Self, Koordinator, Admin, Academic
├─ Can export: Everyone
└─ Audit trail: All downloads logged
```
