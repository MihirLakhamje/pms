<x-layout>
    <x-slot name="title">People</x-slot>

    <div>
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-4">Add User</a>
    </div>

    <x-data-table>
        <x-slot name="header">
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Action</th>
        </x-slot>
        @foreach ($users as $user)
            <tr>
                <td>{{ ucfirst($user->name) }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->role->value === 'admin')
                        <span>Admin</span>
                    @elseif ($user->role->value === 'manager')
                        <span>Manager</span>
                    @elseif ($user->role->value === 'employee')
                        <span>Employee</span>
                    @else
                        <span>{{ ucfirst($user->role->value) }}</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="flex gap-4">
                        <a href="{{ route('users.edit', $user) }}" class="link link-success" aria-label="Edit">
                            Edit
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="link link-error" aria-label="Delete">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>


    <div class="">
        {{ $users->links() }}
    </div>
</x-layout>