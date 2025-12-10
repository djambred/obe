# Performance Indicator Seeder

## Overview
Seeder ini membuat data indikator kinerja (Performance Indicator) untuk mata kuliah yang sudah memiliki CPMK. Data ini digunakan untuk menampilkan Assessment Matrix OBE yang menunjukkan distribusi bobot penilaian terhadap CPMK.

## Struktur Penilaian

Setiap mata kuliah memiliki **7 indikator kinerja** yang terdistribusi sebagai berikut:

### 1. Tugas Besar / Proyek (20%)
- **TB**: Analisis, perancangan, implementasi, dan dokumentasi proyek komprehensif
- Mencakup seluruh aspek pembelajaran dalam satu proyek terintegrasi
- Dikerjakan berkelompok atau individu sesuai instruksi

### 2. Ujian Tengah Semester - UTS (30%)
- **UTS**: Penguasaan materi minggu 1-7
- Mencakup pemahaman konseptual, kemampuan aplikasi, dan pemecahan masalah
- Format: Ujian Tulis

### 3. Ujian Akhir Semester - UAS (30%)
- **UAS**: Penguasaan materi minggu 8-14 dan integrasi konsep
- Mencakup sintesis, evaluasi, dan studi kasus komprehensif
- Format: Ujian Tulis

### 4. Quiz (10%)
- **Q1**: Pemahaman konsep dasar dan penerapan teori (5%)
- **Q2**: Analisis dan evaluasi materi pembelajaran (5%)
- Dikerjakan individu dalam waktu terbatas

### 5. Tugas Individu (10%)
- **T1**: Implementasi konsep dalam kasus sederhana (5%)
- **T2**: Analisis dan dokumentasi hasil pembelajaran (5%)
- Dikerjakan mandiri sesuai instruksi

**Total Bobot: 100%**

## Distribusi ke CPMK

Setiap indikator kinerja dipetakan ke salah satu CPMK untuk memastikan:
1. Setiap CPMK diukur oleh beberapa jenis penilaian
2. Tidak ada CPMK yang terlalu dominan atau terabaikan
3. Distribusi bobot seimbang antar CPMK

## Contoh Matrix (ILK.102 - Algoritma dan Pemrograman)

```
Item    Jenis           Bobot   CPMK-01   CPMK-02   CPMK-03   CPMK-04   CPMK-05   Total
---------------------------------------------------------------------------------------

TB      Proyek          20%     20.0%     -         -         -         -         20%
UTS     Ujian Tulis     30%     -         30.0%     -         -         -         30%
UAS     Ujian Tulis     30%     -         -         30.0%     -         -         30%
Q1      Quiz            5%      -         -         -         5.0%      -         5%
Q2      Quiz            5%      -         -         -         -         5.0%      5%
T1      Tugas Individu  5%      -         -         -         -         5.0%      5%
T2      Tugas Individu  5%      5.0%      -         -         -         -         5%

---------------------------------------------------------------------------------------
Total CPMK              100%    25.0%     30.0%     30.0%     5.0%      10.0%     100%
```

## Rubrik Penilaian OBE

Setiap indikator menggunakan rubrik standar OBE:

```
- Sangat Baik (86-100): Menunjukkan pemahaman yang sangat mendalam dan mampu mengaplikasikan konsep dengan sangat baik
- Baik (71-85): Menunjukkan pemahaman yang baik dan mampu mengaplikasikan konsep dengan baik
- Cukup (56-70): Menunjukkan pemahaman yang cukup dan mampu mengaplikasikan konsep dasar
- Kurang (0-55): Belum menunjukkan pemahaman yang memadai
```

## Skala Penilaian (Grading Scale)

### Level Skala Nilai
Setiap Performance Indicator memiliki level skala nilai yang dapat disesuaikan:

1. **Universitas** (Default)
   - Standar penilaian berlaku untuk seluruh universitas
   - Tidak memerlukan referensi fakultas/prodi

2. **Fakultas**
   - Standar penilaian khusus fakultas tertentu
   - Memerlukan referensi faculty_id

3. **Program Studi**
   - Standar penilaian khusus program studi tertentu
   - Memerlukan referensi study_program_id

### Konversi Nilai Standar Universitas

```
A: 86-100  (Sangat Baik)
B: 71-85   (Baik)
C: 56-70   (Cukup)
D: 41-55   (Kurang)
E: 0-40    (Sangat Kurang)
```

### Passing Grade

Standar kelulusan: **56** (grade C - Cukup)

## Cara Menjalankan Seeder

```bash
# Jalankan seeder ini saja
docker exec obe_php php artisan db:seed --class=PerformanceIndicatorSeeder

# Atau jalankan semua seeder
docker exec obe_php php artisan db:seed
```

## Mata Kuliah yang Memiliki Data

Seeder ini otomatis membuat data untuk mata kuliah yang sudah memiliki CPMK:

1. **ILK.102** - Algoritma dan Pemrograman (5 CPMK)
2. **ILK.201** - Struktur Data (5 CPMK)
3. **ILK.202** - Pemrograman Berorientasi Objek (5 CPMK)
4. **ILK.301** - Desain dan Analisis Algoritma (5 CPMK)
5. **ILK.401** - Kecerdasan Buatan (5 CPMK)

**Total**: 35 indikator kinerja (7 indikator × 5 mata kuliah)

## Melihat Assessment Matrix

1. Login ke Filament Admin Panel
2. Navigasi ke **Academic Management** → **Assessment Matrix**
3. Pilih:
   - **Faculty**: Fakultas yang sesuai
   - **Study Program**: Program Studi yang sesuai
   - **Course**: Pilih salah satu dari 5 mata kuliah di atas
4. Klik **Load Matrix**
5. Matrix akan menampilkan distribusi bobot penilaian terhadap CPMK

## Notes

- Seeder ini menggunakan `truncate()` sehingga akan menghapus data lama sebelum membuat data baru
- Distribusi CPMK dilakukan secara otomatis dengan algoritma modulo untuk memastikan pemerataan
- Setiap CPMK minimal diukur oleh 2-3 jenis penilaian yang berbeda
- Total bobot selalu 100% untuk memastikan konsistensi OBE
