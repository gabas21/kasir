<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CategoryManager extends Component
{
    // =========================================================
    // FIX: Properti dengan strict typing — mencegah type crash.
    // FIX: Hapus `public $categories` (anti-pattern di Livewire v3).
    // FIX: isModalOpen = 0 (integer) diubah ke bool = false.
    // =========================================================

    public string $name        = '';
    public string $slug        = '';
    public ?int   $category_id = null;
    public bool   $isModalOpen = false;

    public function render()
    {
        // FIX: Query sebagai local variable, bukan disimpan ke public property.
        $categories = Category::latest()->get();

        return view('livewire.category-manager', compact('categories'));
    }

    public function create(): void
    {
        $this->resetCreateForm();
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm(): void
    {
        $this->name        = '';
        $this->slug        = '';
        $this->category_id = null;
        $this->resetValidation();
    }

    public function store(): void
    {
        $this->validate([
            'name' => 'required|min:3|max:100',
        ]);

        Category::updateOrCreate(['id' => $this->category_id], [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
        ]);

        session()->flash('message',
            $this->category_id ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.');

        $this->closeModal();
        $this->resetCreateForm();
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name        = $category->name;
        $this->slug        = $category->slug;

        $this->isModalOpen = true;
    }

    public function delete(int $id): void
    {
        Category::find($id)?->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }
}
