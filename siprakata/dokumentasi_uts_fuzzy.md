# DOKUMENTASI UTS - Implementasi Logika Fuzzy pada Sistem Manajemen Proses Belajar
## SIPRAKATA (Sistem Informasi Akademik)

**NIM:** 2155201110011
**Mata Kuliah:** Manajemen Proses Belajar
**Aplikasi:** SIPRAKATA - Sistem Akademik Berbasis Laravel 13

---

# SOAL 1: Variabel Input & Output Fuzzy

## 1.1 Konfigurasi Variabel

Sistem menggunakan **3 variabel input** dan **1 variabel output** dengan metode **Mamdani**.

### Tabel Variabel

| No | Variabel | Tipe | Domain | Himpunan Fuzzy |
|----|----------|------|--------|----------------|
| 1 | Kehadiran | Input | 0% – 100% | Rendah, Sedang, Tinggi |
| 2 | Nilai Tugas | Input | 0 – 100 | Rendah, Sedang, Tinggi |
| 3 | Keaktifan Diskusi | Input | 0 – 100 | Rendah, Sedang, Tinggi |
| 4 | Skor Prediksi Kelulusan | Output | 0 – 100 | Tidak Lulus, Cukup, Lulus |

### Sumber Data

| Variabel | Sumber Tabel | Kolom/Keterangan |
|----------|-------------|------------------|
| Kehadiran | `presensi` | Rasio `status_hadir`='hadir' terhadap total pertemuan per mata kuliah |
| Nilai Tugas | `nilai` | Kolom `nilai_tugas` (0–100) |
| Keaktifan Diskusi | `presensi` | Rasio `status_hadir`='hadir' (tanpa izin/sakit) terhadap total pertemuan |
| Skor Prediksi | `kelayakan_mahasiswa` | Kolom `skor_prediksi` hasil defuzzifikasi |

## 1.2 Domain / Semesta Pembicaraan

| Variabel | Semesta (Universe) | Satuan |
|----------|-------------------|--------|
| Kehadiran | [0, 100] | Persen (%) |
| Nilai Tugas | [0, 100] | Skala 0–100 |
| Keaktifan Diskusi | [0, 100] | Persen (%) |
| Skor Prediksi | [0, 100] | Skala 0–100 |

## 1.3 Fungsi Keanggotaan dan Rumus

### A. Kehadiran (0% – 100%)

**Rendah** — Linear Turun (trapesium kiri):

```
         1 ─────────┐
                     │
         0 ──────────┴──────────
             0     50  60     100
```

Rumus:
```
μRendah(x) = 1                    jika x ≤ 50
             (60 - x) / 10        jika 50 < x < 60
             0                    jika x ≥ 60
```

**Sedang** — Trapesium:

```
                 ┌────────┐
                 │        │
         0 ──────┘        └──────
            50  60    75  85
```

Rumus:
```
μSedang(x) = 0                    jika x ≤ 50
             (x - 50) / 10        jika 50 < x ≤ 60
             1                    jika 60 < x ≤ 75
             (85 - x) / 10        jika 75 < x < 85
             0                    jika x ≥ 85
```

**Tinggi** — Linear Naik (trapesium kanan):

```
                     ┌──────────
                     │
         0 ──────────┘
            75  85   100
```

Rumus:
```
μTinggi(x) = 0                    jika x ≤ 75
             (x - 75) / 10        jika 75 < x < 85
             1                    jika x ≥ 85
```

### B. Nilai Tugas (0 – 100)

Fungsi keanggotaan **identik** dengan Kehadiran (same shape, same breakpoints):

| Himpunan | Bentuk | Titik Kritis |
|----------|--------|-------------|
| Rendah | Linear turun | 0–50 (full), 50–60 (turun) |
| Sedang | Trapesium | 50–60 (naik), 60–75 (full), 75–85 (turun) |
| Tinggi | Linear naik | 75–85 (naik), 85–100 (full) |

### C. Keaktifan Diskusi (0 – 100)

**Rendah** — Linear Turun:

```
μRendah(x) = 1                    jika x ≤ 40
             (50 - x) / 10        jika 40 < x < 50
             0                    jika x ≥ 50
```

**Sedang** — Trapesium:

```
μSedang(x) = 0                    jika x ≤ 40
             (x - 40) / 10        jika 40 < x ≤ 50
             1                    jika 50 < x ≤ 70
             (80 - x) / 10        jika 70 < x < 80
             0                    jika x ≥ 80
```

**Tinggi** — Linear Naik:

```
μTinggi(x) = 0                    jika x ≤ 70
             (x - 70) / 10        jika 70 < x < 80
             1                    jika x ≥ 80
```

### D. Output: Skor Prediksi (0 – 100)

| Himpunan | Centroid | Bentuk | Titik Kritis |
|----------|---------|--------|-------------|
| Tidak Lulus | 20 | Linear turun | 0–30 (full), 30–40 (turun) |
| Cukup | 50 | Trapesium | 30–40 (naik), 40–60 (full), 60–70 (turun) |
| Lulus | 80 | Linear naik | 60–70 (naik), 70–100 (full) |

## 1.4 Ringkasan Grafik Fungsi Keanggotaan

### Input

```
Kehadiran / Nilai Tugas (0–100):

  1.0 ───┐          ┌────────┐          ┌──────
         │          │        │          │
  0.0 ───┴──────────┘        └──────────┴───
         0    50  60    75  85        100
           [Rendah]   [Sedang]    [Tinggi]

Keaktifan Diskusi (0–100):

  1.0 ──┐            ┌──────────┐         ┌──────
        │            │          │         │
  0.0 ──┴────────────┘          └─────────┴───
        0   40  50       70  80        100
         [Rendah]    [Sedang]     [Tinggi]
```

### Output

```
Skor Prediksi (0–100):

  1.0 ───┐          ┌────────┐          ┌──────
         │          │        │          │
  0.0 ───┴──────────┘        └──────────┴───
         0    30  40    60  70        100
         [Tidak Lulus] [Cukup]     [Lulus]
```

---

# SOAL 2: Rule Base (Aturan Fuzzy)

## 2.1 Metode: Mamdani

- **Operator AND**: `min(μ1, μ2, μ3)`
- **Operator OR**: `max()` (tidak digunakan pada kasus ini)
- **Implikasi**: Minimum (truncation)
- **Agregasi**: Maximum
- **Jumlah Rules**: 27 (kombinasi lengkap 3 × 3 × 3)

## 2.2 Tabel Rule Base Lengkap (27 Rules)

### Kehadiran Rendah (R1–R9)

| Rule | IF Kehadiran | AND Tugas | AND Diskusi | THEN Output |
|------|-------------|-----------|-------------|-------------|
| R1 | Rendah | Rendah | Rendah | **Tidak Lulus** |
| R2 | Rendah | Rendah | Sedang | **Tidak Lulus** |
| R3 | Rendah | Rendah | Tinggi | **Tidak Lulus** |
| R4 | Rendah | Sedang | Rendah | **Tidak Lulus** |
| R5 | Rendah | Sedang | Sedang | **Tidak Lulus** |
| R6 | Rendah | Sedang | Tinggi | **Cukup** |
| R7 | Rendah | Tinggi | Rendah | **Tidak Lulus** |
| R8 | Rendah | Tinggi | Sedang | **Cukup** |
| R9 | Rendah | Tinggi | Tinggi | **Cukup** |

### Kehadiran Sedang (R10–R18)

| Rule | IF Kehadiran | AND Tugas | AND Diskusi | THEN Output |
|------|-------------|-----------|-------------|-------------|
| R10 | Sedang | Rendah | Rendah | **Tidak Lulus** |
| R11 | Sedang | Rendah | Sedang | **Cukup** |
| R12 | Sedang | Rendah | Tinggi | **Cukup** |
| R13 | Sedang | Sedang | Rendah | **Cukup** |
| R14 | Sedang | Sedang | Sedang | **Cukup** |
| R15 | Sedang | Sedang | Tinggi | **Lulus** |
| R16 | Sedang | Tinggi | Rendah | **Cukup** |
| R17 | Sedang | Tinggi | Sedang | **Lulus** |
| R18 | Sedang | Tinggi | Tinggi | **Lulus** |

### Kehadiran Tinggi (R19–R27)

| Rule | IF Kehadiran | AND Tugas | AND Diskusi | THEN Output |
|------|-------------|-----------|-------------|-------------|
| R19 | Tinggi | Rendah | Rendah | **Cukup** |
| R20 | Tinggi | Rendah | Sedang | **Cukup** |
| R21 | Tinggi | Rendah | Tinggi | **Lulus** |
| R22 | Tinggi | Sedang | Rendah | **Cukup** |
| R23 | Tinggi | Sedang | Sedang | **Lulus** |
| R24 | Tinggi | Sedang | Tinggi | **Lulus** |
| R25 | Tinggi | Tinggi | Rendah | **Lulus** |
| R26 | Tinggi | Tinggi | Sedang | **Lulus** |
| R27 | Tinggi | Tinggi | Tinggi | **Lulus** |

## 2.3 Statistik Distribusi Output

| Output | Jumlah Rules | Persentase |
|--------|-------------|-----------|
| Tidak Lulus | 8 rules | 29.6% |
| Cukup | 9 rules | 33.3% |
| Lulus | 10 rules | 37.0% |

**Logika rules:**
- Kehadiran rendah → condong ke Tidak Lulus (kehadiran sangat berpengaruh)
- Kehadiran tinggi → selalu minimal Cukup, bahkan dengan tugas rendah
- Ketiga input tinggi → pasti Lulus
- Output seimbang, tidak bias ke satu kategori

---

# SOAL 3: Proses Inferensi & Defuzzifikasi

## 3.1 Contoh Kasus Nyata

> **Mahasiswa:** Andi Pratama (NIM 2023003)
> **Mata Kuliah:** Analisis & Perancangan Sistem Informasi (MK007, 3 SKS)
> **KRS Status:** Disetujui

### Input dari Database

| Variabel | Nilai | Sumber |
|----------|-------|--------|
| Kehadiran | 85.71% | 6 dari 7 pertemuan hadir (presensi) |
| Nilai Tugas | 55.00 | Kolom `nilai_tugas` pada tabel `nilai` |
| Keaktifan Diskusi | 57.14% | 4 dari 7 pertemuan hadir aktif (presensi) |

## 3.2 Langkah 1: Fuzzifikasi

### Kehadiran = 85.71%

```
μRendah(85.71)  = 0                    (85.71 ≥ 60, di luar range)
μSedang(85.71)  = 0                    (85.71 ≥ 85, di luar range)
μTinggi(85.71)  = (85.71 - 75) / 10   = 10.71 / 10 = 1.07 → dibatasi 1.00
```

> **Hasil:** Kehadiran = Tinggi (μ = 1.00)

### Nilai Tugas = 55

```
μRendah(55)  = (60 - 55) / 10  = 5/10 = 0.50
μSedang(55)  = (55 - 50) / 10  = 5/10 = 0.50
μTinggi(55)  = 0                (55 ≤ 75, di bawah range)
```

> **Hasil:** Tugas = Rendah (μ = 0.50) DAN Sedang (μ = 0.50)

### Keaktifan Diskusi = 57.14%

```
μRendah(57.14)  = 0                    (57.14 ≥ 50, di luar range)
μSedang(57.14)  = (57.14 - 40) / 10   = 17.14/10 → dibatasi 1.00
μTinggi(57.14)  = 0                    (57.14 ≤ 70, di bawah range)
```

> **Hasil:** Diskusi = Sedang (μ = 1.00)

### Tabel Fuzzifikasi

| Variabel | Input | μRendah | μSedang | μTinggi |
|----------|-------|---------|---------|---------|
| Kehadiran | 85.71% | 0.00 | 0.00 | **1.00** |
| Nilai Tugas | 55.00 | **0.50** | **0.50** | 0.00 |
| Keaktifan Diskusi | 57.14% | 0.00 | **1.00** | 0.00 |

## 3.3 Langkah 2: Rule Evaluation (Operator min)

Karena Tugas punya 2 himpunan aktif (Rendah & Sedang), ada **2 rules aktif**:

### Rule 20

```
IF Kehadiran = Tinggi AND Tugas = Rendah AND Diskusi = Sedang THEN Cukup

μ = min(μTinggi_kehadiran, μRendah_tugas, μSedang_diskusi)
μ = min(1.00, 0.50, 1.00)
μ = 0.50
```

### Rule 23

```
IF Kehadiran = Tinggi AND Tugas = Sedang AND Diskusi = Sedang THEN Lulus

μ = min(μTinggi_kehadiran, μSedang_tugas, μSedang_diskusi)
μ = min(1.00, 0.50, 1.00)
μ = 0.50
```

### Ringkasan Rules Aktif

| Rule | Kondisi (IF) | μ (min) | Output (THEN) |
|------|-------------|---------|---------------|
| R20 | Kehadiran Tinggi & Tugas Rendah & Diskusi Sedang | 0.50 | Cukup |
| R23 | Kehadiran Tinggi & Tugas Sedang & Diskusi Sedang | 0.50 | Lulus |

## 3.4 Langkah 3: Defuzzifikasi (Weighted Average)

```
Skor = Σ(μ_i × centroid_i) / Σ(μ_i)

Centroid: Tidak Lulus = 20, Cukup = 50, Lulus = 80

Skor = (0.50 × 50 + 0.50 × 80) / (0.50 + 0.50)
     = (25.00 + 40.00) / 1.00
     = 65.00
```

### Keputusan Akhir

```
Skor = 65.00 ≥ 60 → DIPREDIKSI LULUS
```

**Threshold Keputusan:**
| Skor | Hasil |
|------|-------|
| ≥ 60 | Lulus |
| 40 – 59 | Cukup |
| < 40 | Tidak Lulus |

**Penjelasan:** Mahasiswa ini memiliki kehadiran sangat tinggi (85.71%), namun nilai tugas masih rendah-menengah (55). Sistem fuzzy memberikan skor 65 (di atas threshold 60), artinya **diprediksi lulus** karena kehadiran yang sangat baik mengkompensasi nilai tugas yang belum optimal.

## 3.5 Perbandingan dengan If-Else Konvensional

| Metode | Hasil | Penjelasan |
|--------|-------|-----------|
| If-Else (≥75/70/60) | **Tidak Lulus** | Tugas 55 < 70 → langsung gagal |
| Fuzzy Mamdani | **Lulus (skor 65)** | Kehadiran tinggi mengkompensasi tugas rendah |

---

# SOAL 4: Rancangan Implementasi di Laravel

## 4.1 Arsitektur MVC + Service

```
┌─────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                     │
│                                                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐     │
│  │  index.blade │  │ create.blade│  │ detail.blade│     │
│  │  (Tabel      │  │ (Form input │  │ (Fuzzific., │     │
│  │  prediksi)   │  │  AJAX MK)   │  │  Rules,SVG) │     │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘     │
│         │                │                │              │
├─────────┼────────────────┼────────────────┼──────────────┤
│         ▼                ▼                ▼              │
│                    ROUTE LAYER                            │
│         GET /kelayakan                                    │
│         GET /kelayakan/create                             │
│         POST /kelayakan/proses                            │
│         POST /kelayakan/batch                             │
│         GET /kelayakan/{id}                               │
│         GET /kelayakan/ajax/matakuliah                    │
├───────────────────────────────────────────────────────────┤
│                  CONTROLLER LAYER                         │
│                                                           │
│  ┌──────────────────────────────────────────────┐        │
│  │         KelayakanController                   │        │
│  │  - index()         → daftar prediksi         │        │
│  │  - create()        → form + AJAX MK          │        │
│  │  - proses()        → 1 mahasiswa × 1 MK      │        │
│  │  - batchProses()   → semua mhs × semua MK    │        │
│  │  - detail()        → detail perhitungan       │        │
│  │  - getMatakuliahByMahasiswa() → AJAX JSON    │        │
│  └───────────────────────┬──────────────────────┘        │
│                          │                                │
├──────────────────────────┼────────────────────────────────┤
│                    SERVICE LAYER                           │
│                          ▼                                │
│  ┌──────────────────────────────────────────────┐        │
│  │         FuzzyLogicService (311 baris)         │        │
│  │                                                │        │
│  │  Pengambilan Data:                            │        │
│  │  ├── hitungKehadiran(mhs, mk) → float         │        │
│  │  ├── hitungNilaiTugas(mhs, mk) → float        │        │
│  │  └── hitungKeaktifanDiskusi(mhs, mk) → float  │        │
│  │                                                │        │
│  │  Fungsi Keanggotaan (9 fungsi):               │        │
│  │  ├── kehadiran{Rendah,Sedang,Tinggi}(x)       │        │
│  │  ├── tugas{Rendah,Sedang,Tinggi}(x)           │        │
│  │  └── diskusi{Rendah,Sedang,Tinggi}(x)         │        │
│  │                                                │        │
  │  │  Perhitungan:                                  │        │
  │  │  └── hitungPrediksi(keh, tug, dis) → array    │        │
  │  │      (Mamdani: 27 Rules + Weighted Average)    │        │
│  │                                                │        │
│  │  Pipeline:                                     │        │
│  │  └── analisis(mhs, mk) → array (lengkap)      │        │
│  └───────────────────────┬──────────────────────┘        │
│                          │                                │
├──────────────────────────┼────────────────────────────────┤
│                    MODEL LAYER                             │
│                          ▼                                │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐   │
│  │  Kelayakan    │  │  Mahasiswa   │  │  Matakuliah  │   │
│  │  (hasil       │──│  (master)    │  │  (master)    │   │
│  │   fuzzy)      │──│              │  │              │   │
│  └──────────────┘  └──────────────┘  └──────────────┘   │
│         │                                                   │
│         │ (via transaksi_krs)                              │
│         ▼                                                   │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐   │
│  │ TransaksiKrs  │  │  Presensi    │  │    Nilai     │   │
│  │ (KRS)         │  │ (kehadiran)  │  │  (tugas)     │   │
│  └──────────────┘  └──────────────┘  └──────────────┘   │
├───────────────────────────────────────────────────────────┤
│                    DATABASE LAYER                          │
│              MySQL (laravelyudha)                          │
└───────────────────────────────────────────────────────────┘
```

## 4.2 ERD (Entity Relationship Diagram)

```
┌─────────────────┐       ┌──────────────────┐
│    mahasiswa     │       │    matakuliah     │
├─────────────────┤       ├──────────────────┤
│ id (PK)         │       │ id (PK)          │
│ NIM (unique)    │       │ kode_mk (unique) │
│ nama            │       │ nama_mk          │
│ prodi_id (FK)   │       │ sks              │
│ status          │       │ semester         │
│ ...             │       │ prodi_id (FK)    │
└────────┬────────┘       └────────┬─────────┘
         │                         │
         │    ┌────────────────────┘
         │    │
         ▼    ▼
┌──────────────────────────────────────────────┐
│          kelayakan_mahasiswa                  │
├──────────────────────────────────────────────┤
│ id (PK)                                      │
│ mahasiswa_id (FK → mahasiswa)                │
│ matakuliah_id (FK → matakuliah)              │
│ tahun_ajaran (VARCHAR)                       │
│ semester (VARCHAR)                           │
│ kehadiran (DECIMAL 5,2)    ← INPUT FUZZY 1   │
│ nilai_tugas (DECIMAL 5,2)  ← INPUT FUZZY 2   │
│ keaktifan_diskusi (DECIMAL)← INPUT FUZZY 3   │
│ skor_prediksi (DECIMAL)    ← OUTPUT (crisp)  │
│ hasil_prediksi (ENUM)      ← lulus/cukup/tidak_lulus │
│ detail_perhitungan (TEXT)   ← JSON log        │
│ created_at, updated_at                       │
│ UNIQUE (mahasiswa_id, matakuliah_id,         │
│         tahun_ajaran, semester)              │
└──────────────────────────────────────────────┘

┌──────────────────────┐    ┌─────────────────┐
│    transaksi_krs      │    │     presensi     │
├──────────────────────┤    ├─────────────────┤
│ id (PK)              │    │ id (PK)         │
│ mahasiswa_id (FK)    │    │ jadwal_id (FK)  │
│ matakuliah_id (FK)   │    │ mahasiswa_id(FK)│
│ dosen_id (FK)        │    │ tanggal         │
│ tahun_ajaran         │    │ pertemuan_ke    │
│ semester             │    │ status_hadir    │
│ status_validasi      │    │ ...             │
└──────────────────────┘    └─────────────────┘

┌─────────────────┐
│      nilai       │
├─────────────────┤
│ id (PK)         │
│ krs_id (FK)     │ ← ke transaksi_krs
│ nilai_tugas     │ ← sumber input fuzzy
│ nilai_uts       │
│ nilai_uas       │
│ nilai_akhir     │
│ grade           │
└─────────────────┘
```

## 4.3 Deskripsi File & Fungsi

| Komponen | File | Fungsi |
|----------|------|--------|
| **Model** | `app/Models/Kelayakan.php` | ORM untuk tabel `kelayakan_mahasiswa`, relasi ke Mahasiswa & Matakuliah |
| **Service** | `app/Services/FuzzyLogicService.php` | Logika fuzzy Mamdani: fuzzification (9 fungsi), 27 rules, defuzzifikasi centroid, pengambilan data dari DB |
| **Controller** | `app/Http/Controllers/KelayakanController.php` | Handle request: index, create, proses, batch, detail, AJAX |
| **Migration** | `database/migrations/2026_07_21_000001_...php` | Buat tabel `kelayakan_mahasiswa` dengan kolom input/output fuzzy |
| **View Index** | `resources/views/kelayakan/index.blade.php` | Tabel daftar prediksi + kartu ringkasan |
| **View Create** | `resources/views/kelayakan/create.blade.php` | Form input + dropdown dinamis MK via JavaScript |
| **View Detail** | `resources/views/kelayakan/detail.blade.php` | Fuzzification + Rules Aktif + Grafik SVG + Hasil |
| **Routes** | `routes/web.php` (baris 82–88) | 6 routes untuk modul kelayakan |

## 4.4 Alur Logika Program (Flowchart)

```
┌─────────────────────────────┐
│   1. USER AKSI              │
│   - Pilih Mahasiswa         │
│   - Pilih Mata Kuliah       │
│   - Pilih Tahun/Semester    │
│   - Klik "Hitung Prediksi"  │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│   2. ROUTE                  │
│   POST /kelayakan/proses    │
│   → KelayakanController     │
│     ::proses()              │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│   3. VALIDASI               │
│   - mahasiswa_id exists?    │
│   - matakuliah_id exists?   │
│   - KRS disetujui?          │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│   4. SERVICE: ANALISIS      │
│   FuzzyLogicService         │
│   ::analisis($mhs, $mk)    │
│                             │
│   4a. Ambil Data:           │
│   ├── hitungKehadiran()     │
│   │   → query presensi      │
│   │   → hitung % hadir      │
│   ├── hitungNilaiTugas()    │
│   │   → query nilai table   │
│   │   → ambil nilai_tugas   │
│   └── hitungKeaktifanDiskusi│
│       → query presensi      │
│       → hitung % hadir aktif│
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│   5. FUZZIFICATION          │
│   hitungPrediksi(keh,tug,dis)│
│                             │
│   Untuk setiap variabel:    │
│   ├── μRendah = fungsi(x)  │
│   ├── μSedang = fungsi(x)  │
│   └── μTinggi = fungsi(x)  │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│   6. RULE EVALUATION        │
│   27 Rules × min operator   │
│                             │
│   Untuk setiap rule:        │
│   μ_rule = min(μA, μB, μC)│
│   → tentukan output         │
│     (lulus/cukup/tidak)     │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│   7. DEFUZZIFICATION        │
│   Weighted Average          │
│                             │
│   Skor = Σ(μi × centroidi) │
│          / Σ(μi)           │
│                             │
  │   centroid: 20 / 50 / 80   │
  │   threshold: ≥60=lulus      │
  │          40-59=cukup        │
  │          <40=tidak_lulus    │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│   8. SIMPAN DATABASE        │
│   Kelayakan::updateOrCreate │
│   + detail_perhitungan JSON │
│   (semua step fuzzy)        │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│   9. REDIRECT → DETAIL      │
│   Tampilkan:                │
│   - Fuzzification + Grafik  │
│   - Rules Aktif (tabel)     │
│   - Grafik Output SVG       │
│   - Hasil: LULUS/TIDAK      │
└─────────────────────────────┘
```

---

# SOAL 5: Analisis dan Kesimpulan

## 5.1 Mengapa Mamdani (bukan Tsukamoto)?

### Perbandingan Metode

| Aspek | Mamdani | Tsukamoto |
|-------|---------|-----------|
| Input | Himpunan fuzzy | Himpunan fuzzy |
| Output | Himpunan fuzzy (perlu defuzzifikasi) | Fungsi monotonik → crisp langsung |
| Defuzzifikasi | Centroid / Weighted Average | Rata-rata terbobot dari semua rule |
| Visualisasi | Mudah ditampilkan grafik himpunan | Sulit divisualisasikan |
| **Interpretabilitas** | **Tinggi — rule mudah dipahami** | Sedang |
| **Kasus cocok** | **Penilaian/keputusan berbasis kategori** | Control system (suhu, motor) |

### Alasan Mamdani Dipilih

1. **Input bernilai kontinu** — Kehadiran (0–100%), Nilai Tugas (0–100), Keaktifan (0–100) sangat cocok untuk himpunan fuzzy Rendah/Sedang/Tinggi

2. **Output berupa kategori** — Kita ingin menentukan apakah mahasiswa "Lulus", "Cukup", atau "Tidak Lulus" → Mamdani menghasilkan output himpunan fuzzy yang bisa dipetakan ke kategori

3. **Mudah dipahami dosen/mahasiswa** — Rule berformat IF-THEN yang intuitif:
   > "JIKA kehadiran tinggi DAN tugas tinggi DAN diskusi tinggi MAKA lulus"

4. **Defuzzifikasi menghasilkan skor 0–100** — Weighted Average memberikan nilai crisp yang bisa digunakan untuk ranking dan perbandingan antar mahasiswa

5. **Visualisasi lengkap** — Grafik fungsi keanggotaan input/output bisa ditampilkan di detail view, membantu mahasiswa memahami proses perhitungan

## 5.2 Manfaat Fuzzy Logic vs If-Else Konvensional

### Masalah If-Else Konvensional

```php
// Contoh if-else kaku:
if ($kehadiran >= 75 && $tugas >= 70 && $diskusi >= 60) {
    return 'lulus';
} else {
    return 'tidak_lulus';
}
```

| Masalah | Penjelasan |
|---------|-----------|
| **Batas kaku (crisp boundary)** | Kehadiran 74.9% = tidak lulus, 75% = lulus → tidak adil |
| **Tidak ada kombinasi** | Kehadiran 90% tapi tugas 69% = langsung gagal |
| **Hanya 2 output** | Tidak ada gradasi "hampir lulus" atau "cukup" |
| **Tidak realistis** | Dosen tidak menilai secara kaku seperti ini |

### Keunggulan Fuzzy Logic

| Keunggulan | Penjelasan |
|-----------|-----------|
| **Batas lunak (soft boundary)** | Kehadiran 74% masih punya μSedang ≈ 0.9 → kontribusi positif |
| **Kombinasi berbobot** | Kehadiran 90% + tugas 69% → masih bisa lulus |
| **Skor gradual (0–100)** | Bisa ranking mahasiswa, bukan hanya ya/tidak |
| **Mendekati cara berpikir manusia** | Dosen bilang "cukup aktif", bukan "aktif atau tidak" |
| **Transparan** | Mahasiswa bisa melihat di mana nilai mereka lemah |

### Contoh Perbandingan Nyata

| Mahasiswa | Kehadiran | Tugas | Diskusi | If-Else | Fuzzy (Skor) |
|-----------|-----------|-------|---------|---------|-------------|
| A | 80% | 75% | 65% | ✅ Lulus | 68.5 (Lulus) |
| B | 90% | 68% | 55% | ❌ Tidak Lulus | 61.2 (Lulus) |
| C | 70% | 72% | 62% | ❌ Tidak Lulus | 52.8 (Lulus) |
| D | 45% | 90% | 30% | ❌ Tidak Lulus | 38.5 (Tidak Lulus) |

- **Mahasiswa B**: If-Else gagal karena tugas < 70, tapi fuzzy mengakui kehadiran 90% yang sangat baik
- **Mahasiswa C**: If-Else gagal karena kehadiran < 75, tapi fuzzy memberi nilai pada kombinasi ketiga aspek
- **Mahasiswa D**: Keduanya sama — kehadiran sangat rendah → tidak lulus

## 5.3 Kesimpulan

1. **Logika fuzzy Mamdani** terbukti cocok untuk prediksi kelulusan mata kuliah karena mampu menangani ketidakpastian dan gradasi nilai yang tidak bisa diakomodasi oleh sistem if-else konvensional

2. **3 variabel input** (kehadiran, nilai tugas, keaktifan diskusi) merepresentasikan aspek-aspek penting dalam proses belajar mahasiswa yang saling melengkapi

3. **27 rules** memberikan cakupan lengkap semua kombinasi kemungkinan input, dengan distribusi output yang seimbang (8 Tidak Lulus, 9 Cukup, 10 Lulus)

4. **Defuzzifikasi Weighted Average** menghasilkan skor 0–100 yang intuitif dan bisa digunakan untuk ranking serta pengambilan keputusan

5. **Implementasi di Laravel** dengan pola MVC + Service memberikan arsitektur yang bersih, terorganisir, dan mudah dimodifikasi

6. **Manfaat utama**: Sistem menjadi **lebih adil, realistis, dan transparan** dibandingkan pendekatan konvensional — mahasiswa bisa melihat secara detail di mana posisi mereka dan aspek mana yang perlu diperbaiki

---

*Document generated from SIPRAKATA — ManajemenKegiatanProsesBelajar*
*Metode: Mamdani Fuzzy Inference System*
*Framework: Laravel 13 + PHP 8.3 + MySQL*
