Tentu, berikut adalah **Product Requirements Document (PRD)** spesifik untuk pengembangan modul **Payroll (Penggajian)**. Dokumen ini dirancang agar terintegrasi dengan sistem absensi baru yang sudah Anda rencanakan sebelumnya.

Silakan simpan kode di bawah ini sebagai file `.md` (misalnya: `PRD_PAYROLL_MODULE.md`).

-----

````markdown
# Product Requirements Document (PRD): Payroll Management Module

**Module Name:** Payroll System (Penggajian & Kompensasi)
**Parent Project:** Web Absensi / HRM System
**Version:** 1.0
**Status:** Planning
**Language:** Indonesia

---

## 1. Pendahuluan
Modul Payroll bertujuan untuk mengotomatisasi perhitungan gaji karyawan berdasarkan kehadiran (dari modul absensi), komponen tetap (gaji pokok), dan komponen variabel (tunjangan harian/insentif). Output akhirnya adalah Laporan Gaji Bulanan dan Slip Gaji (Payslip) digital untuk karyawan.

## 2. Fitur Utama & Alur Kerja

### A. Master Data Komponen Gaji (Salary Components)
Admin dapat membuat berbagai jenis komponen gaji secara dinamis.
* **Tipe Komponen:**
    1.  **Penerimaan (Earnings):** Gaji Pokok, Tunjangan Jabatan, Tunjangan Transport, Bonus, THR.
    2.  **Potongan (Deductions):** BPJS, PPh 21, Kasbon, Potongan Keterlambatan.
* **Sifat Hitungan:**
    1.  **Fixed (Tetap):** Nilai tetap setiap bulan (contoh: Gaji Pokok, Tunjangan Jabatan).
    2.  **Daily (Harian/Kehadiran):** Dikalikan jumlah kehadiran (contoh: Uang Makan = Rp 50.000 x 20 Hari Kerja).
    3.  **One-time (Insidental):** Input manual saat generate gaji (contoh: Bonus Project, Rapel).

### B. Setting Gaji Karyawan (Salary Setup)
Admin mengatur nominal spesifik untuk setiap karyawan.
* Setiap karyawan bisa memiliki komponen yang berbeda.
* **Contoh:**
    * User A (Manager): Gaji Pokok 10jt, Tunjangan Jabatan 2jt, BPJS 200rb.
    * User B (Staff): Gaji Pokok 5jt, Uang Makan 50rb/hari.

### C. Proses Generate Payroll (Bulanan)
Fitur untuk memproses gaji masal per periode.
* Admin memilih Periode (misal: Oktober 2025).
* Sistem otomatis menarik data jumlah kehadiran dari tabel `attendances` (Status: Hadir).
* Sistem menghitung: `(Komponen Tetap) + (Komponen Harian x Jumlah Hadir) - (Potongan)`.
* Admin bisa mengedit manual jika ada penyesuaian (adjustment) sebelum finalisasi.

### D. Slip Gaji (Payslip)
* **Admin:** Bisa cetak PDF masal atau per orang.
* **Karyawan:** Bisa melihat dan download Slip Gaji sendiri di dashboard user (Menu "Riwayat Gaji").

---

## 3. Spesifikasi Teknis & Database

### 3.1. Database Schema

Berikut adalah rancangan tabel baru yang perlu ditambahkan:

#### 1. Tabel `salary_components`
Menyimpan master nama-nama tunjangan/potongan.
```php
Schema::create('salary_components', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // Contoh: Tunjangan Transport
    $table->enum('type', ['earning', 'deduction']); // Penerimaan atau Potongan
    $table->boolean('is_daily')->default(false); // Jika true, dikalikan jumlah kehadiran
    $table->boolean('is_taxable')->default(false); // Opsional (untuk PPh21)
    $table->timestamps();
});
````

#### 2\. Tabel `employee_salaries`

Menyimpan nominal gaji per karyawan (Relasi User \<-\> Component).

```php
Schema::create('employee_salaries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('salary_component_id')->constrained()->cascadeOnDelete();
    $table->decimal('amount', 15, 2); // Nominal (misal: 5000000)
    $table->timestamps();
});
```

#### 3\. Tabel `payrolls`

Header transaksi penggajian bulanan.

```php
Schema::create('payrolls', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('period'); // Format: "2025-10"
    $table->date('payment_date');
    $table->integer('total_attendance')->default(0); // Jumlah hari hadir
    $table->decimal('basic_salary', 15, 2); // Gaji Pokok (Snapshot)
    $table->decimal('total_allowance', 15, 2); // Total Tunjangan
    $table->decimal('total_deduction', 15, 2); // Total Potongan
    $table->decimal('net_salary', 15, 2); // Gaji Bersih (Take Home Pay)
    $table->enum('status', ['draft', 'published', 'paid'])->default('draft');
    $table->timestamps();
});
```

#### 4\. Tabel `payroll_details` (Detail Item di Slip Gaji)

Menyimpan rincian agar history slip gaji tidak berubah meski master data berubah.

```php
Schema::create('payroll_details', function (Blueprint $table) {
    $table->id();
    $table->foreignId('payroll_id')->constrained()->cascadeOnDelete();
    $table->string('component_name'); // Snapshot nama komponen
    $table->enum('type', ['earning', 'deduction']);
    $table->decimal('amount', 15, 2);
    $table->timestamps();
});
```

-----

## 4\. User Interface (Mockup Requirements)

### Halaman 1: Master Komponen (Admin)

  * **Tabel:** Nama Komponen | Tipe (Tunjangan/Potongan) | Hitungan (Tetap/Harian).
  * **Tombol:** Tambah Komponen Baru.

### Halaman 2: Setting Gaji Karyawan (Admin)

  * **View:** List Karyawan.
  * **Action:** Klik tombol "Set Gaji" pada karyawan tertentu.
  * **Modal/Page:**
      * Input: Gaji Pokok (Field Khusus).
      * Repeater: Tambah Komponen Lain (Pilih dari Master Data -\> Input Nominal).
      * *Contoh Input:* "Uang Makan" -\> "50000".

### Halaman 3: Generate Payroll (Admin)

  * **Filter:** Pilih Bulan & Tahun.
  * **Tombol:** "Generate Payroll".
  * **Logic:** Sistem looping semua karyawan -\> Cek jumlah absen di tabel `attendances` -\> Hitung total -\> Simpan ke tabel `payrolls` status 'draft'.
  * **Review:** Admin melihat tabel draft gaji. Bisa edit manual jika ada bonus dadakan.
  * **Finalize:** Tombol "Publish & Kirim Slip Gaji".

### Halaman 4: Slip Gaji (User View)

  * Tampilan mirip struk resmi/kertas A4.
  * **Header:** Logo Perusahaan, Periode Gaji.
  * **Kiri:** Data Penerimaan (Gaji Pokok, Tunjangan A, Tunjangan B).
  * **Kanan:** Data Potongan (BPJS, PPh21).
  * **Bottom:** **Total Gaji Bersih (Net Salary)**.
  * **Tombol:** "Download PDF".

-----

## 5\. Timeline Pengembangan (Estimasi)

| Tahap | Task | Estimasi Waktu |
| :--- | :--- | :--- |
| **1** | Migrasi Database & Model (Salary Tables) | 2 Hari |
| **2** | CRUD Master Komponen & Setting User | 3 Hari |
| **3** | Logic Kalkulasi (Integrasi Absensi) | 4 Hari |
| **4** | Pembuatan UI Slip Gaji (PDF View) | 2 Hari |
| **5** | Testing & Validasi Hitungan | 2 Hari |

-----

## 6\. Logic Perhitungan (Contoh Kasus)

**Skenario:**

  * **Karyawan:** Budi
  * **Gaji Pokok:** Rp 4.000.000 (Tetap)
  * **Tunjangan Transport:** Rp 20.000 (Harian)
  * **Potongan BPJS:** Rp 100.000 (Tetap)
  * **Kehadiran Bulan Ini:** 20 Hari (Data dari modul Absensi)

**Rumus Sistem:**

1.  **Earnings:**
      * Gapok: 4.000.000
      * Transport: 20.000 \* 20 Hari = 400.000
      * *Subtotal Earning: 4.400.000*
2.  **Deductions:**
      * BPJS: 100.000
      * *Subtotal Deduction: 100.000*
3.  **Net Salary:**
      * 4.400.000 - 100.000 = **Rp 4.300.000**

<!-- end list -->

```
```