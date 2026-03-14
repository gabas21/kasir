<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ProductManager extends Component
{
    use WithFileUploads;

    // =========================================================
    // FIX: Properti dengan strict typing — mencegah type crash
    // dan memperjelas kontrak data.
    // FIX: Hapus `public $products` (anti-pattern di Livewire v3)
    // karena Eloquent Collection disimpan di component state,
    // membebani memory dan session.
    // =========================================================

    public ?int   $product_id   = null;
    public string $name         = '';
    public string $description  = '';
    public $price               = '';  // nullable — input kosong = ''
    public ?int   $category_id  = null;
    public $image               = null; // file upload — tidak di-type
    public bool   $is_available = true;
    public bool   $isModalOpen  = false;
    public $oldImage             = null;

    public function render()
    {
        // FIX: Query dilakukan di render() sebagai variable lokal,
        // bukan disimpan ke public property.
        $products   = Product::with('category')->latest()->get();
        $categories = Category::all();

        return view('livewire.product-manager', compact('products', 'categories'));
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
        $this->product_id   = null;
        $this->name         = '';
        $this->description  = '';
        $this->price        = '';
        $this->category_id  = null;
        $this->image        = null;
        $this->oldImage     = null;
        $this->is_available = true;
    }

    public function store(): void
    {
        $this->validate([
            'name'        => 'required|min:3',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ]);

        $imagePath = $this->oldImage;

        if ($this->image) {
            if ($this->oldImage) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('products', 'public');
        }

        Product::updateOrCreate(['id' => $this->product_id], [
            'name'         => $this->name,
            'description'  => $this->description,
            'price'        => $this->price,
            'category_id'  => $this->category_id,
            'image'        => $imagePath,
            'is_available' => $this->is_available,
        ]);

        session()->flash('message',
            $this->product_id ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.');

        $this->closeModal();
        $this->resetCreateForm();
    }

    public function edit(int $id): void
    {
        $product = Product::findOrFail($id);

        $this->product_id   = $id;
        $this->name         = $product->name;
        $this->description  = $product->description ?? '';
        $this->price        = $product->price;
        $this->category_id  = $product->category_id;
        $this->oldImage     = $product->image;
        $this->is_available = (bool) $product->is_available;

        $this->isModalOpen = true;
    }

    public function delete(int $id): void
    {
        $product = Product::find($id);

        if ($product?->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product?->delete();
        session()->flash('message', 'Produk berhasil dihapus.');
    }
}
