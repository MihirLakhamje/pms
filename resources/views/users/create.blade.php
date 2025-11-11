<x-layout>
    <x-slot name="title">Create User</x-slot>
    <div class="card p-6">
        <form method="POST" class="max-w-lg w-full" action="{{ route('users.store') }}">
            @csrf
            <div class="mb-4">
                <label class="label-text" for="name">Full Name</label>
                <input type="text" class="input w-full" id="name" name="name" value="{{ old('name') }}" required />
            </div>
            <div class="mb-4">
                <label class="label-text" for="email">Email</label>
                <input type="email" class="input w-full" id="email" name="email" value="{{ old('email') }}" required />
            </div>
            <div class="mb-4">
                <label class="label-text" for="role">Role</label>
                <select class="select w-full" id="role" name="role" required>
                    @foreach (App\Enums\Role::cases() as $role)
                        <option value="{{ $role->value }}" {{ old('role') === $role->value ? 'selected' : '' }}>
                            {{ ucfirst($role->value) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="label-text" for="password">Password</label>
                <input type="password" class="input w-full" id="password" name="password" required />
            </div>
            <div class="flex gap-2 mt-6">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('users.index') }}" class="btn btn-soft btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layout>