<?php

namespace App\Livewire\Admin\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Carbon;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceComponent extends Component
{
    use WithPagination, InteractsWithBanner;

    public $search = '';
    public $showModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $deleteName = null;
    public $confirmingDeletion = false;

    // Form properties
    public $order_number = '';
    public $sender_name = 'FIRSTSUDIO';
    public $sender_email = 'firstudio24@gmail.com';
    public $sender_address = 'Jl. Kauman Barat IV, Palebon, Kec. Pedurungan, Kota Semarang, Jawa Tengah';
    public $recipient_name = '';
    public $recipient_email = '';
    public $recipient_address = '';
    public $invoice_date = '';
    public $tax = 0;
    public $bank_name = 'BNI';
    public $account_name = 'Ifnu Setya A';
    public $account_number = '829419464';
    public $payment_status = 'unpaid';
    public $notes = '';
    
    // Items
    public $items = [];

    public function mount()
    {
        $this->invoice_date = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showCreating()
    {
        $this->resetForm();
        $this->order_number = Invoice::generateOrderNumber();
        $this->showModal = true;
        $this->editingId = null;
        $this->addItem();
    }

    public function showEditing($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $this->editingId = $invoice->id;
        $this->order_number = $invoice->order_number;
        $this->sender_name = $invoice->sender_name;
        $this->sender_email = $invoice->sender_email;
        $this->sender_address = $invoice->sender_address;
        $this->recipient_name = $invoice->recipient_name;
        $this->recipient_email = $invoice->recipient_email;
        $this->recipient_address = $invoice->recipient_address;
        $this->invoice_date = $invoice->invoice_date->format('Y-m-d');
        $this->tax = $invoice->tax;
        $this->bank_name = $invoice->bank_name;
        $this->account_name = $invoice->account_name;
        $this->account_number = $invoice->account_number;
        $this->payment_status = $invoice->payment_status;
        $this->notes = $invoice->notes;
        
        $this->items = $invoice->items->map(function ($item) {
            return [
                'id' => $item->id,
                'description' => $item->description,
                'unit_price' => $item->unit_price,
                'quantity' => $item->quantity,
                'total' => $item->total,
            ];
        })->toArray();
        
        if (empty($this->items)) {
            $this->addItem();
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->order_number = '';
        $this->sender_name = 'FIRSTSUDIO';
        $this->sender_email = 'firstudio24@gmail.com';
        $this->sender_address = 'Jl. Kauman Barat IV, Palebon, Kec. Pedurungan, Kota Semarang, Jawa Tengah';
        $this->recipient_name = '';
        $this->recipient_email = '';
        $this->recipient_address = '';
        $this->invoice_date = date('Y-m-d');
        $this->tax = 0;
        $this->bank_name = 'BNI';
        $this->account_name = 'Ifnu Setya A';
        $this->account_number = '829419464';
        $this->payment_status = 'unpaid';
        $this->notes = '';
        $this->items = [];
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function addItem()
    {
        $this->items[] = [
            'id' => null,
            'description' => '',
            'unit_price' => 0,
            'quantity' => 1,
            'total' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updatedItems($value, $key)
    {
        // Calculate total when item fields change
        if (str_contains($key, '.')) {
            [$index, $field] = explode('.', $key);
            $index = (int) $index;
            
            if (isset($this->items[$index])) {
                if ($field === 'unit_price' || $field === 'quantity') {
                    $unitPrice = (float) ($this->items[$index]['unit_price'] ?? 0);
                    $quantity = (int) ($this->items[$index]['quantity'] ?? 1);
                    $this->items[$index]['total'] = $unitPrice * $quantity;
                }
            }
        }
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        // This will be called automatically when items change
    }

    public function save()
    {
        $this->validate([
            'order_number' => 'required|string|max:255|unique:invoices,order_number,' . ($this->editingId ?? ''),
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'nullable|email|max:255',
            'sender_address' => 'nullable|string',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_address' => 'nullable|string',
            'invoice_date' => 'required|date',
            'tax' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($this->items as $item) {
            $itemTotal = (float) $item['unit_price'] * (int) $item['quantity'];
            $subtotal += $itemTotal;
        }
        $taxAmount = (float) $this->tax;
        $total = $subtotal + $taxAmount;

        if ($this->editingId) {
            $invoice = Invoice::findOrFail($this->editingId);
            $invoice->update([
                'order_number' => $this->order_number,
                'sender_name' => $this->sender_name,
                'sender_email' => $this->sender_email,
                'sender_address' => $this->sender_address,
                'recipient_name' => $this->recipient_name,
                'recipient_email' => $this->recipient_email,
                'recipient_address' => $this->recipient_address,
                'invoice_date' => $this->invoice_date,
                'subtotal' => $subtotal,
                'tax' => $taxAmount,
                'total' => $total,
                'bank_name' => $this->bank_name,
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
                'payment_status' => $this->payment_status,
                'notes' => $this->notes,
            ]);

            // Delete existing items
            $invoice->items()->delete();
        } else {
            $invoice = Invoice::create([
                'order_number' => $this->order_number,
                'sender_name' => $this->sender_name,
                'sender_email' => $this->sender_email,
                'sender_address' => $this->sender_address,
                'recipient_name' => $this->recipient_name,
                'recipient_email' => $this->recipient_email,
                'recipient_address' => $this->recipient_address,
                'invoice_date' => $this->invoice_date,
                'subtotal' => $subtotal,
                'tax' => $taxAmount,
                'total' => $total,
                'bank_name' => $this->bank_name,
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
                'payment_status' => $this->payment_status,
                'notes' => $this->notes,
            ]);
        }

        // Create/update items
        foreach ($this->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'total' => (float) $item['unit_price'] * (int) $item['quantity'],
            ]);
        }

        $this->closeModal();
        $this->banner($this->editingId ? __('Invoice updated successfully.') : __('Invoice created successfully.'));
    }

    public function confirmDeletion($id, $orderNumber)
    {
        $this->deleteId = $id;
        $this->deleteName = $orderNumber;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            Invoice::findOrFail($this->deleteId)->delete();
            $this->confirmingDeletion = false;
            $this->deleteId = null;
            $this->deleteName = null;
            $this->banner(__('Invoice deleted successfully.'));
        }
    }

    public function render()
    {
        $invoices = Invoice::query()
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('recipient_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate subtotal and total for form
        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += (float) ($item['total'] ?? 0);
        }
        $tax = (float) ($this->tax ?? 0);
        $total = $subtotal + $tax;

        return view('livewire.admin.invoice.invoice-component', [
            'invoices' => $invoices,
            'subtotal' => (float) $subtotal,
            'tax' => (float) ($this->tax ?? 0),
            'total' => (float) $total,
        ]);
    }
}
