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
        @can('view', $task)
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

                        @can('update', $task)
                            <a href="{{ route('tasks.edit', $task) }}" class="link link-success">Edit</a>
                        @endcan

                        @can('delete', $task)
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button class="link link-error">Delete</button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @endcan
    @empty
        <tr>
            <td colspan="8" class="italic py-3">No tasks found.</td>
        </tr>
    @endforelse
</x-data-table>

<div class="mt-4">
    {{ $tasks->links() }}
</div>