<?php

namespace App\Livewire;

use App\Models\Promo;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PromoManager extends Component
{
    use WithPagination;

    public bool $isModalOpen = false;
    public ?int $promoId = null;
    public string $code = '';
    public string $type = 'percentage';
    public float $value = 0;
    public bool $is_active = true;
    public string $search = '';

    protected function rules(): array
    {
        return [
            'code'      => 'required|min:3|max:50|unique:promos,code,' . $this->promoId,
            'type'      => 'required|in:percentage,fixed',
            'value'     => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        $promo = Promo::findOrFail($id);
        $this->promoId = $id;
        $this->code = $promo->code;
        $this->type = $promo->type;
        $this->value = $promo->value;
        $this->is_active = $promo->is_active;
        $this->isModalOpen = true;
    }

    public function store(): void
    {
        $this->validate();

        Promo::updateOrCreate(['id' => $this->promoId], [
            'code'      => strtoupper($this->code),
            'type'      => $this->type,
            'value'     => $this->value,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', $this->promoId ? 'Promo berhasil diperbarui.' : 'Promo berhasil ditambahkan.');
        $this->closeModal();
    }

    public function toggleActive(int $id): void
    {
        $promo = Promo::find($id);
        if ($promo) {
            $promo->update(['is_active' => !$promo->is_active]);
        }
    }

    public function delete(int $id): void
    {
        Promo::destroy($id);
        session()->flash('message', 'Promo berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->promoId = null;
        $this->code = '';
        $this->type = 'percentage';
        $this->value = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $promos = Promo::query()
            ->when($this->search, fn($q) => $q->where('code', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.promo-manager', compact('promos'));
    }
}
