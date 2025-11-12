<x-layout>
    <x-slot name="title">Projects</x-slot>

    <div>
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-4">Add Project</a>
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
                    @if ($user->role === 'admin')
                        <span>Admin</span>
                    @elseif ($user->role === 'manager')
                        <span>Manager</span>
                    @elseif ($user->role === 'employee')
                        <span>Employee</span>
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