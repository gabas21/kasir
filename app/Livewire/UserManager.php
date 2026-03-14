<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    public bool $isModalOpen = false;
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'kasir';
    public string $search = '';

    protected function rules(): array
    {
        $rules = [
            'name'  => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role'  => 'required|in:kasir,admin,owner',
        ];

        // Password wajib saat create, opsional saat edit
        if (!$this->userId) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6';
        }

        return $rules;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role ?? 'kasir';
        $this->password = '';
        $this->isModalOpen = true;
    }

    public function store(): void
    {
        $this->validate();

        $data = [
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        session()->flash('message', $this->userId ? 'User berhasil diperbarui.' : 'User berhasil ditambahkan.');

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        $user = User::find($id);
        
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
            session()->flash('message', 'User berhasil dihapus.');
        } else {
            session()->flash('error', 'Tidak dapat menghapus akun sendiri.');
        }
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'kasir';
        $this->resetValidation();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                                                ->orWhere('email', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.user-manager', compact('users'));
    }
}
