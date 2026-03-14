<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-text-main">Manajemen Produk</h2>
        <button wire:click="create()" class="px-4 py-2 bg-gold text-white rounded-lg shadow-sm font-semibold transition active:scale-95 hover:opacity-90">
            + Tambah Produk
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-active/10 text-active px-4 py-3 rounded-xl mb-4 text-sm font-medium border border-active/20 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white border border-border rounded-lg overflow-x-auto shadow-sm">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-surface text-text-muted border-b border-border">
                    <th class="py-3 px-4 font-semibold text-sm w-20">Gambar</th>
                    <th class="py-3 px-4 font-semibold text-sm">Nama Produk</th>
                    <th class="py-3 px-4 font-semibold text-sm">Kategori</th>
                    <th class="py-3 px-4 font-semibold text-sm">Harga</th>
                    <th class="py-3 px-4 font-semibold text-sm">Status</th>
                    <th class="py-3 px-4 font-semibold text-sm text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr class="border-b border-border hover:bg-surface/50 transition duration-150">
                    <td class="py-3 px-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 object-cover rounded-lg border border-border" alt="{{ $product->name }}">
                        @else
                            <div class="w-12 h-12 bg-cream flex items-center justify-center rounded-lg border border-border text-text-muted text-xs font-medium">
                                No Img
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4 font-semibold text-text-main">
                        <div>{{ $product->name }}</div>
                        @if($product->description)
                        <div class="text-xs text-text-muted font-normal mt-0.5 truncate max-w-[200px]">{{ $product->description }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-text-main">{{ $product->category->name ?? '-' }}</td>
                    <td class="py-3 px-4 text-accent font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        @if($product->is_available)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter bg-active/10 text-active border border-active/20">
                                ● AVAILABLE
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter bg-danger/10 text-danger border border-danger/20">
                                ● OUT OF STOCK
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right whitespace-nowrap">
                        <button wire:click="edit({{ $product->id }})" class="text-gold hover:opacity-80 mr-3 font-medium transition duration-150 active:scale-95">Edit</button>
                        <button wire:click="delete({{ $product->id }})" class="text-danger hover:text-danger-light font-medium transition duration-150 active:scale-95" onclick="confirm('Yakin ingin menghapus produk ini?') || event.stopImmediatePropagation()">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-text-muted">Belum ada produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Background -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6 overflow-y-auto" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
        <div class="bg-white border border-border w-full max-w-2xl rounded-xl shadow-2xl p-6 my-auto max-h-full overflow-y-auto">
            <h3 class="text-xl font-bold text-text-main mb-4">{{ $product_id ? 'Edit Produk' : 'Tambah Produk' }}</h3>
            
            <form wire:submit.prevent="store">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-text-muted uppercase tracking-wider mb-2">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" wire:model="name" class="w-full px-4 py-3 bg-cream border border-border rounded-xl text-text-main focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/30 transition duration-150 placeholder:text-text-muted/50" placeholder="Contoh: Kopi Aren">
                        @error('name') <span class="text-danger text-sm mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-text-muted uppercase tracking-wider mb-2">Harga <span class="text-danger">*</span></label>
                        <input type="number" wire:model="price" class="w-full px-4 py-3 bg-cream border border-border rounded-xl text-text-main focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/30 transition duration-150 placeholder:text-text-muted/50" placeholder="Contoh: 25000">
                        @error('price') <span class="text-danger text-sm mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-text-muted text-sm font-semibold mb-2">Kategori <span class="text-danger">*</span></label>
                        <select wire:model="category_id" class="w-full px-4 py-3 bg-cream border border-border rounded-xl text-text-main focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/30 transition duration-150">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-danger text-sm mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4 flex items-center md:mt-8">
                        <label for="is_available_toggle" class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" id="is_available_toggle" wire:model.live="is_available" class="sr-only">
                                <div class="block w-12 h-7 rounded-full transition-colors duration-200 border border-border" 
                                     :class="$wire.is_available ? 'bg-gold/20 border-gold/50' : 'bg-surface'"></div>
                                <div class="dot absolute left-1 top-1 w-5 h-5 rounded-full transition-transform duration-200 shadow-sm" 
                                     :class="$wire.is_available ? 'translate-x-5 bg-gold' : 'bg-text-muted'"></div>
                            </div>
                            <div class="ml-3 font-medium text-text-main text-sm">
                                <span>Status: 
                                    <span :class="$wire.is_available ? 'text-active font-bold' : 'text-danger font-bold'" 
                                          x-text="$wire.is_available ? 'Tersedia' : 'Habis'">
                                    </span>
                                </span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-text-muted text-sm font-semibold mb-2">Deskripsi (Opsional)</label>
                    <textarea wire:model="description" rows="2" class="w-full px-4 py-3 bg-cream border border-border rounded-xl text-text-main focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/30 transition duration-150 placeholder:text-text-muted/50" placeholder="Contoh: Espresso dengan susu dan gula aren"></textarea>
                    @error('description') <span class="text-danger text-sm mt-1 block font-medium">{{ $message }}</span>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-text-muted uppercase tracking-wider mb-2">Gambar Produk (Opsional)</label>
                    <input type="file" wire:model="image" class="block w-full text-sm text-text-muted 
                        file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border file:border-gold/20 
                        file:text-sm file:font-semibold file:bg-gold/10 file:text-gold 
                        hover:file:bg-gold/20 transition duration-150 cursor-pointer" accept="image/*">
                    
                    <div x-show="isUploading" class="mt-2 text-gold text-sm font-medium flex items-center gap-2">
                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Menyiapkan pratinjau... <span x-text="progress"></span>%
                    </div>
                    @error('image') <span class="text-danger text-sm mt-1 block font-medium">{{ $message }}</span>@enderror

                    <div class="mt-3 flex items-start gap-4" wire:loading.remove wire:target="image">
                        @if ($image && method_exists($image, 'temporaryUrl'))
                            <div class="relative">
                                <span class="block text-xs font-semibold text-gold uppercase tracking-wider mb-1">Pratinjau:</span>
                                <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-xl border-2 border-gold/50">
                            </div>
                        @elseif($oldImage)
                            <div class="relative">
                                <span class="block text-xs font-semibold text-text-muted uppercase tracking-wider mb-1">Saat Ini:</span>
                                <img src="{{ asset('storage/' . $oldImage) }}" class="w-24 h-24 object-cover rounded-xl border border-border">
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-border">
                    <button type="button" wire:click="closeModal()" class="px-5 py-2.5 rounded-xl text-text-main bg-cream border border-border hover:bg-surface transition duration-150 font-semibold active:scale-95">Batal</button>
                    <!-- Loading state saat menyimpan -->
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-white bg-gold hover:opacity-90 shadow-md font-semibold transition duration-150 flex items-center justify-center min-w-[120px] active:scale-95" wire:loading.attr="disabled" wire:target="store, image">
                        <span wire:loading.remove wire:target="store">Simpan</span>
                        <span wire:loading wire:target="store" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg> Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
