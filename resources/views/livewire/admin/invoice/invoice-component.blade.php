<div>
  <div class="mb-4 flex items-center justify-between">
    <h3 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Daftar Invoice
    </h3>
    <x-button wire:click="showCreating">
      <x-heroicon-o-plus class="mr-2 h-5 w-5" />
      Buat Invoice Baru
    </x-button>
  </div>

  <div class="mb-4">
    <x-input type="text" class="w-full sm:w-64" wire:model.live="search" placeholder="Cari Order Number atau Nama..." />
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Order Number
          </th>
          <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Recipient
          </th>
          <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Invoice Date
          </th>
          <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Total
          </th>
          <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Payment Status
          </th>
          <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @foreach ($invoices as $invoice)
          <tr wire:key="{{ $invoice->id }}">
            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
              {{ $invoice->order_number }}
            </td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
              {{ $invoice->recipient_name }}
            </td>
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
              {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
            </td>
            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
              Rp {{ number_format((float) $invoice->total, 0, ',', '.') }}
            </td>
            <td class="px-4 py-3 text-sm">
              @php
                $statusColors = [
                  'unpaid' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                  'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                  'partial' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                ];
                $statusLabels = [
                  'unpaid' => 'Unpaid',
                  'paid' => 'Paid',
                  'partial' => 'Partial',
                ];
              @endphp
              <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$invoice->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ $statusLabels[$invoice->payment_status] ?? $invoice->payment_status }}
              </span>
            </td>
            <td class="px-4 py-3 text-right text-sm font-medium">
              <div class="flex items-center justify-end gap-2">
                <x-button href="{{ route('admin.invoice.show', $invoice->id) }}" class="text-xs" target="_blank">
                  View
                </x-button>
                <x-button href="{{ route('admin.invoice.pdf', $invoice->id) }}" class="text-xs" download>
                  PDF
                </x-button>
                <x-secondary-button wire:click="showEditing({{ $invoice->id }})" class="text-xs">
                  Edit
                </x-secondary-button>
                <x-danger-button wire:click="confirmDeletion({{ $invoice->id }}, '{{ $invoice->order_number }}')" class="text-xs">
                  Delete
                </x-danger-button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $invoices->links() }}
  </div>

  <!-- Create/Edit Modal -->
  <x-dialog-modal wire:model="showModal" maxWidth="4xl">
    <x-slot name="title">
      {{ $editingId ? 'Edit Invoice' : 'Buat Invoice Baru' }}
    </x-slot>

    <x-slot name="content">
      <div class="space-y-4">
        <!-- Order Number -->
        <div>
          <x-label for="order_number" value="Order Number" />
          <x-input id="order_number" class="mt-1 block w-full" type="text" wire:model="order_number" />
          @error('order_number')
            <x-input-error for="order_number" class="mt-2" />
          @enderror
        </div>

        <!-- Sender Information -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <h4 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">FROM (Pengirim)</h4>
            <div class="space-y-3">
              <div>
                <x-label for="sender_name" value="Nama" />
                <x-input id="sender_name" class="mt-1 block w-full" type="text" wire:model="sender_name" />
                @error('sender_name')
                  <x-input-error for="sender_name" class="mt-2" />
                @enderror
              </div>
              <div>
                <x-label for="sender_email" value="Email" />
                <x-input id="sender_email" class="mt-1 block w-full" type="email" wire:model="sender_email" />
                @error('sender_email')
                  <x-input-error for="sender_email" class="mt-2" />
                @enderror
              </div>
              <div>
                <x-label for="sender_address" value="Alamat" />
                <x-textarea id="sender_address" class="mt-1 block w-full" wire:model="sender_address" rows="3" />
                @error('sender_address')
                  <x-input-error for="sender_address" class="mt-2" />
                @enderror
              </div>
            </div>
          </div>

          <!-- Recipient Information -->
          <div>
            <h4 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">INVOICE TO (Penerima)</h4>
            <div class="space-y-3">
              <div>
                <x-label for="recipient_name" value="Nama" />
                <x-input id="recipient_name" class="mt-1 block w-full" type="text" wire:model="recipient_name" />
                @error('recipient_name')
                  <x-input-error for="recipient_name" class="mt-2" />
                @enderror
              </div>
              <div>
                <x-label for="recipient_email" value="Email" />
                <x-input id="recipient_email" class="mt-1 block w-full" type="email" wire:model="recipient_email" />
                @error('recipient_email')
                  <x-input-error for="recipient_email" class="mt-2" />
                @enderror
              </div>
              <div>
                <x-label for="recipient_address" value="Alamat" />
                <x-textarea id="recipient_address" class="mt-1 block w-full" wire:model="recipient_address" rows="3" />
                @error('recipient_address')
                  <x-input-error for="recipient_address" class="mt-2" />
                @enderror
              </div>
              <div>
                <x-label for="invoice_date" value="Invoice Date" />
                <x-input id="invoice_date" class="mt-1 block w-full" type="date" wire:model="invoice_date" />
                @error('invoice_date')
                  <x-input-error for="invoice_date" class="mt-2" />
                @enderror
              </div>
            </div>
          </div>
        </div>

        <!-- Items -->
        <div>
          <div class="mb-2 flex items-center justify-between">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Items</h4>
            <x-secondary-button wire:click="addItem" type="button" class="text-xs">
              + Tambah Item
            </x-secondary-button>
          </div>
          <div class="space-y-2">
            @foreach ($items as $index => $item)
              <div class="grid grid-cols-12 gap-2 rounded border p-2">
                <div class="col-span-12 md:col-span-5">
                  <x-input type="text" placeholder="Description" wire:model="items.{{ $index }}.description" />
                  @error('items.' . $index . '.description')
                    <x-input-error for="items.{{ $index }}.description" class="mt-1" />
                  @enderror
                </div>
                <div class="col-span-4 md:col-span-2">
                  <x-input type="number" step="0.01" placeholder="Unit Price" wire:model.live="items.{{ $index }}.unit_price" />
                  @error('items.' . $index . '.unit_price')
                    <x-input-error for="items.{{ $index }}.unit_price" class="mt-1" />
                  @enderror
                </div>
                <div class="col-span-4 md:col-span-2">
                  <x-input type="number" placeholder="Qty" wire:model.live="items.{{ $index }}.quantity" />
                  @error('items.' . $index . '.quantity')
                    <x-input-error for="items.{{ $index }}.quantity" class="mt-1" />
                  @enderror
                </div>
                <div class="col-span-3 md:col-span-2">
                  <x-input type="text" readonly value="Rp {{ number_format((float) ($item['total'] ?? 0), 0, ',', '.') }}" />
                </div>
                <div class="col-span-1">
                  <x-danger-button wire:click="removeItem({{ $index }})" type="button" class="w-full text-xs">
                    ×
                  </x-danger-button>
                </div>
              </div>
            @endforeach
          </div>
          @error('items')
            <x-input-error for="items" class="mt-2" />
          @enderror
        </div>

        <!-- Totals and Payment -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <h4 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Payment Information</h4>
            <div class="space-y-3">
              <div>
                <x-label for="bank_name" value="Bank Name" />
                <x-input id="bank_name" class="mt-1 block w-full" type="text" wire:model="bank_name" />
                @error('bank_name')
                  <x-input-error for="bank_name" class="mt-2" />
                @enderror
              </div>
              <div>
                <x-label for="account_name" value="Account Name" />
                <x-input id="account_name" class="mt-1 block w-full" type="text" wire:model="account_name" />
                @error('account_name')
                  <x-input-error for="account_name" class="mt-2" />
                @enderror
              </div>
              <div>
                <x-label for="account_number" value="Account Number" />
                <x-input id="account_number" class="mt-1 block w-full" type="text" wire:model="account_number" />
                @error('account_number')
                  <x-input-error for="account_number" class="mt-2" />
                @enderror
              </div>
              <div>
                <x-label for="payment_status" value="Payment Status" />
                <x-select id="payment_status" class="mt-1 block w-full" wire:model="payment_status">
                  <option value="unpaid">Unpaid</option>
                  <option value="paid">Paid</option>
                  <option value="partial">Partial</option>
                </x-select>
                @error('payment_status')
                  <x-input-error for="payment_status" class="mt-2" />
                @enderror
              </div>
            </div>
          </div>

          <div>
            <h4 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Summary</h4>
            <div class="space-y-2 rounded-lg border p-4">
              <div class="flex justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal:</span>
                <span class="text-sm font-semibold">Rp {{ number_format((float) ($subtotal ?? 0), 0, ',', '.') }}</span>
              </div>
              <div>
                <div class="flex items-center justify-between">
                  <x-label for="tax" value="Tax:" class="mb-0 text-sm" />
                  <div class="flex items-center gap-2">
                    <x-input id="tax" type="number" step="0.01" class="w-24 text-sm" wire:model.live="tax" />
                    <span class="text-sm">= Rp {{ number_format((float) ($tax ?? 0), 0, ',', '.') }}</span>
                  </div>
                </div>
                @error('tax')
                  <x-input-error for="tax" class="mt-1" />
                @enderror
              </div>
              <div class="border-t pt-2">
                <div class="flex justify-between">
                  <span class="font-semibold text-gray-900 dark:text-white">TOTAL:</span>
                  <span class="text-lg font-bold text-green-600 dark:text-green-400">Rp {{ number_format((float) ($total ?? 0), 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
            <div class="mt-3">
              <x-label for="notes" value="Notes (Optional)" />
              <x-textarea id="notes" class="mt-1 block w-full" wire:model="notes" rows="2" />
              @error('notes')
                <x-input-error for="notes" class="mt-2" />
              @enderror
            </div>
          </div>
        </div>
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="closeModal">
        Cancel
      </x-secondary-button>
      <x-button wire:click="save" wire:loading.attr="disabled">
        {{ $editingId ? 'Update' : 'Create' }}
      </x-button>
    </x-slot>
  </x-dialog-modal>

  <!-- Delete Confirmation Modal -->
  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      {{ __('Delete Invoice') }}
    </x-slot>

    <x-slot name="content">
      {{ __('Are you sure you want to delete invoice') }} <strong>{{ $deleteName }}</strong>? {{ __('This action cannot be undone.') }}
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$set('confirmingDeletion', false)">
        {{ __('Cancel') }}
      </x-secondary-button>
      <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
        {{ __('Delete') }}
      </x-danger-button>
    </x-slot>
  </x-confirmation-modal>
</div>
