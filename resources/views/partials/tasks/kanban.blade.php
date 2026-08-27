@php
    $statuses = [
        'todo' => 'To Do',
        'in_progress' => 'In Progress',
        'in_review' => 'In Review',
        'completed' => 'Completed',
    ];

    // Group tasks safely
    $grouped = $tasks->groupBy('status');

    // Make sure every status exists
    foreach ($statuses as $key => $label) {
        if (!isset($grouped[$key])) {
            $grouped[$key] = collect();
        }
    }
@endphp

<div class="grid grid-cols-1 sm:overflow-x-auto sm:flex gap-5 ">

    @foreach ($statuses as $status => $label)
        <div class="p-4 rounded-lg border card flex-1 min-w-xs shadow-none">

            <!-- Header -->
            <div class="pb-2 flex items-center justify-between">
                <h2 class="font-bold text-lg flex items-center">
                    <span @class([
                        'bg-base-content' => $status === 'todo',
                        'bg-warning' => $status === 'in_progress',
                        'bg-info' => $status === 'in_review',
                        'bg-success' => $status === 'completed',
                        'w-2 h-2 inline-block rounded-full mr-2'
                    ])></span>
                    {{ $label }}
                </h2>
            </div>

            <!-- Task List -->
            <div class="space-y-3">
                @forelse ($grouped[$status] as $task)
                    @can('view', $task)
                        <div class="card border border-primary bg-base-100 p-4 rounded-lg shadow-none">
                            <div class="font-semibold">#T-{{ $task->id }} {{ $task->title }}</div>

                            <div class="text-sm text-base-content/60">
                                {{ $task->project?->name ?? '—' }}
                            </div>

                            <div class="text-sm mt-1">
                                <span class="font-semibold">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            <div class="mt-2 flex justify-between text-xs text-base-content/70">
                                <span>
                                    Deadline: {{ optional($task->due_date)->format('d M') ?? 'No due' }}
                                </span>
                            </div>

                            <div class="mt-3 flex justify-between">
                                <a href="{{ route('tasks.show', $task) }}?task-view=kanban" class="link link-primary text-sm">
                                    View
                                </a>

                                @can('update', $task)
                                    <a href="{{ route('tasks.edit', $task) }}?task-view=kanban" class="link link-success text-sm">
                                        Edit
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcan
                @empty
                    <p class="text-sm text-base-content/50 italic">No tasks</p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $tasks->links() }}
</div>