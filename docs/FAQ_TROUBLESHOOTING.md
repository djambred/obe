# FAQ & Troubleshooting - OBE System Input Guide

## ❓ Frequently Asked Questions (FAQ)

### RPS Input Questions

**Q1: Apakah harus buat RPS baru setiap tahun?**
A: Ya, RPS harus dibuat per tahun akademik. System akan auto-populate dari RPS tahun lalu jika ada, tapi tetap perlu review & update.

**Q2: Berapa jumlah CPL minimum yang harus dipilih?**
A: Minimum 2 CPL per RPS. Pilih CPL yang paling relevan dengan learning outcomes mata kuliah.

**Q3: Apakah harus semua CPMK digunakan?**
A: Tidak harus. Pilih CPMK yang akan diajarkan dalam semester itu. Bisa jadi 3-5 dari total 8 CPMK.

**Q4: Apakah minggu 16 selalu sama?**
A: Biasanya ya, tapi bisa disesuaikan dengan kalender akademik. Default 16 minggu bisa diubah di Tab 1.

**Q5: Referensi apa saja yang bisa digunakan?**
A: Jurnal, buku, conference proceedings, online resources yang kredibel. Min 3 untuk utama, 2 untuk pendukung.

**Q6: Bisa gak mengedit RPS setelah di-submit?**
A: Tidak. RPS yang sudah submitted harus di-approve/reject terlebih dahulu. Jika mau edit, minta Kaprodi return ke Draft.

---

### Performance Indicator Questions

**Q7: Kenapa harus tepat 7 indikator?**
A: Struktur OBE standar: TB (20%) + UTS (30%) + UAS (30%) + Quiz (10%) + Tugas (10%). Total 100%.

**Q8: Bisa gak mengubah bobot TB/UTS/UAS?**
A: Bisa, tapi minimal:
  - Ujian (UTS+UAS): 50-60% (assessment formal)
  - Tugas: 20-40% (continuous assessment)
  - Quiz: 10-20% (formative assessment)
  
  Tapi untuk consistency, ikuti standar 20-30-30-10-10.

**Q9: Apakah setiap indikator harus ke CPMK berbeda?**
A: Tidak. Bisa beberapa indikator ke CPMK yang sama. Yang penting:
  - Setiap CPMK tercakup oleh minimal 1 indikator
  - Distribusi balanced
  - Total = 100%

**Q10: Grade scale A-E bisa di-customize?**
A: Ya, tapi 3 level pilihan:
  - Universitas: A-E standar (fixed)
  - Fakultas: Bisa customize, apply ke semua course di fakultas
  - Prodi: Bisa customize, apply ke course specific prodi
  
  Pilih "Universitas" jika tidak ada requirement khusus.

**Q11: Passing grade harus 56?**
A: Default 56 (grade C minimum). Bisa diubah ke nilai lain sesuai kebijakan prodi, tapi konsisten untuk semua.

**Q12: Apa itu rubrik? Harus custom?**
A: Rubrik = deskripsi level pencapaian (Sangat Baik, Baik, Cukup, Kurang). Default sudah disediakan, customize hanya jika ada requirement khusus.

---

### CPMK-CPL Mapping Questions

**Q13: Apakah CPMK harus map ke semua CPL?**
A: Tidak. CPMK biasanya map ke 1-2 CPL yang paling relevan. Tidak semua CPMK berkontribusi ke semua CPL.

**Q14: Apa bedanya mapping 1 vs 0?**
A: 
  - **1** = CPMK berkontribusi/mendukung tercapainya CPL itu
  - **0** = CPMK tidak berkontribusi ke CPL itu
  
  Contoh: CPMK "Debugging" berkontribusi ke CPL "Problem Solving" (1), tapi tidak berkontribusi ke CPL "Komunikasi" (0).

**Q15: Siapa yang menentukan mapping?**
A: Dosen (subject matter expert). Bisa juga dikonfirmasi/direview oleh Kaprodi untuk konsistensi.

**Q16: Apakah mapping bisa berubah?**
A: Bisa, jika ada revisi kurikulum atau jika analisis ulang menunjukkan perubahan relevansi.

---

### Matrix & Validation Questions

**Q17: Apa itu Assessment Matrix?**
A: Matrix yang menunjukkan distribusi bobot penilaian ke CPMK.
```
Contoh:
         CPMK-1 CPMK-2 CPMK-3
TB       20%    -      -
UTS      -      30%    -
UAS      -      -      30%
Q1       5%     -      -
Q2       -      5%     -
T1       -      5%     -
T2       5%     -      -
TOTAL    30%    40%    30%
```

**Q18: Apa itu CPMK Contribution Matrix?**
A: Matrix yang menunjukkan kontribusi CPMK ke CPL (seperti gambar yang Anda tunjukkan).
```
Contoh:
         CPL-1 CPL-2 CPL-3 ... CPL-15
CPMK-1    1     0     0    ...   0
CPMK-2    0     1     0    ...   0
CPMK-3    0     0     1    ...   0
```

**Q19: Apakah matrix otomatis generate?**
A: Ya, setelah PI & mapping lengkap, system akan otomatis generate:
  - Assessment Matrix (dari PI data)
  - CPMK Contribution Matrix (dari mapping data)
  - Export sebagai Excel/PDF

**Q20: Total bobot harus 100%?**
A: Ya, pasti. 7 indikator harus total 100%:
  - TB: 20%
  - UTS: 30%
  - UAS: 30%
  - Q1: 5%
  - Q2: 5%
  - T1: 5%
  - T2: 5%
  **Total: 100%**

---

## 🔧 Troubleshooting

### Problem: "CPMK tidak muncul di performance indicator dropdown"

**Penyebab**: CPMK belum dipilih di RPS Tab 2
**Solusi**:
1. Buka RPS yang sedang dikerjakan
2. Go to Tab 2: Capaian Pembelajaran
3. Ceklis CPMK yang ingin digunakan
4. Save
5. Kembali ke Performance Indicators form
6. Refresh halaman atau buka form baru
7. CPMK sekarang akan muncul di dropdown

---

### Problem: "Total bobot tidak 100%"

**Penyebab**: Ada ketidaksesuaian antara indikator yang dibuat
**Solusi**:
1. Buka list Performance Indicators
2. Filter by Course (yang sedang dikerjakan)
3. Lihat semua indikator + bobotnya
4. Cek total: TB + UTS + UAS + Q1 + Q2 + T1 + T2 = ?
5. Jika ada yang kurang/lebih, delete/create/edit
6. Contoh: Jika hanya 6 indikator, ada yang missing (harus 7)
7. Jika bobot salah, misal T2 = 10% instead of 5%, fix it
8. Validate total = 100%

**Checklist Bobot**:
```
☐ TB  = 20% (Proyek)
☐ UTS = 30% (Ujian Tulis)
☐ UAS = 30% (Ujian Tulis)
☐ Q1  = 5%  (Quiz)
☐ Q2  = 5%  (Quiz)
☐ T1  = 5%  (Tugas Individu)
☐ T2  = 5%  (Tugas Individu)
───────────────
  TOTAL = 100%
```

---

### Problem: "Tidak bisa submit RPS, ada error/warning"

**Penyebab**: Ada field yang belum diisi atau tidak valid
**Solusi**:
1. Cek Tab 1 (Informasi Dasar):
   - ☐ Course: Selected
   - ☐ Kurikulum: Otomatis
   - ☐ Semester: Valid (1-8)
   - ☐ Tahun Akademik: Valid
   - ☐ Minggu Efektif: ≥ 14

2. Cek Tab 2 (Capaian Pembelajaran):
   - ☐ CPL: Minimum 2 selected, no duplicates
   - ☐ CPMK: Minimum 3 selected, no duplicates
   - ☐ Study Field: Minimum 1 selected

3. Cek Tab 3 (Rencana Pembelajaran):
   - ☐ 16 minggu semua terisi
   - ☐ Setiap minggu punya: topic, outcomes, methods
   - ☐ Tidak ada cell kosong

4. Cek Tab 5 (Referensi):
   - ☐ Minimal ada beberapa referensi

5. Cek Tab 6 (Status):
   - ☐ Koordinator: Selected (must exist in system)

6. Click "Preview PDF" untuk final check
   - ☐ Bisa generate tanpa error
   - ☐ Layout & content OK

7. Jika semua OK, bisa submit

---

### Problem: "Mapping tidak bisa load, matrix kosong"

**Penyebab**: Data belum ada atau filter salah
**Solusi**:
1. Pastikan mata kuliah dipilih sudah punya:
   - ✓ RPS (sudah dibuat)
   - ✓ Performance Indicators (7 items)
   - ✓ CPMK (sudah dipilih di RPS)
   - ✓ CPL (sudah ada di program studi)

2. Cek filter:
   - ☐ Fakultas: Selected (harus yang punya Study Program)
   - ☐ Study Program: Selected dari Fakultas itu
   - ☐ Course: Selected dari Study Program itu

3. Click "Load Matriks" button
4. Wait untuk load (bisa 2-3 detik)
5. Jika masih kosong, check di database:
   - ☐ Course punya curriculum
   - ☐ Curriculum punya study_program_id
   - ☐ Study Program punya CPL
   - ☐ Course punya CPMK

6. Jika perlu, jalankan seeder: 
   ```
   php artisan db:seed --class=CpmkCplMappingSeeder
   ```

---

### Problem: "Koordinator tidak muncul di dropdown RPS Tab 6"

**Penyebab**: Koordinator belum didefinisikan/tidak ada di system
**Solusi**:
1. Koordinator harus sudah terdaftar sebagai User dengan role "Dosen"
2. Hubungi Admin untuk:
   - ☐ Create user coordinator
   - ☐ Assign role "Dosen/Pengajar"
   - ☐ Set department/program
3. Setelah koordinator ada, refresh dropdown

---

### Problem: "PDF tidak generate / error saat export"

**Penyebab**: Memory limit atau file system issue
**Solusi**:
1. Cek koneksi internet (aktif)
2. Cek storage space (minimal 100MB free)
3. Try again (server mungkin busy)
4. Jika tetap error:
   - ☐ Clear browser cache
   - ☐ Try different browser
   - ☐ Contact IT support

---

### Problem: "RPS submission error, status tidak berubah"

**Penyebab**: Koordinator tidak valid atau system error
**Solusi**:
1. Check Tab 6: Koordinator harus dipilih dan exist
2. Try refresh halaman, then try submit lagi
3. Jika tetap error, check:
   - ☐ Browser console (F12) untuk error details
   - ☐ Screenshot error message
   - ☐ Report ke IT dengan timestamp

---

### Problem: "Cannot edit RPS, read-only"

**Penyebab**: RPS sudah submitted atau approved
**Solusi**:
1. Jika belum submitted: Refresh halaman, try again
2. Jika sudah submitted: Need approval first
   - Tunggu Koordinator review
   - Jika ditolak, akan kembali ke Draft (bisa edit)
   - Jika direvisi request: Return to Draft by Kaprodi
3. Jika sudah approved: Contact Kaprodi untuk unlock (rare case)

---

## 💡 Tips & Tricks

### Mengisi Tab 3 (16 minggu) dengan cepat:

**Tip 1**: Template minggu
```
Minggu 1-2: Introduction & Basics
Minggu 3-5: Core Concepts
Minggu 6-8: Implementation
Minggu 9-10: Advanced Topics
Minggu 11-12: Projects/Case Studies
Minggu 13-15: Review & Practice
Minggu 16: Final Review & Assessment
```

**Tip 2**: Gunakan copy-paste
- Buat template untuk semua minggu di text editor
- Copy-paste ke form (jika allowed)
- Atau input satu-satu dengan pattern

**Tip 3**: Mapping CPMK to weeks
```
CPMK-01: Week 1-3 (Introduction)
CPMK-02: Week 4-6 (Core concepts)
CPMK-03: Week 7-9 (Implementation)
CPMK-04: Week 10-13 (Advanced)
CPMK-05: Week 14-16 (Synthesis)
```

---

### Performance Indicator shortcut:

**Tip**: Jika semua indikator menggunakan standar universitas & format sama, bisa bulk create dengan mengganti CPMK/kode saja.

Template form:
```
Jenis: [Assessment Type]
Bobot: [Weight]
Rubrik: [Standard OBE 4-level - copy dari contoh]
Grading Scale: [A: 86-100, B: 71-85, ... - standard]
Passing Grade: 56.00 [fixed]
Status: Active [fixed]
Grade Scale Level: Universitas [fixed]
```

---

### CPMK-CPL Mapping strategy:

**Tip 1**: Sequential mapping
```
CPMK-01 → CPL-9
CPMK-02 → CPL-10
CPMK-03 → CPL-11
CPMK-04 → CPL-12
CPMK-05 → CPL-13
```

**Tip 2**: Thematic mapping
```
CPMK-01 (Knowledge) → CPL-KK01-KK05
CPMK-02 (Skill) → CPL-KU01-KU05
CPMK-03-05 (Generic) → CPL-P01-P05
```

**Tip 3**: Check existing mapping first
```
Sebelum manual edit, lihat hasil seeder
Hanya edit jika ada yang tidak cocok
Jangan ubah yang sudah OK
```

---

## 📞 When to Contact Support

| Situation | Contact |
|-----------|---------|
| Cannot find course/CPMK | Academic/Kurikulum staff |
| CPMK error di form | IT/System admin |
| Permission/access denied | IT admin |
| Coordinator not in list | HR/Admin (create user first) |
| Matrix not showing | Academic staff + IT |
| PDF export error | IT support |
| Database issue | Database admin |
| Feature request | Kaprodi/Academic planning |

---

## 📋 Approval Workflow Status

```
Status Flow & What Each Means:

┌─────────┐
│ DRAFT   │ → RPS dibuat, belum submit
└────┬────┘
     │ (Click Submit)
     ▼
┌──────────────┐
│ SUBMITTED    │ → Pending Koordinator review
│ PENDING      │
└────┬─────────┘
     │ (Koordinator action)
     ├─ ▶ Approve → APPROVED
     └─ ▶ Reject  → REVISION_NEEDED
                 │
                 ▼ (Dosen edit & resubmit)
             ┌─────────────────┐
             │ REVISION_NEEDED │
             └────┬────────────┘
                  │ (Submit lagi)
                  ▼
             SUBMITTED (ulang)

┌─────────┐
│ APPROVED│ → RPS published, students can see
└─────────┘

┌──────────────┐
│ ARCHIVED     │ → RPS dari semester/tahun lalu (read-only)
└──────────────┘
```

---

## 🎓 Learning Resources

- **Video Tutorial**: [Link will be provided]
- **Demo Course**: "Algoritma dan Pemrograman" (ILK.102)
- **Webinar**: Monthly training (Thursdays 3PM)
- **Handbook**: ALUR_INPUT_DATA_DOSEN.md (this folder)
- **Support Email**: support@unesa-obe.edu
- **WhatsApp Group**: [Link for quick questions]

