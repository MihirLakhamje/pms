<x-layout>
    <x-slot name="title">Tasks</x-slot>

    <div class="flex items-center justify-between mb-4">
        @can('create', App\Models\Task::class)
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add Task</a>
        @endcan
        <div class="flex gap-3 items-center">
            <form method="GET" class="flex gap-2 items-center">
                <select name="project_id" class="select select-bordered">
                    <option value="">All Projects</option>
                    @foreach ($projects as $id => $name)
                        <option value="{{ $id }}" {{ request('project_id') == $id ? 'selected' : '' }}>
                            {{ ucfirst($name) }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary">Filter</button>
            </form>
        </div>

    </div>

    <x-data-table>
        <x-slot name="header">
            <th>Title</th>
            <th>Project</th>
            <th>Assignee</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Duration</th> {{-- 🕒 Added --}}
            <th>Due Date</th>
            <th>Action</th>
        </x-slot>

        @forelse ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->project?->name ?? '—' }}</td>
                <td>{{ $task->assignee?->name ?? '—' }}</td>

                {{-- Status --}}
                <td>
                    <span class="font-semibold 
                                    {{ $task->status === 'completed' ? 'text-success' :
            ($task->status === 'in_progress' ? 'text-warning' :
                ($task->status === 'in_review' ? 'text-info' : 'text-neutral')) }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </td>

                {{-- Priority --}}
                <td>
                    <span class="font-semibold 
                                    {{ $task->priority === 'High' ? 'text-error' :
            ($task->priority === 'Medium' ? 'text-warning' : 'text-success') }}">
                        {{ $task->priority }}
                    </span>
                </td>

                {{-- 🕒 Duration --}}
                <td>{{ $task->total_duration ?? '—' }}</td>

                {{-- Due Date --}}
                <td>{{ optional($task->due_date)->format('d M Y') ?? '—' }}</td>

                {{-- Actions --}}
                <td>
                    <div class="flex gap-4">
                        <a href="{{ route('tasks.show', $task) }}" class="link link-neutral">View</a>

                        <a href="{{ route('tasks.edit', $task) }}" class="link link-success">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button class="link link-error">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="italic py-3">No tasks found.</td>
            </tr>
        @endforelse
    </x-data-table>

    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</x-layout>