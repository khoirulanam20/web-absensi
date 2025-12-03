<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('PRD Generator') }}
    </h2>
  </x-slot>

  <div class="py-4 sm:py-6 md:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
        <div class="border-b border-gray-100 px-4 pb-4 pt-4 sm:px-6 sm:pt-6 dark:border-gray-700">
          <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 sm:text-2xl">
            PRD Generator Pro
          </h1>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            Isi formulir berikut untuk menyusun dokumen Product Requirements Document (PRD) secara otomatis.
          </p>
        </div>

        <div class="px-4 py-6 sm:px-6 sm:py-8">
          <form id="prdForm" class="space-y-6">
            <div id="sectionContainer" class="space-y-4">
              <!-- 1. Informasi Umum Produk -->
              <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm dark:border-blue-900/40 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section1Content" onclick="toggleSection(this)">
                  <span>🧩 1. Informasi Umum Produk</span>
                  <span class="transform transition-transform duration-300 rotate-90" data-arrow>&#9654;</span>
                </button>

                <div id="section1Content" class="space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Judul / Nama Proyek</span>
                    <input type="text" id="title"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Sistem Manajemen Aset Digital">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Deskripsi Singkat Produk</span>
                    <textarea id="short_description"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Aplikasi web untuk melacak dan mengelola aset digital perusahaan."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Tujuan Utama Produk</span>
                    <textarea id="objective"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Meningkatkan efisiensi 30% dalam inventarisasi aset dan mengurangi risiko kehilangan data."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Latar Belakang / Masalah yang
                      Ingin Diselesaikan</span>
                    <textarea id="problem"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Proses inventarisasi aset saat ini masih manual menggunakan spreadsheet, rawan kesalahan, dan memakan waktu."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Stakeholder (klien, user, tim
                      developer, dll)</span>
                    <textarea id="stakeholder"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Klien: PT Maju Bersama; User: Tim IT dan Karyawan; Developer: Tim Alpha."></textarea>
                  </label>
                </div>
              </div>

              <!-- 2. Target Pengguna -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section2Content" onclick="toggleSection(this)">
                  <span>👥 2. Target Pengguna (Persona & Kebutuhan)</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section2Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Segmentasi User</span>
                    <textarea id="segmentation"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="1. Admin (Mengelola data master); 2. User Umum (Melakukan peminjaman aset); 3. Staff IT (Audit aset)."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Profil Pengguna (Persona
                      Singkat)</span>
                    <textarea id="persona"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Nama: Budi, Jabatan: Staff IT. Butuh melacak aset dengan cepat dan akurat. Mahir menggunakan aplikasi web."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Kebutuhan Utama User</span>
                    <textarea id="user_needs"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Melihat status aset secara real-time; Notifikasi pengembalian aset; Laporan periodik."></textarea>
                  </label>
                </div>
              </div>

              <!-- 3. Fitur dan Fungsionalitas -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section3Content" onclick="toggleSection(this)">
                  <span>⚙️ 3. Fitur dan Fungsionalitas (Functional Requirements)</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section3Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Daftar Fitur Utama &
                      Deskripsi</span>
                    <textarea id="features"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="4"
                      placeholder="- CRUD Aset: Mengelola data aset (nama, lokasi, status, dll).
- Modul Peminjaman: User dapat mengajukan pinjaman dan Admin menyetujui/menolak.
- Laporan Aset: Menghasilkan laporan PDF/Excel tentang inventaris."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Prioritas Fitur (Must Have,
                      Should Have, Nice to Have)</span>
                    <textarea id="priority"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Must Have: CRUD Aset, Login.
Should Have: Modul Peminjaman, Notifikasi Email.
Nice to Have: Integrasi dengan sistem HRD."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Alur Penggunaan (User
                      Flow)</span>
                    <textarea id="user_flow"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="User -> Login -> Cari Aset -> Ajukan Peminjaman -> Admin Notifikasi -> Admin Setuju -> User Ambil Aset."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Batasan Fitur (Scope
                      Limit)</span>
                    <textarea id="scope_limit"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Aplikasi ini tidak mencakup pengelolaan aset fisik yang memerlukan scan barcode/RFID. Fokus pada aset digital dan inventarisasi sederhana."></textarea>
                  </label>
                </div>
              </div>

              <!-- 4. Struktur Halaman / Modul -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section4Content" onclick="toggleSection(this)">
                  <span>🧱 4. Struktur Halaman / Modul</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section4Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Daftar Halaman Utama dan
                      Sub-halaman</span>
                    <textarea id="sitemap"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="- Dashboard (Ringkasan Aset)
- Aset (Daftar Aset, Detail Aset, Tambah Aset)
- Peminjaman (Daftar Pinjaman, Buat Pinjaman)
- Laporan (Laporan PDF/Excel)"></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Modul-modul Sistem</span>
                    <textarea id="modules"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="1. Modul Autentikasi; 2. Modul Master Aset; 3. Modul Transaksi Peminjaman; 4. Modul Laporan."></textarea>
                  </label>
                </div>
              </div>

              <!-- 5. Spesifikasi Teknis -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section5Content" onclick="toggleSection(this)">
                  <span>🧮 5. Spesifikasi Teknis (Technical Specifications)</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section5Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Platform</span>
                    <input type="text" id="platform"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Web (Responsif)">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Framework / Bahasa
                      Pemrograman</span>
                    <input type="text" id="tech_stack"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Backend: Laravel 10 (PHP); Frontend: React (Next.js); Styling: Tailwind CSS.">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Integrasi Eksternal</span>
                    <textarea id="integrations"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="API Notifikasi Email (SendGrid/Mailgun); LDAP untuk Single Sign-On."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Database dan Kebutuhan
                      Penyimpanan</span>
                    <input type="text" id="database"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="PostgreSQL. Estimasi 500MB dalam 1 tahun pertama.">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Hosting / Deployment
                      Environment</span>
                    <input type="text" id="deployment"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="AWS EC2 (Linux, Nginx) atau Kubernetes.">
                  </label>
                </div>
              </div>

              <!-- 6. Desain & UX -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section6Content" onclick="toggleSection(this)">
                  <span>🎨 6. Desain & UX (Design)</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section6Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Referensi Desain / Style
                      Guide</span>
                    <input type="text" id="design_reference"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Material Design 3 atau internal design system klien.">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Warna Utama &
                      Tipografi</span>
                    <input type="text" id="color_typography"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Warna Utama: Biru #1E40AF. Tipografi: Inter (Sans-serif).">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Wireframe atau Mockup</span>
                    <input type="text" id="wireframe"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Link Figma: https://www.mockupworld.co/">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Responsiveness &
                      Aksesibilitas</span>
                    <input type="text" id="accessibility"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Harus responsif di semua ukuran layar (Mobile-First). Mematuhi standar WCAG 2.1 Level AA (minimal).">
                  </label>
                </div>
              </div>

              <!-- 7. Keamanan & Hak Akses -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section7Content" onclick="toggleSection(this)">
                  <span>🔐 7. Keamanan & Hak Akses (Non-Functional Requirements)</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section7Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Role User dan Hak
                      Aksesnya</span>
                    <textarea id="roles"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Admin: Full CRUD data master, Laporan. User Umum: Hanya Read dan Create transaksi peminjaman."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Data Sensitif yang Harus
                      Diamankan</span>
                    <input type="text" id="sensitive_data"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Nama lengkap, Jabatan, Data Log Aktivitas User (harus dienkripsi/hash).">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Metode Autentikasi</span>
                    <input type="text" id="authentication"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Login username/password dengan enkripsi Bcrypt. Otentikasi dua faktor (2FA) opsional untuk Admin.">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Backup & Recovery Plan</span>
                    <input type="text" id="backup"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Backup database harian otomatis. RPO maksimal 24 jam.">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Persyaratan Non-Fungsional
                      Lain (e.g., Latency, Peak Users)</span>
                    <textarea id="non_functional_requirements"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Latency: Page load <500ms. Peak users: mendukung 100 concurrent users."></textarea>
                  </label>
                </div>
              </div>

              <!-- 8. Konten & Data -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section8Content" onclick="toggleSection(this)">
                  <span>🧾 8. Konten & Data</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section8Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Sumber Konten</span>
                    <input type="text" id="content_source"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Data Aset awal disediakan Klien (via Excel). Konten aplikasi dibuat oleh developer.">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Format Data</span>
                    <input type="text" id="data_format"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Teks, Gambar (JPG/PNG maks 5MB per file), CSV (untuk impor).">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Volume Data (Estimasi)</span>
                    <input type="text" id="data_volume"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="User: 500; Aset: 3000; Transaksi: 100 per hari.">
                  </label>
                </div>
              </div>

              <!-- 9. Timeline & Milestone -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section9Content" onclick="toggleSection(this)">
                  <span>⏱️ 9. Timeline & Milestone</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section9Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Tahapan Pengembangan</span>
                    <textarea id="stages"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="1. Analisis & Desain (2 minggu); 2. Implementasi Core Features (4 minggu); 3. Integrasi & Testing (2 minggu); 4. Deployment & UAT (1 minggu)."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Jadwal Tiap Fase & Estimasi
                      Waktu</span>
                    <textarea id="schedule"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Total: 9 minggu. Milestone: Selesai modul autentikasi (minggu ke-3), fitur utama selesai (minggu ke-7)."></textarea>
                  </label>
                </div>
              </div>

              <!-- 10. Anggaran (Opsional) -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section10Content" onclick="toggleSection(this)">
                  <span>💰 10. Anggaran (Opsional)</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section10Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Estimasi Biaya Total</span>
                    <input type="text" id="budget"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Rp 150.000.000 (termasuk lisensi tools, hosting 1 tahun, dan SDM).">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Biaya Tambahan per Fitur
                      Opsional</span>
                    <input type="text" id="optional_cost"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Integrasi LDAP: +Rp 15.000.000.">
                  </label>
                </div>
              </div>

              <!-- 11. Acceptance Criteria -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section11Content" onclick="toggleSection(this)">
                  <span>🧩 11. Acceptance Criteria</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section11Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Indikator Keberhasilan
                      Fitur</span>
                    <textarea id="success_indicators"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Login: user dapat masuk <2 detik. CRUD aset: operasi dasar berjalan tanpa error."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Kriteria Siap Diuji (Ready for
                      Test)</span>
                    <input type="text" id="ready_for_test"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Semua unit test & integrasi test lulus (coverage >80%). Tidak ada error blocker.">
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Kriteria Selesai (Done
                      Criteria)</span>
                    <input type="text" id="done_criteria"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="UAT disetujui klien, semua fitur Must Have sudah live, dokumentasi teknis lengkap.">
                  </label>
                </div>
              </div>

              <!-- 12. Risiko & Catatan Tambahan -->
              <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <button type="button"
                  class="flex w-full items-center justify-between text-left text-lg font-semibold text-gray-800 dark:text-gray-100"
                  data-target="section12Content" onclick="toggleSection(this)">
                  <span>🧠 12. Risiko & Catatan Tambahan</span>
                  <span class="transform transition-transform duration-300" data-arrow>&#9654;</span>
                </button>

                <div id="section12Content" class="hidden space-y-4 pt-4">
                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Potensi Kendala Teknis atau
                      Non-teknis</span>
                    <textarea id="risks"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Klien terlambat menyediakan data aset. Kendala integrasi LDAP (risiko tinggi)."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Asumsi</span>
                    <textarea id="assumptions"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="3"
                      placeholder="Asumsi: klien memiliki server hosting yang memadai. Asumsi: API email notifikasi tersedia."></textarea>
                  </label>

                  <label class="block">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Catatan Perubahan (Change Log
                      Versi PRD)</span>
                    <textarea id="changelog"
                      class="mt-1 block w-full rounded-md border-gray-300 p-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                      rows="2"
                      placeholder="v1.0 (2025-11-07): Dokumen awal dibuat."></textarea>
                  </label>
                </div>
              </div>
            </div>

            <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
              <button type="button" onclick="downloadPRDAsDocx()"
                class="flex w-full items-center justify-center rounded-full bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition duration-200 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                <x-heroicon-o-arrow-down-tray class="mr-2 h-5 w-5" />
                Download PRD (.docx)
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  {{-- CDN untuk FileSaver dan JSZip khusus halaman ini --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" defer></script>

  <script>
    // Fungsi untuk mengumpulkan data dan menghasilkan string Markdown
    function generatePRDContent() {
      const data = {
        title: document.getElementById('title').value || '[Judul / Nama Proyek]',
        short_description: document.getElementById('short_description').value,
        objective: document.getElementById('objective').value,
        problem: document.getElementById('problem').value,
        stakeholder: document.getElementById('stakeholder').value,
        segmentation: document.getElementById('segmentation').value,
        persona: document.getElementById('persona').value,
        user_needs: document.getElementById('user_needs').value,
        features: document.getElementById('features').value,
        priority: document.getElementById('priority').value,
        user_flow: document.getElementById('user_flow').value,
        scope_limit: document.getElementById('scope_limit').value,
        sitemap: document.getElementById('sitemap').value,
        modules: document.getElementById('modules').value,
        platform: document.getElementById('platform').value,
        tech_stack: document.getElementById('tech_stack').value,
        integrations: document.getElementById('integrations').value,
        database: document.getElementById('database').value,
        deployment: document.getElementById('deployment').value,
        design_reference: document.getElementById('design_reference').value,
        color_typography: document.getElementById('color_typography').value,
        wireframe: document.getElementById('wireframe').value,
        accessibility: document.getElementById('accessibility').value,
        roles: document.getElementById('roles').value,
        sensitive_data: document.getElementById('sensitive_data').value,
        authentication: document.getElementById('authentication').value,
        backup: document.getElementById('backup').value,
        content_source: document.getElementById('content_source').value,
        data_format: document.getElementById('data_format').value,
        data_volume: document.getElementById('data_volume').value,
        stages: document.getElementById('stages').value,
        schedule: document.getElementById('schedule').value,
        budget: document.getElementById('budget').value,
        optional_cost: document.getElementById('optional_cost').value,
        success_indicators: document.getElementById('success_indicators').value,
        ready_for_test: document.getElementById('ready_for_test').value,
        done_criteria: document.getElementById('done_criteria').value,
        risks: document.getElementById('risks').value,
        assumptions: document.getElementById('assumptions').value,
        changelog: document.getElementById('changelog').value,
        non_functional_requirements: document.getElementById('non_functional_requirements').value
      };

      const today = new Date().toISOString().slice(0, 10);

      let mdContent = `# PRD: ${data.title}

## Changelog

${data.changelog || `v1.0 (${today}): Dokumen awal dibuat.`}

## Related Documents

Gunakan bagian ini untuk menautkan ke dokumen terkait lainnya, seperti PRD lain, riset pasar, dll.

${data.wireframe ? `- Wireframe / Mockup: ${data.wireframe}` : ''}
${data.design_reference ? `- Style Guide / Referensi Desain: ${data.design_reference}` : ''}${(!data.wireframe && !data.design_reference) ? `
- Link to Document 1
- Link to Document 2` : ''}

---

## Introduction

### Deskripsi Umum Produk

${data.short_description || '[Deskripsi singkat inisiatif produk]'}

### Latar Belakang / Masalah yang Ingin Diselesaikan

${data.problem || '[Latar belakang dan masalah]'}

### Tujuan Utama Produk

${data.objective || '[Tujuan utama]'}

---

## Customer Needs, Market, and Business Model

### Known Customers and Customer Requests

${data.stakeholder || '[Pelanggan dan pemangku kepentingan utama, seperti Klien dan Tim Pengembang]'}

### Target Pengguna (Personas)

${data.segmentation ? `**Segmentasi User:**
${data.segmentation.split('\n').map(l => `* ${l.trim()}`).join('\n')}` : '-'}

${data.persona ? `**Profil Pengguna Singkat:**
${data.persona}` : '-'}

### Kebutuhan Utama User

${data.user_needs ? data.user_needs.split('\n').map(l => `* ${l.trim()}`).join('\n') : '-'}

### Expected Results

${data.objective || '[Tuliskan di sini dalam beberapa kalimat dan dengan beberapa angka apa yang Anda harapkan saat meluncurkan inisiatif ini.]'}

---

## (Product) Marketing and Communication

### Key Metrics (Acceptance Criteria)

${data.success_indicators ? data.success_indicators.split('\n').map(l => `* **Indikator Keberhasilan:** ${l.trim()}`).join('\n') : '-'}

### Kriteria Selesai (Done Criteria)

${data.done_criteria || '-'}

### Kriteria Siap Diuji (Ready for Test)

${data.ready_for_test || '-'}

---

## Functional Requirements (Fitur & Fungsionalitas)

### Daftar Fitur Utama

${data.features ? data.features.split('\n').map(l => `* ${l.trim()}`).join('\n') : '-'}

### Prioritas Fitur (MoSCoW)

${data.priority ? data.priority.split('\n').map(l => `- ${l.trim()}`).join('\n') : '-'}

### Alur Penggunaan (User Flow)

${data.user_flow || '-'}

### Batasan Fitur (Scope Limit)

${data.scope_limit || '-'}

### Struktur Halaman / Modul

${data.sitemap ? `**Sitemap/Navigasi:**
${data.sitemap.split('\n').map(l => `* ${l.trim()}`).join('\n')}` : '-'}

${data.modules ? `**Modul Sistem:**
${data.modules.split('\n').map(l => `* ${l.trim()}`).join('\n')}` : '-'}

---

## Non-Functional Requirements (Keamanan & Kinerja)

### Keamanan & Hak Akses

* **Role User dan Akses:** ${data.roles ? data.roles.split('\n').map(l => l.trim()).join(' / ') : '-'}
* **Data Sensitif:** ${data.sensitive_data || '-'}
* **Metode Autentikasi:** ${data.authentication || '-'}
* **Backup & Recovery:** ${data.backup || '-'}

### Persyaratan Kinerja dan Lainnya

${data.non_functional_requirements || '[Seperti: dukungan untuk x pengguna puncak, waktu buka halaman dalam y ms, dll.]'}

---

## Design (Desain & UX)

### Aspek Desain Utama

* **Warna & Tipografi:** ${data.color_typography || '-'}
* **Responsiveness & Aksesibilitas:** ${data.accessibility || '-'}

### Referensi Desain

${data.wireframe ? `* Wireframe / Mockup: ${data.wireframe}` : '-'}
${data.design_reference ? `* Style Guide: ${data.design_reference}` : '-'}

---

## Technical Specifications (Spesifikasi Teknis)

### Tech Stack

* **Platform:** ${data.platform || '-'}
* **Framework / Bahasa:** ${data.tech_stack || '-'}
* **Integrasi Eksternal:** ${data.integrations ? data.integrations.split('\n').map(l => l.trim()).join('; ') : '-'}

### Data & Konten

* **Sumber Konten:** ${data.content_source || '-'}
* **Format Data:** ${data.data_format || '-'}
* **Volume Data (Estimasi):** ${data.data_volume || '-'}
* **Database & Penyimpanan:** ${data.database || '-'}

### Deployment

* **Hosting / Lingkungan:** ${data.deployment || '-'}

---

## Timeline & Resources

### Timeline & Milestone

${data.stages ? `**Tahapan Pengembangan:**
${data.stages.split('\n').map(l => `* ${l.trim()}`).join('\n')}` : '-'}

${data.schedule ? `**Jadwal Fase & Estimasi:**
${data.schedule}` : '-'}

### Anggaran

* **Estimasi Biaya Total:** ${data.budget || '-'}
* **Biaya Tambahan Opsional:** ${data.optional_cost || '-'}

### Risiko & Asumsi

* **Potensi Kendala (Risiko):** ${data.risks ? data.risks.split('\n').map(l => `* ${l.trim()}`).join('\n') : '-'}
* **Asumsi:** ${data.assumptions ? data.assumptions.split('\n').map(l => `* ${l.trim()}`).join('\n') : '-'}
`;

      return mdContent;
    }

    async function downloadPRDAsDocx() {
      try {
        const mdContent = generatePRDContent();
        const projectName = document.getElementById('title').value || 'PRD_Dokumen_Baru';
        const filename = projectName.replace(/[^a-z0-9]/gi, '_').toLowerCase() +
          `_${new Date().toISOString().slice(0, 10)}.docx`;

        const result = await generateDocxFromMarkdown(mdContent, filename);
        if (result && result.success) {
          showTemporaryMessage(`File PRD (${filename}) berhasil diunduh dalam format .docx!`, 'bg-green-600');
        } else {
          showTemporaryMessage(`File PRD (${filename}) berhasil diunduh dalam format .docx!`, 'bg-green-600');
        }
      } catch (error) {
        console.error('Error generating DOCX:', error);
        showTemporaryMessage('Gagal mengunduh .docx: ' + error.message, 'bg-red-600');
      }
    }

    // Collapse / expand section
    function toggleSection(button) {
      const targetId = button.dataset.target;
      if (!targetId) return;
      const content = document.getElementById(targetId);
      if (!content) return;
      const arrow = button.querySelector('[data-arrow]');

      const isHidden = content.classList.contains('hidden');
      if (isHidden) {
        content.classList.remove('hidden');
        if (arrow) arrow.classList.add('rotate-90');
      } else {
        content.classList.add('hidden');
        if (arrow) arrow.classList.remove('rotate-90');
      }
    }

    // Toast sederhana
    function showTemporaryMessage(message, bgClass) {
      let msgBox = document.getElementById('tempMessage');
      if (!msgBox) {
        msgBox = document.createElement('div');
        msgBox.id = 'tempMessage';
        msgBox.className =
          'fixed bottom-4 right-4 z-50 rounded-lg px-4 py-3 text-xs font-semibold text-white shadow-xl transition-opacity duration-500 opacity-0';
        document.body.appendChild(msgBox);
      }

      msgBox.textContent = message;
      msgBox.className =
        `fixed bottom-4 right-4 z-50 rounded-lg px-4 py-3 text-xs font-semibold text-white shadow-xl transition-opacity duration-500 ${bgClass} opacity-100`;

      setTimeout(() => {
        msgBox.classList.add('opacity-0');
      }, 4000);
    }

    // Konversi markdown ke Word XML (disederhanakan, sama seperti snippet awal)
    function escapeHtml(text) {
      const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
      return text.replace(/[&<>"']/g, m => map[m]);
    }

    function escapeXml(text) {
      const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&apos;' };
      return text.replace(/[&<>"']/g, m => map[m]);
    }

    function parseInlineFormattingXML(text) {
      let xml = '';
      const boldRegex = /\*\*(.*?)\*\*/g;
      let lastIndex = 0;
      let match;

      while ((match = boldRegex.exec(text)) !== null) {
        if (match.index > lastIndex) {
          xml += `<w:r><w:t>${escapeXml(text.substring(lastIndex, match.index))}</w:t></w:r>`;
        }
        xml += `<w:r><w:rPr><w:b/></w:rPr><w:t>${escapeXml(match[1])}</w:t></w:r>`;
        lastIndex = match.index + match[0].length;
      }

      if (lastIndex < text.length) {
        xml += `<w:r><w:t>${escapeXml(text.substring(lastIndex))}</w:t></w:r>`;
      }

      if (!xml) {
        xml = `<w:r><w:t>${escapeXml(text)}</w:t></w:r>`;
      }

      return xml;
    }

    function markdownToWordXML(mdContent) {
      const lines = mdContent.split('\n');
      let xml = '';
      let inList = false;

      for (let i = 0; i < lines.length; i++) {
        const line = lines[i].trim();

        if (!line) {
          if (inList) inList = false;
          xml += '<w:p><w:r><w:t></w:t></w:r></w:p>';
          continue;
        }

        if (line.startsWith('# ')) {
          if (inList) inList = false;
          const text = escapeXml(line.substring(2));
          xml += `<w:p><w:pPr><w:pStyle w:val="Heading1"/></w:pPr><w:r><w:t>${text}</w:t></w:r></w:p>`;
        } else if (line.startsWith('## ')) {
          if (inList) inList = false;
          const text = escapeXml(line.substring(3));
          xml += `<w:p><w:pPr><w:pStyle w:val="Heading2"/></w:pPr><w:r><w:t>${text}</w:t></w:r></w:p>`;
        } else if (line.startsWith('### ')) {
          if (inList) inList = false;
          const text = escapeXml(line.substring(4));
          xml += `<w:p><w:pPr><w:pStyle w:val="Heading3"/></w:pPr><w:r><w:t>${text}</w:t></w:r></w:p>`;
        } else if (line.startsWith('---')) {
          if (inList) inList = false;
          xml += '<w:p><w:pPr><w:pBdr><w:bottom w:val="single" w:sz="6" w:space="1" w:color="000000"/></w:pBdr></w:pPr><w:r><w:t></w:t></w:r></w:p>';
        } else if (line.startsWith('- ') || line.startsWith('* ')) {
          const text = parseInlineFormattingXML(line.substring(2));
          xml += `<w:p><w:pPr><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr></w:pPr>${text}</w:p>`;
          inList = true;
        } else {
          if (inList) inList = false;
          const text = parseInlineFormattingXML(line);
          xml += `<w:p>${text}</w:p>`;
        }
      }

      return xml;
    }

    async function generateDocxFromMarkdown(mdContent, filename) {
      try {
        if (typeof JSZip === 'undefined') {
          throw new Error('Library JSZip tidak tersedia. Pastikan library sudah dimuat.');
        }

        const wordXML = markdownToWordXML(mdContent);
        const zip = new JSZip();

        const contentTypesXml =
          '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' +
          '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' +
          '<Default Extension="xml" ContentType="application/xml"/>' +
          '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>' +
          '<Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>' +
          '<Override PartName="/word/numbering.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.numbering+xml"/>' +
          '</Types>';

        zip.file('[Content_Types].xml', contentTypesXml);

        const relsXml =
          '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
          '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>' +
          '</Relationships>';

        zip.folder('_rels').file('.rels', relsXml);

        const documentXml =
          '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' +
          '<w:body>' +
          wordXML +
          '</w:body>' +
          '</w:document>';

        zip.folder('word').file('document.xml', documentXml);

        const stylesXml =
          '<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">' +
          '<w:style w:type="paragraph" w:styleId="Heading1">' +
          '<w:name w:val="heading 1"/>' +
          '<w:basedOn w:val="Normal"/>' +
          '<w:next w:val="Normal"/>' +
          '<w:qFormat/>' +
          '<w:pPr><w:keepNext/><w:spacing w:before="480" w:after="0"/><w:outlineLvl w:val="0"/></w:pPr>' +
          '<w:rPr><w:b/><w:sz w:val="32"/></w:rPr>' +
          '</w:style>' +
          '<w:style w:type="paragraph" w:styleId="Heading2">' +
          '<w:name w:val="heading 2"/>' +
          '<w:basedOn w:val="Normal"/>' +
          '<w:next w:val="Normal"/>' +
          '<w:qFormat/>' +
          '<w:pPr><w:keepNext/><w:spacing w:before="240" w:after="0"/><w:outlineLvl w:val="1"/></w:pPr>' +
          '<w:rPr><w:b/><w:sz w:val="28"/></w:rPr>' +
          '</w:style>' +
          '<w:style w:type="paragraph" w:styleId="Heading3">' +
          '<w:name w:val="heading 3"/>' +
          '<w:basedOn w:val="Normal"/>' +
          '<w:next w:val="Normal"/>' +
          '<w:qFormat/>' +
          '<w:pPr><w:keepNext/><w:spacing w:before="240" w:after="0"/><w:outlineLvl w:val="2"/></w:pPr>' +
          '<w:rPr><w:b/><w:sz w:val="24"/></w:rPr>' +
          '</w:style>' +
          '</w:styles>';

        zip.folder('word').file('styles.xml', stylesXml);

        const numberingXml =
          '<w:numbering xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">' +
          '<w:abstractNum w:abstractNumId="0">' +
          '<w:multiLevelType w:val="hybridMultilevel"/>' +
          '<w:lvl w:ilvl="0">' +
          '<w:start w:val="1"/>' +
          '<w:numFmt w:val="bullet"/>' +
          '<w:lvlText w:val="•"/>' +
          '</w:lvl>' +
          '</w:abstractNum>' +
          '<w:num w:numId="1">' +
          '<w:abstractNumId w:val="0"/>' +
          '</w:num>' +
          '</w:numbering>';

        zip.folder('word').file('numbering.xml', numberingXml);

        const docRelsXml =
          '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
          '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>' +
          '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/numbering" Target="numbering.xml"/>' +
          '</Relationships>';

        zip.folder('word').folder('_rels').file('document.xml.rels', docRelsXml);

        const blob = await zip.generateAsync({
          type: 'blob',
          mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        });

        if (typeof saveAs !== 'undefined') {
          saveAs(blob, filename);
        } else {
          const a = document.createElement('a');
          a.href = URL.createObjectURL(blob);
          a.download = filename;
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          URL.revokeObjectURL(a.href);
        }

        return { success: true };
      } catch (error) {
        console.error('Error generating DOCX:', error);
        throw error;
      }
    }

    // Saat load, collapse semua section kecuali pertama
    document.addEventListener('DOMContentLoaded', () => {
      const sections = document.querySelectorAll('#sectionContainer > div');
      sections.forEach((section, index) => {
        if (index === 0) return;
        const button = section.querySelector('button');
        const arrow = button.querySelector('[data-arrow]');
        const targetId = button.dataset.target;
        const content = targetId ? document.getElementById(targetId) : null;
        if (!content) return;
        content.classList.add('hidden');
        if (arrow) arrow.classList.remove('rotate-90');
      });
    });
  </script>
</x-app-layout>


