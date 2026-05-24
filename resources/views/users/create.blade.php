<x-layout>
    <x-slot name="title">Create User</x-slot>
    <div class="card w-full p-6">
        <form method="POST" class="flex flex-col gap-6" action="{{ route('users.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-text" for="name">Full Name</label>
                    <input type="text" class="input w-full" id="name" name="name" value="{{ old('name') }}" required />
                </div>
                <div>
                    <label class="label-text" for="email">Email</label>
                    <input type="email" class="input w-full" id="email" name="email" value="{{ old('email') }}"
                        required />
                </div>
                <div>
                    <label class="label-text" for="role">Role</label>
                    <select class="select w-full" id="role" name="role" required>
                        <option value="employee">Employee</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="label-text" for="password">Password</label>
                    <input type="password" class="input w-full" id="password" name="password" required />
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('users.index') }}" class="btn btn-soft btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layout>