<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Tutorial Pengisian KPI') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="prose dark:prose-invert max-w-none">
                        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Panduan Pengisian KPI</h2>
                        
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h3 class="text-lg font-semibold mb-2 text-blue-900 dark:text-blue-200">📋 Untuk Karyawan</h3>
                            <p class="text-sm text-blue-800 dark:text-blue-300 mb-4">
                                Ikuti langkah-langkah berikut untuk melakukan self-assessment KPI Anda.
                            </p>
                        </div>

                        <div class="space-y-6">
                            <!-- Step 1 -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Langkah 1: Akses Halaman Self Assessment</h3>
                                <p class="text-gray-700 dark:text-gray-300 mb-2">
                                    Setelah admin membuka Review Cycle, Anda akan melihat notifikasi atau bisa mengakses langsung melalui menu Performance.
                                </p>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded mt-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Catatan:</strong> Pastikan Review Cycle sudah dalam status "Open" sebelum mulai mengisi.
                                    </p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Langkah 2: Baca dan Pahami Setiap KPI</h3>
                                <p class="text-gray-700 dark:text-gray-300 mb-2">
                                    Untuk setiap KPI yang di-assign kepada Anda:
                                </p>
                                <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300 ml-4">
                                    <li>Baca <strong>deskripsi KPI</strong> dengan teliti</li>
                                    <li>Perhatikan <strong>target</strong> yang harus dicapai</li>
                                    <li>Pahami <strong>unit pengukuran</strong> (%, jumlah, hari, rupiah, dll)</li>
                                </ul>
                            </div>

                            <!-- Step 3 -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Langkah 3: Isi Self Score (0-100)</h3>
                                <p class="text-gray-700 dark:text-gray-300 mb-2">
                                    Berikan penilaian untuk pencapaian KPI Anda dengan skala 0-100:
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded border border-red-200 dark:border-red-800">
                                        <p class="text-sm font-semibold text-red-900 dark:text-red-200">0-40</p>
                                        <p class="text-xs text-red-700 dark:text-red-300">Belum mencapai target atau sangat kurang</p>
                                    </div>
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded border border-yellow-200 dark:border-yellow-800">
                                        <p class="text-sm font-semibold text-yellow-900 dark:text-yellow-200">41-60</p>
                                        <p class="text-xs text-yellow-700 dark:text-yellow-300">Mencapai sebagian target</p>
                                    </div>
                                    <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded border border-green-200 dark:border-green-800">
                                        <p class="text-sm font-semibold text-green-900 dark:text-green-200">61-80</p>
                                        <p class="text-xs text-green-700 dark:text-green-300">Mencapai target dengan baik</p>
                                    </div>
                                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded border border-blue-200 dark:border-blue-800">
                                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-200">81-100</p>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">Melebihi target atau sangat baik</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Langkah 4: Isi Comment/Deskripsi</h3>
                                <p class="text-gray-700 dark:text-gray-300 mb-2">
                                    Jelaskan pencapaian Anda dengan detail:
                                </p>
                                <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300 ml-4">
                                    <li>Apa yang telah dicapai?</li>
                                    <li>Berapa nilai/angka pencapaiannya?</li>
                                    <li>Apakah ada bukti atau dokumen pendukung?</li>
                                    <li>Jika belum mencapai target, apa kendalanya?</li>
                                </ul>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded mt-3">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Contoh Comment yang Baik:</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                                        "Berhasil mencapai penjualan Rp 120.000.000 pada bulan ini, melebihi target Rp 100.000.000 sebesar 20%. 
                                        Pencapaian ini didukung oleh strategi promosi yang efektif dan peningkatan jumlah klien baru. 
                                        Bukti: Laporan penjualan bulanan terlampir."
                                    </p>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Langkah 5: Save atau Submit</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded border border-yellow-200 dark:border-yellow-800">
                                        <h4 class="font-semibold text-yellow-900 dark:text-yellow-200 mb-2">💾 Save Draft</h4>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                            Gunakan untuk menyimpan sementara. Anda masih bisa mengedit kembali sebelum submit.
                                        </p>
                                    </div>
                                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded border border-green-200 dark:border-green-800">
                                        <h4 class="font-semibold text-green-900 dark:text-green-200 mb-2">✅ Submit</h4>
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            Kirim final assessment. Setelah submit, tidak bisa di-edit lagi. Manager akan melakukan review.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tips -->
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg border border-indigo-200 dark:border-indigo-800 mt-6">
                                <h3 class="text-lg font-semibold mb-3 text-indigo-900 dark:text-indigo-200">💡 Tips & Best Practices</h3>
                                <ul class="space-y-2 text-sm text-indigo-800 dark:text-indigo-300">
                                    <li class="flex items-start">
                                        <span class="mr-2">✓</span>
                                        <span><strong>Isi dengan jujur</strong> - Self-assessment adalah refleksi diri yang jujur</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="mr-2">✓</span>
                                        <span><strong>Sertakan bukti</strong> - Jelaskan pencapaian dengan data dan bukti konkret</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="mr-2">✓</span>
                                        <span><strong>Submit tepat waktu</strong> - Jangan menunda hingga deadline</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="mr-2">✓</span>
                                        <span><strong>Gunakan Save Draft</strong> - Simpan progress secara berkala untuk menghindari kehilangan data</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="mr-2">✓</span>
                                        <span><strong>Review sebelum submit</strong> - Pastikan semua KPI sudah diisi dengan benar</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- FAQ -->
                            <div class="mt-6">
                                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">❓ Frequently Asked Questions</h3>
                                <div class="space-y-4">
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Q: Apakah saya bisa mengedit setelah submit?</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">A: Tidak, setelah submit tidak bisa di-edit. Gunakan "Save Draft" jika masih ingin mengubah.</p>
                                    </div>
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Q: Bagaimana jika saya tidak yakin dengan score yang diberikan?</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">A: Gunakan "Save Draft" terlebih dahulu, diskusikan dengan manager, baru submit setelah yakin.</p>
                                    </div>
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Q: Apakah semua KPI wajib diisi?</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">A: Ya, semua KPI yang di-assign harus diisi untuk mendapatkan overall score yang akurat.</p>
                                    </div>
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Q: Kapan saya bisa melihat hasil assessment?</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">A: Setelah manager dan peer reviewer (jika ada) selesai memberikan penilaian, hasil akan tersedia di halaman Results.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-center">
                            <x-button href="{{ route('home') }}" class="w-full sm:w-auto">
                                {{ __('Kembali ke Dashboard') }}
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

