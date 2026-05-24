<x-layout>
    <x-slot name="title" class="truncate">#T-{{ $task->id }} {{ $task->title }}</x-slot>

    <div class="space-y-6">

        <div class="border border-base-content/20 rounded-lg p-6 bg-base-100 space-y-6">
            @can('isAssigned', $task)
                <div x-data="persistentTimer({
                                        running: {{ $running ? 'true' : 'false' }},
                                        startTime: {{ $running ? "'" . $running->start_time . "'" : 'null' }}
                                    })" x-init="init()" class="flex gap-2 items-center">

                    <!-- Start Timer Form -->
                    <form x-show="!running" method="POST" action="{{ route('timesheets.startTimer') }}">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Start Timer
                        </button>
                    </form>

                    <!-- Stop Timer Form -->
                    <form x-show="running" method="POST" action="{{ route('timesheets.stopTimer') }}">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <button type="submit" class="btn btn-error btn-sm">
                            Stop Timer
                        </button>
                    </form>

                    <!-- Timer Display -->
                    <div class="font-semibold">
                        <span x-text="display"></span>
                    </div>
                </div>
            @endcan

            <script>
                function persistentTimer({ running, startTime }) {
                    return {
                        running: running,
                        display: '00:00:00',
                        startTimestamp: startTime ? new Date(startTime).getTime() : null,
                        intervalId: null,

                        init() {
                            if (this.running && this.startTimestamp) {
                                // Start ticking from DB start time
                                this.tick();
                            }
                        },

                        tick() {
                            clearInterval(this.intervalId);
                            this.intervalId = setInterval(() => {
                                if (!this.startTimestamp) return;
                                const diff = Math.floor((Date.now() - this.startTimestamp) / 1000);
                                this.display = this.format(diff);
                            }, 1000);
                        },

                        format(seconds) {
                            const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
                            const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
                            const s = String(seconds % 60).padStart(2, '0');
                            return `${h}:${m}:${s}`;
                        }
                    }
                }
            </script>


            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Due Date -->
                    <div class="card">
                        <div class="space-y-1 card-body">
                            <p class="text-sm text-base-content/60">Due Date</p>
                            <p class="font-medium">
                                {{ $task->due_date?->format('d M Y') ?? '—' }}
                            </p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="card">
                        <div class="space-y-1 card-body">
                            <p class="text-sm text-base-content/60">Status</p>
                            <p class="font-medium">
                                <span>
                                    @if ($task->status === 'completed')
                                        Completed
                                    @elseif ($task->status === 'in_progress')
                                        In Progress
                                    @elseif ($task->status === 'in_review')
                                        In Review
                                    @else
                                        To Do
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="card">
                        <div class="space-y-1 card-body">
                            <p class="text-sm text-base-content/60">Status</p>
                            <p class="font-medium">
                                <span>
                                    @if ($task->priority === 'High')
                                        High
                                    @elseif ($task->priority === 'Medium')
                                        Medium
                                    @else
                                        Low
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Assignee -->
                    <div class="card">
                        <div class="space-y-1 card-body">
                            <p class="text-sm text-base-content/60">Assigned To</p>
                            <p class="font-medium">
                                <span>
                                    {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-1">
                    <p class="text-sm text-base-content/60">Description</p>
                    <p class="leading-relaxed">
                        {{ $task->description ?: 'No description provided.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- BOTTOM SECTION — Tabs --}}
        <div class="flex gap-6 flex-wrap">
            <div class="border border-base-content/20 rounded-lg p-6 bg-base-100 flex-1 h-full min-h-0">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold">Timesheets</h3>
                        <p class="text-sm text-base-content/60">{{ $task->total_duration }}</p>
                    </div>
                    <x-data-table>
                        <x-slot name="header">
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Duration</th>
                        </x-slot>

                        @forelse ($timesheets as $t)
                            @can('view', $t)
                                <tr>
                                    <td>{{ $t->date->format('d M Y') }}</td>
                                    <td>{{ $t->start_time->format('H:i:s A') }}
                                    </td>
                                    <td>{{ $t->end_time ? $t->end_time->format('H:i:s A') : '-' }}
                                    </td>

                                    <td>{{ $t->duration }}</td>
                                </tr>
                            @endcan
                        @empty
                            <tr>
                                <td colspan="4" class="italic py-3">No timesheets found.</td>
                            </tr>
                        @endforelse
                    </x-data-table>

                    <div class="mx-4">
                        {{ $timesheets->links() }}
                    </div>


                </div>
            </div>

            <div class="border border-base-content/20 rounded-lg p-6 bg-base-100 flex-1 h-full min-h-0">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Comments</h3>

                    @can('create', App\Models\TaskReview::class)
                        <form action="{{ route('task-reviews.store') }}" method="POST" class="mb-6">
                            @csrf

                            <input type="hidden" name="task_id" value="{{ $task->id }}">

                            <textarea name="content" class="textarea textarea-bordered w-full"
                                placeholder="Write comment..."></textarea>

                            <button class="btn btn-primary mt-3">
                                Add Comment
                            </button>
                        </form>
                    @endcan

                    <div class="space-y-4">
                        @foreach ($task->reviews()->latest()->get() as $review)
                            <div class="border border-base-300 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <p class="font-semibold">
                                            {{ $review->user->name }}
                                        </p>

                                        <p class="text-xs text-base-content/60">
                                            {{ $review->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    @can('delete', $review)
                                        <form action="{{ route('task-reviews.destroy', $review) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-error btn-xs">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>

                                <p>{{ $review->content }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>