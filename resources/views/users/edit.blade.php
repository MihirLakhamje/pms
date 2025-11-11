<x-layout>
    <x-slot name="title">Edit User</x-slot>
	<div class="max-w-lg ">
		<div class="card p-6">
			<form method="POST" action="{{ route('users.update', $user) }}">
				@csrf
				@method('PATCH')
				<div class="mb-4">
					<label class="label-text" for="name">Full Name</label>
					<input type="text" class="input w-full" id="name" name="name" value="{{ old('name', $user->name) }}"  />
				</div>
				<div class="mb-4">
					<label class="label-text" for="email">Email</label>
					<input type="email" class="input w-full" id="email" name="email" value="{{ old('email', $user->email) }}"  />
				</div>
				<div class="mb-4">
					<label class="label-text" for="role">Role</label>
					<select class="select w-full" id="role" name="role">
                        @foreach (App\Enums\Role::cases() as $role)
                            <option value="{{ $role->value }}" {{ old('role', $user->role->value) === $role->value ? 'selected' : '' }}>
                                {{ ucfirst($role->value) }}
                            </option>
                        @endforeach
					</select>
				</div>
				<div class="flex justify-end gap-2 mt-6">
					<a href="{{ route('users.index') }}" class="btn btn-soft btn-secondary">Cancel</a>
					<button type="submit" class="btn btn-primary">Update User</button>
				</div>
			</form>
		</div>
	</div>
</x-layout>