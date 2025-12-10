# Quick Reference - Menu & Form Navigation

## 🎯 Menu Map untuk Dosen

```
SIDEBAR NAVIGATION
├── 📚 Kurikulum
│   ├── Courses (Master data - lihat saja)
│   ├── CPL (Master data - lihat saja)
│   ├── CPMK (Master data - lihat saja)
│   └── Study Fields (Master data - lihat saja)
│
├── 📝 RPS
│   └── Rencana Pembelajaran Semester
│       ├── Create RPS baru
│       ├── Edit RPS (draft)
│       ├── View RPS (published)
│       └── Submit untuk review
│
├── 🎯 Learning Outcomes
│   ├── Performance Indicators
│   │   ├── Create Indicator (7 items)
│   │   ├── Edit Indicator
│   │   └── View Assessment preview
│   │
│   └── Matriks CPMK → CPL
│       ├── Load matrix
│       ├── Edit mapping (1/0)
│       └── Export matrix
│
└── Academic Management
    ├── Curriculum Mapping (Preview)
    ├── Assessment Matrix
    │   ├── Filter: Fakultas → Prodi → Course
    │   ├── View matrix
    │   ├── Validate
    │   └── Export (Excel/PDF)
    │
    └── Matriks CPMK → CPL
        ├── Filter: Fakultas → Prodi → Course
        ├── View contribution
        └── Export (Excel/PDF)
```

---

## 📋 Form Checklist (Copy-Paste untuk Dosen)

### RPS Input Checklist
```
TAHAP 2: RPS INPUT
==================

☐ TAB 1: Informasi Dasar
  ☐ Mata Kuliah: [pilih]
  ☐ Kurikulum: [auto]
  ☐ Konsentrasi: [jika ada]
  ☐ Semester: [pilih]
  ☐ Tahun Akademik: [auto/pilih]
  ☐ Minggu Efektif: [16 default]

☐ TAB 2: Capaian Pembelajaran
  ☐ Pilih CPL (minimum 2)
  ☐ Pilih CPMK (semua yang diajarkan)
  ☐ Pilih Bahan Kajian (Study Field)

☐ TAB 3: Rencana Pembelajaran
  ☐ Minggu 1-16 sudah terisi
  ☐ Setiap minggu ada topic, outcomes, methods
  ☐ CPMK teralokasi merata

☐ TAB 4: Rencana Penilaian
  ☐ Informasi assessment methods sudah terlihat
  ☐ Note: Detail bobot di Performance Indicators

☐ TAB 5: Referensi & Media
  ☐ Referensi utama: min 3 buku/jurnal
  ☐ Buku pendukung: min 2
  ☐ Tools/Software: tersebutkan
  ☐ Media pembelajaran: video, slide, etc

☐ TAB 6: Status & Persetujuan
  ☐ Pilih Koordinator
  ☐ Input notes jika ada
  ☐ Click [Save] or [Submit]

☐ PREVIEW PDF
  ☐ Cek format, nomor minggu, CPMK list
  ☐ Generate QR Code
  ☐ Save copy PDF untuk dokumentasi
```

### Performance Indicators Checklist
```
TAHAP 3: BOBOT PENILAIAN INPUT
==============================

Untuk setiap dari 7 indikator:
┌─────────────────────────────────────────┐
│ [1] TUGAS BESAR (TB) - 20%              │
├─────────────────────────────────────────┤
│ ☐ Kode: TB                              │
│ ☐ CPMK: [Pilih 1 CPMK]                  │
│ ☐ Deskripsi: Tugas Besar...            │
│ ☐ Jenis: Proyek                         │
│ ☐ Bobot: 20%                            │
│ ☐ Grade Scale: Universitas (default)   │
│ ☐ Click [Create]                        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ [2] UTS - 30%                           │
├─────────────────────────────────────────┤
│ ☐ Kode: UTS                             │
│ ☐ CPMK: [Pilih 1 CPMK]                  │
│ ☐ Deskripsi: Ujian Tengah Semester...  │
│ ☐ Jenis: Ujian Tulis                    │
│ ☐ Bobot: 30%                            │
│ ☐ Click [Create]                        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ [3] UAS - 30%                           │
├─────────────────────────────────────────┤
│ ☐ Kode: UAS                             │
│ ☐ CPMK: [Pilih 1 CPMK]                  │
│ ☐ Deskripsi: Ujian Akhir Semester...   │
│ ☐ Jenis: Ujian Tulis                    │
│ ☐ Bobot: 30%                            │
│ ☐ Click [Create]                        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ [4] QUIZ 1 (Q1) - 5%                    │
├─────────────────────────────────────────┤
│ ☐ Kode: Q1                              │
│ ☐ CPMK: [Pilih 1 CPMK]                  │
│ ☐ Deskripsi: Quiz 1...                 │
│ ☐ Jenis: Quiz                           │
│ ☐ Bobot: 5%                             │
│ ☐ Click [Create]                        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ [5] QUIZ 2 (Q2) - 5%                    │
├─────────────────────────────────────────┤
│ ☐ Kode: Q2                              │
│ ☐ CPMK: [Pilih 1 CPMK]                  │
│ ☐ Deskripsi: Quiz 2...                 │
│ ☐ Jenis: Quiz                           │
│ ☐ Bobot: 5%                             │
│ ☐ Click [Create]                        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ [6] TUGAS 1 (T1) - 5%                   │
├─────────────────────────────────────────┤
│ ☐ Kode: T1                              │
│ ☐ CPMK: [Pilih 1 CPMK]                  │
│ ☐ Deskripsi: Tugas 1...                │
│ ☐ Jenis: Tugas Individu                 │
│ ☐ Bobot: 5%                             │
│ ☐ Click [Create]                        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ [7] TUGAS 2 (T2) - 5%                   │
├─────────────────────────────────────────┤
│ ☐ Kode: T2                              │
│ ☐ CPMK: [Pilih 1 CPMK]                  │
│ ☐ Deskripsi: Tugas 2...                │
│ ☐ Jenis: Tugas Individu                 │
│ ☐ Bobot: 5%                             │
│ ☐ Click [Create]                        │
└─────────────────────────────────────────┘

VALIDATION: Total = 100% ✓
```

### CPMK-CPL Mapping Checklist
```
TAHAP 4: CPMK → CPL MAPPING
===========================

☐ Menu: Academic Management → Matriks CPMK → CPL
☐ Filter: 
  ☐ Fakultas: [pilih]
  ☐ Program Studi: [pilih]
  ☐ Mata Kuliah: [pilih]
☐ Click [Load Matriks]

Lihat hasil auto-mapping:
☐ Setiap CPMK ada di kolom CPL yang sesuai (1)
☐ CPMK yang tidak relevan bernilai 0

Jika perlu edit:
☐ Understand: 1 = berkontribusi, 0 = tidak
☐ Click cell untuk toggle value
☐ Pastikan setiap CPMK punya minimal 1 kontribusi (1)
☐ Click [Save]

Validasi final:
☐ Tidak ada "orphan CPMK" (semua punya kontribusi)
☐ Kontribusi meaningful (bukan random)
☐ View statistics OK
```

### Final Validation Checklist
```
TAHAP 5: VALIDASI & EXPORT
==========================

Assessment Matrix Validation:
☐ Menu: Academic Management → Assessment Matrix
☐ Filter dan load matrix
☐ Cek: Total per CPMK seimbang
☐ Cek: Total assessment type = 100%
☐ Cek: Setiap assessment ada
☐ Download Excel preview
☐ Download PDF preview

CPMK Contribution Matrix Validation:
☐ Menu: Academic Management → Matriks CPMK → CPL
☐ Filter dan load matrix
☐ Cek: Setiap CPMK berkontribusi ke CPL
☐ Cek: Total kontribusi meaningful
☐ Download matrix

RPS Final Review:
☐ Menu: 📝 RPS → List RPS
☐ Find your RPS (status: Draft)
☐ Click "Edit" atau "View" untuk final review
☐ Review setiap tab sekali lagi
☐ Click [Preview PDF]
☐ Verify format & content
☐ Click [Submit for Review]
☐ Select Koordinator (auto-suggest)
☐ Click [Submit]

STATUS CHECK:
☐ RPS status: Draft → Submitted (Pending Review)
☐ Koordinator akan menerima notifikasi
☐ Wait for approval
☐ RPS status: Approved → Published
```

---

## 📱 Quick Links

| Tujuan | Menu Path | Shortcut |
|--------|-----------|----------|
| Input RPS | 📝 RPS → Rencana Pembelajaran Semester | RPS |
| Input Bobot | 🎯 Learning Outcomes → Performance Indicators | PI |
| Mapping CPMK-CPL | Academic Management → Matriks CPMK → CPL | MAP |
| Assessment Matrix | Academic Management → Assessment Matrix | MATRIX |
| View RPS List | 📝 RPS → Rencana Pembelajaran Semester | RPS LIST |
| Curriculum Map | Academic Management → Curriculum Mapping | CURMAP |

---

## 🚨 Common Issues & Solutions

| Problem | Solution |
|---------|----------|
| CPMK tidak muncul di PI form | Pastikan CPMK sudah diceklis di RPS Tab 2 |
| Bobot PI total ≠ 100% | Check setiap PI, total harus 100% |
| Tidak bisa submit RPS | Fill semua Tab 1 & 2, no red warnings |
| Matrix kosong | Run seeder atau manual mapping |
| Grade scale error | Default Universitas, customize jika perlu |
| PDF tidak generate | Cek internet connection, try again |
| Koordinator tidak muncul | Koordinator harus create di system dulu |

---

## 📞 Support

- **For Technical Issues**: Contact IT/System Admin
- **For Data Issues**: Contact Kurikulum/Academic Staff
- **For Form Questions**: Check ALUR_INPUT_DATA_DOSEN.md detailed guide
