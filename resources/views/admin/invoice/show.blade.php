<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Invoice - {{ $invoice->order_number }}
    </h2>
  </x-slot>

  <div class="py-4 sm:py-6 md:py-12">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
        <div class="p-4 sm:p-6 md:p-8">
          <div class="mb-4 flex gap-3">
            <a href="{{ route('admin.invoice.pdf', $invoice->id) }}" download>
              <x-button>
                Download PDF
              </x-button>
            </a>
            <x-secondary-button href="{{ route('admin.invoice.index') }}">
              Kembali
            </x-secondary-button>
          </div>

          <!-- Invoice Preview -->
          <div class="border p-6">
            @include('admin.invoice.pdf', ['invoice' => $invoice, 'logoBase64' => $logoBase64 ?? null])
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

