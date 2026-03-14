<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-text-main">Manajemen Kategori</h2>
        <button wire:click="create()" class="px-4 py-2 bg-gold text-white rounded-lg shadow-sm font-semibold transition hover:opacity-90 active:scale-95">
            + Tambah Kategori
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-active/10 text-active px-4 py-3 rounded-xl mb-4 border border-active/20 font-medium text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white border border-border rounded-lg overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface text-text-muted border-b border-border">
                    <th class="py-3 px-4 font-semibold text-sm">ID</th>
                    <th class="py-3 px-4 font-semibold text-sm">Nama Kategori</th>
                    <th class="py-3 px-4 font-semibold text-sm">Slug</th>
                    <th class="py-3 px-4 font-semibold text-sm text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $cat)
                <tr class="border-b border-border hover:bg-surface/50 transition">
                    <td class="py-3 px-4 text-text-main">{{ $cat->id }}</td>
                    <td class="py-3 px-4 font-semibold text-text-main">{{ $cat->name }}</td>
                    <td class="py-3 px-4 text-text-muted">{{ $cat->slug }}</td>
                    <td class="py-3 px-4 text-right">
                        <button wire:click="edit({{ $cat->id }})" class="text-gold hover:opacity-80 mr-3 font-medium">Edit</button>
                        <button wire:click="delete({{ $cat->id }})" class="text-danger hover:text-danger-light font-medium" onclick="confirm('Yakin ingin menghapus kategori ini?') || event.stopImmediatePropagation()">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-text-muted">Belum ada kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Background -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white border border-border w-11/12 md:w-1/3 rounded-xl shadow-2xl p-6">
            <h3 class="text-xl font-bold text-text-main mb-4">{{ $category_id ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
            
            <form wire:submit.prevent="store">
                <div class="mb-4">
                    <label class="block text-text-muted text-sm font-semibold mb-2 uppercase tracking-wide">Nama Kategori</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 bg-cream border border-border rounded-lg text-text-main focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/30 transition">
                    @error('name') <span class="text-danger text-sm mt-1 block font-medium">{{ $message }}</span>@enderror
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="closeModal()" class="px-5 py-2.5 rounded-lg text-text-main bg-cream hover:bg-border transition font-semibold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-white bg-gold hover:opacity-90 shadow-md font-semibold transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
