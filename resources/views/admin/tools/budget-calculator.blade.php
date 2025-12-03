<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Budget Calculator') }}
    </h2>
  </x-slot>

  <div class="py-4 sm:py-6 md:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
        <div class="border-b border-gray-100 px-4 pb-4 pt-4 sm:px-6 sm:pt-6 dark:border-gray-700">
          <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 sm:text-2xl">
            Kalkulator Harga Proyek Custom
          </h1>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            Hitung biaya pokok dan harga jual jasa website Laravel Anda secara cepat dan terstruktur.
          </p>
        </div>

        <main class="grid gap-6 px-4 py-6 sm:px-6 sm:py-8 lg:grid-cols-3 lg:gap-8">
          <!-- Kolom Input Estimasi dan Biaya -->
          <div class="space-y-6 lg:col-span-2">
            <div>
              <h2 class="mb-3 border-b pb-2 text-lg font-semibold text-gray-800 dark:text-gray-100 sm:text-xl">
                1. Input Biaya dan Estimasi Kerja
              </h2>

              <!-- Input Rate Tim (Dipisah) -->
              <div class="mb-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-800/60 sm:mb-6">
                <h3 class="mb-3 font-medium text-gray-700 dark:text-gray-200">Rate Hourly/Jam Tim</h3>
                <div class="grid gap-4 sm:grid-cols-2">
                  <div>
                    <label for="devRate" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                      Rate Developer (Rata-rata)
                    </label>
                    <div class="flex items-center space-x-2">
                      <span class="font-bold text-gray-600 dark:text-gray-300">Rp</span>
                      <input type="number" id="devRate" value="100000"
                        class="w-full rounded-lg border border-gray-300 p-3 text-lg text-gray-900 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/60 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        placeholder="Rate per jam (contoh: 100000)" oninput="calculatePrice()">
                    </div>
                  </div>
                  <div>
                    <label for="pmRate" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                      Rate PM/Analyst & QA (Rata-rata)
                    </label>
                    <div class="flex items-center space-x-2">
                      <span class="font-bold text-gray-600 dark:text-gray-300">Rp</span>
                      <input type="number" id="pmRate" value="60000"
                        class="w-full rounded-lg border border-gray-300 p-3 text-lg text-gray-900 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/60 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        placeholder="Rate per jam (contoh: 60000)" oninput="calculatePrice()">
                    </div>
                  </div>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                  Rate harus mencakup Gaji, Tunjangan, dan Overhead Tim.
                </p>
              </div>
            </div>

            <!-- Input Estimasi Jam Kerja -->
            <div>
              <h3 class="mb-3 border-b pb-2 text-lg font-semibold text-gray-800 dark:text-gray-100">
                Estimasi Jam Kerja
              </h3>
              <div class="grid gap-4 sm:grid-cols-2">
                <div>
                  <label for="devHours" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Estimasi Jam <span class="italic">Development</span> (Laravel)
                  </label>
                  <input type="number" id="devHours" value="250"
                    class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/60 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    placeholder="Contoh: 250 jam" oninput="calculatePrice()">
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Pengerjaan fitur inti, <span class="italic">backend</span>, dan <span class="italic">front-end</span>.
                  </p>
                </div>
                <div>
                  <label for="pmHours" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Estimasi Jam PM, Analisis & QA
                  </label>
                  <input type="number" id="pmHours" value="50"
                    class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/60 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    placeholder="Contoh: 50 jam" oninput="calculatePrice()">
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Waktu untuk rapat, perencanaan, <span class="italic">testing</span>, dan <span class="italic">bug
                      fixing</span>.
                  </p>
                </div>
              </div>
            </div>

            <!-- Biaya Non-Labor & Overhead -->
            <div>
              <h3 class="mt-6 mb-3 border-b pb-2 text-lg font-semibold text-gray-800 dark:text-gray-100">
                2. Biaya <span class="italic">Non-Labor</span> & Overhead
              </h3>
              <div class="grid gap-4 sm:grid-cols-2">
                <div>
                  <label for="infrastructureCost" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Biaya Infrastruktur (Domain/Hosting/SSL)
                  </label>
                  <input type="number" id="infrastructureCost" value="2500000"
                    class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/60 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    placeholder="Contoh: 2500000" oninput="calculatePrice()">
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Fee deployment server, SQA.
                  </p>
                </div>
                <div>
                  <label for="overheadCost" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Biaya Overhead Proyek (Tidak Langsung)
                  </label>
                  <input type="number" id="overheadCost" value="1000000"
                    class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 shadow-sm transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/60 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    placeholder="Contoh: 1000000" oninput="calculatePrice()">
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Misal: listrik, akomodasi klien, lisensi, salary operasional, dll.
                  </p>
                </div>
              </div>
            </div>

            <!-- Margin Keuntungan -->
            <div>
              <h3 class="mt-6 mb-3 border-b pb-2 text-lg font-semibold text-gray-800 dark:text-gray-100">
                3. Margin Keuntungan
              </h3>
              <label for="profitMargin" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                Persentase Margin Keuntungan (Gross Profit)
              </label>
              <div class="flex items-center space-x-3">
                <input type="range" id="profitMargin" min="10" max="100" value="40"
                  class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 accent-blue-600"
                  oninput="updateMarginValue(this.value); calculatePrice()">
                <span id="marginValue"
                  class="w-14 text-right text-lg font-semibold text-blue-600 dark:text-blue-400">40%</span>
              </div>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Disarankan 30% - 60% untuk <span class="italic">custom development</span>.
              </p>
            </div>
          </div>

          <!-- Kolom Hasil Perhitungan -->
          <div class="space-y-4 lg:col-span-1">
            <h2 class="mb-3 border-b pb-2 text-lg font-semibold text-gray-800 dark:text-gray-100 sm:text-xl">
              4. Hasil Perhitungan Harga
            </h2>

            <!-- Biaya Tenaga Kerja (Labor Cost) Rincian -->
            <div
              class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/60">
              <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                Total Biaya Tenaga Kerja (Labor)
              </p>
              <p id="resultLaborCost" class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-50">Rp 0</p>
              <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">Rincian:</p>
              <p id="resultDevCost" class="pl-2 text-xs text-gray-600 dark:text-gray-300"></p>
              <p id="resultPmCost" class="pl-2 text-xs text-gray-600 dark:text-gray-300"></p>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Total Jam: <span id="resultTotalHours" class="font-medium">0 Jam</span>
              </p>
            </div>

            <!-- Total Biaya Pokok -->
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-500/40 dark:bg-red-900/20">
              <p class="text-sm text-red-800 dark:text-red-200">Total Biaya Pokok (COGS)</p>
              <p id="resultCOGS" class="mt-1 text-2xl font-bold text-red-800 dark:text-red-200">Rp 0</p>
              <p id="cogsBreakdown" class="mt-1 text-xs text-red-600 dark:text-red-300"></p>
            </div>

            <!-- Margin Profit -->
            <div
              class="rounded-xl border border-green-200 bg-green-50 p-4 dark:border-green-500/40 dark:bg-green-900/20">
              <p class="text-sm text-green-800 dark:text-green-200">
                Margin Keuntungan (<span id="finalMarginPercent">0%</span>)
              </p>
              <p id="resultProfit" class="mt-1 text-2xl font-bold text-green-800 dark:text-green-200">Rp 0</p>
            </div>

            <!-- Harga Jual Final -->
            <div class="mt-4 rounded-xl bg-gray-900 p-4 text-white shadow-lg dark:bg-black">
              <p class="text-base font-medium sm:text-lg">
                Harga Jual Yang Disarankan (Investasi Klien)
              </p>
              <p id="resultFinalPrice"
                class="mt-1 text-3xl font-extrabold text-yellow-400 sm:text-4xl">
                Rp 0
              </p>
            </div>

            <p class="mt-3 text-center text-xs text-gray-500 dark:text-gray-400">
              *Harga Jual Final sebaiknya dibulatkan ke atas. Ini adalah harga minimum agar Anda tidak rugi.
            </p>
          </div>
        </main>

        <footer class="border-t border-gray-100 bg-gray-50 p-4 text-center text-xs text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
          Pastikan rate hourly tim sudah mencakup gaji, tunjangan, dan overhead kantor agar perhitungan akurat.
        </footer>
      </div>
    </div>
  </div>

  <script>
    // Fungsi untuk memformat angka menjadi format Rupiah (IDR)
    const formatRupiah = (angka) => {
      if (isNaN(angka) || angka === null) return 'Rp 0';
      const numberString = String(Math.round(angka));
      const sisa = numberString.length % 3;
      let rupiah = numberString.substr(0, sisa);
      const ribuan = numberString.substr(sisa).match(/\d{3}/g);
      if (ribuan) {
        const separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }
      return 'Rp ' + rupiah;
    };

    // Fungsi untuk memperbarui tampilan persentase margin
    const updateMarginValue = (value) => {
      document.getElementById('marginValue').textContent = `${value}%`;
      document.getElementById('finalMarginPercent').textContent = `${value}%`;
    };

    // Fungsi utama untuk menghitung harga
    const calculatePrice = () => {
      const devRate = parseFloat(document.getElementById('devRate').value) || 0;
      const pmRate = parseFloat(document.getElementById('pmRate').value) || 0;
      const devHours = parseFloat(document.getElementById('devHours').value) || 0;
      const pmHours = parseFloat(document.getElementById('pmHours').value) || 0;
      const infrastructureCost = parseFloat(document.getElementById('infrastructureCost').value) || 0;
      const overheadCost = parseFloat(document.getElementById('overheadCost').value) || 0;
      const profitMargin = (parseFloat(document.getElementById('profitMargin').value) || 0) / 100;

      // 2. Hitung Biaya Tenaga Kerja (Labor Cost)
      const devCost = devHours * devRate;
      const pmCost = pmHours * pmRate;
      const totalLaborCost = devCost + pmCost;

      const resultLaborCostElement = document.getElementById('resultLaborCost');
      if (resultLaborCostElement) resultLaborCostElement.textContent = formatRupiah(totalLaborCost);

      const resultDevCostElement = document.getElementById('resultDevCost');
      if (resultDevCostElement) resultDevCostElement.textContent =
        `Developer (${devHours} jam @${formatRupiah(devRate)}): ${formatRupiah(devCost)}`;

      const resultPmCostElement = document.getElementById('resultPmCost');
      if (resultPmCostElement) resultPmCostElement.textContent =
        `PM/QA (${pmHours} jam @${formatRupiah(pmRate)}): ${formatRupiah(pmCost)}`;

      // 3. Total Jam Kerja
      const totalHours = devHours + pmHours;
      const resultTotalHoursElement = document.getElementById('resultTotalHours');
      if (resultTotalHoursElement) resultTotalHoursElement.textContent = `${totalHours.toFixed(0)} Jam`;

      // 4. Total Biaya Pokok (COGS)
      const totalCOGS = totalLaborCost + infrastructureCost + overheadCost;
      const resultCOGSElement = document.getElementById('resultCOGS');
      if (resultCOGSElement) resultCOGSElement.textContent = formatRupiah(totalCOGS);

      const cogsBreakdownElement = document.getElementById('cogsBreakdown');
      if (cogsBreakdownElement) {
        cogsBreakdownElement.textContent =
          `(Labor: ${formatRupiah(totalLaborCost)} + Non-Labor/Overhead: ${formatRupiah(infrastructureCost + overheadCost)})`;
      }

      // 5. Margin Keuntungan
      const totalProfit = totalCOGS * profitMargin;
      const resultProfitElement = document.getElementById('resultProfit');
      if (resultProfitElement) resultProfitElement.textContent = formatRupiah(totalProfit);

      // 6. Harga Jual Final
      const finalPrice = totalCOGS + totalProfit;
      const resultFinalPriceElement = document.getElementById('resultFinalPrice');
      if (resultFinalPriceElement) resultFinalPriceElement.textContent = formatRupiah(finalPrice);

      updateMarginValue(document.getElementById('profitMargin').value);
    };

    document.addEventListener('DOMContentLoaded', () => {
      updateMarginValue(document.getElementById('profitMargin').value);
      calculatePrice();
    });
  </script>
</x-app-layout>


