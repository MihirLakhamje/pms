<x-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Project: {{ $project->name }}
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('projects.edit', $project) }}"
                    class="px-3 py-1.5 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    Edit
                </a>

                <form action="{{ route('projects.destroy', $project) }}" method="POST"
                    onsubmit="return confirm('Delete this project?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1.5 text-sm rounded-md bg-red-600 text-white hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- PROJECT DETAILS --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <span class="icon-[tabler--info-circle]"></span>
                Project Details
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium text-gray-800">{{ $project->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span @class([
                        'font-semibold',
                        $project->status === 'in_progress' => 'text-warning',
                        $project->status === 'on_hold' => 'text-gray-700',
                        $project->status === 'completed' => 'text-success',
                    ])>
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Deadline</p>
                    <p class="font-medium text-gray-800">
                        {{ $project->deadline?->format('d M Y') ?? '—' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="font-medium text-gray-800 leading-6">
                        {{ $project->description ?? 'No description.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- PROJECT MEMBERS --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <span class="icon-[tabler--users]"></span>
                Members
            </h3>

            <x-data-table>
                <x-slot name="header">
                    <th>#</th>
                    <th>User</th>
                    <th>Email</th>
                </x-slot>

                @forelse($users as $i => $user)
                    <tr>
                        <td>{{ $i + 1 }}</td>

                        <td class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span>{{ $user->name }}</span>
                        </td>

                        <td>{{ $user->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-500 py-4">
                            No members assigned.
                        </td>
                    </tr>
                @endforelse
            </x-data-table>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layout>