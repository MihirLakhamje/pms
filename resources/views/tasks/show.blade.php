<x-layout>
    <x-slot name="title" class="truncate">#T-{{ $task->id }} {{ $task->title }}</x-slot>

    <div class="space-y-6">
        <div class="space-y-6">
            @can('isAssigned', $task)
                <div x-data="persistentTimer({
<<<<<<< HEAD
                                        running: {{ $running ? 'true' : 'false' }},
                                        startTime: {{ $running ? "'" . $running->start_time . "'" : 'null' }}
                                    })" x-init="init()" class="flex gap-2 items-center">
=======
                    running: {{ $running ? 'true' : 'false' }},
                    startTime: {{ $running ? "'" . $running->start_time . "'" : 'null' }}
                })" x-init="init()" class="flex gap-2 items-center">
>>>>>>> 4ee398db56bd4ac653faf0a6175486b9a1064fcf

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
                function persistentTimer({
                    running,
                    startTime
                }) {
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
                <div class="border border-base-content/20 rounded-lg p-6 bg-base-100 flex-1 h-full min-h-0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold flex items-center"> <span
                                class="icon-[tabler--subtask] size-5"></span> <span class="ms-2">Task Details</span>
                        </h3>
                    </div>
                    <dl class="divide-y divide-base-content/25">
                        <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 text-base">
                            <dt class="font-medium text-base-content">Task Title</dt>
                            <dd class="mt-1  text-base-content/80 sm:col-span-2 sm:mt-0">{{ ucfirst($task->title) }}
                            </dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 text-base">
                            <dt class="font-medium text-base-content">Due Date</dt>
                            <dd class="mt-1  text-base-content/80 sm:col-span-2 sm:mt-0">
                                {{ $task->due_date?->format('d M Y') ?? 'N/A' }}</dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 text-base">
                            <dt class="font-medium text-base-content">Priority</dt>
                            <dd class="mt-1  text-base-content/80 sm:col-span-2 sm:mt-0">
                                @if ($task->priority === 'High')
                                    <span class="text-error font-semibold">High</span>
                                @elseif ($task->priority === 'Medium')
                                    <span class="text-warning font-semibold">Medium</span>
                                @else
                                    <span class="text-success font-semibold">Low</span>
                                @endif
                            </dd>
                        </div>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 text-base">
                            <dt class="font-medium text-base-content">Status</dt>
                            <dd class="mt-1  text-base-content/80 sm:col-span-2 sm:mt-0">
                                @if ($task->status === 'completed')
                                    <span class="text-success font-semibold">Completed</span>
                                @elseif ($task->status === 'in_progress')
                                    <span class="text-warning font-semibold">In Progress</span>
                                @elseif ($task->status === 'in_review')
                                    <span class="text-info font-semibold">In Review</span>
                                @else
                                    <span class="text-base-content font-semibold">To Do</span>
                                @endif
                            </dd>
                        </div>
                        {{-- Existing Attachments --}}
                        @if ($task->attachments->count())

                            <div class="pe-4 py-3">
                                <div class="text-base font-medium mb-2">Existing Attachments</div>

                                <div class="flex gap-4 overflow-x-auto pb-2 w-full">

                                    @foreach ($task->attachments as $attachment)
                                        <a href="{{ $attachment->temporary_url }}" target="_blank"
                                            class="min-w-[140px] max-w-[140px] border rounded-xl p-4 hover:bg-base-200 transition flex flex-col items-center text-center shrink-0">

                                            {{-- File Icon --}}
                                            <div class="text-5xl mb-3">
                                                📄
                                            </div>

                                            {{-- File Name --}}
                                            <p class="text-sm font-medium truncate w-full">
                                                {{ \Illuminate\Support\Str::limit($attachment->file_name, 18) }}
                                            </p>
                                        </a>
                                    @endforeach

                                </div>
                            </div>

                        @endif
                    </dl>
                </div>

                <div class="border border-base-content/20 rounded-lg p-6 bg-base-100 flex-1 h-full min-h-0">
                    <div class="flex items-center mb-4">
                        <span class="icon-[tabler--file-description] size-5 shrink-0"></span>
                        <h3 class="text-xl font-semibold ms-2">Description</h3>
                    </div>

                    <div class="prose max-w-none">
                        {{ $task->description ?? 'No description found' }}
                    </div>
                </div>
            </div>

<<<<<<< HEAD
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

=======
            {{-- BOTTOM SECTION — Tabs --}}
            <div class="flex gap-6 flex-wrap">
                <div class="border border-base-content/20 rounded-lg p-6 bg-base-100 flex-1 h-full min-h-0">

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold flex items-center"> <span
                                class="icon-[tabler--clock] size-5"></span> <span class="ms-2">Timesheets</span></h3>
                        <p class="text-sm text-base-content/60">{{ $task->total_duration }}</p>
                    </div>
                    <x-data-table>
                        <x-slot name="header">
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Duration</th>
                        </x-slot>

>>>>>>> 4ee398db56bd4ac653faf0a6175486b9a1064fcf
                        @forelse ($timesheets as $t)
                            @can('view', $t)
                                <tr>
                                    <td>{{ $t->date->format('d M Y') }}</td>
                                    <td>{{ $t->start_time->format('H:i:s A') }}
                                    </td>
                                    <td>{{ $t->end_time ? $t->end_time->format('H:i:s A') : '-' }}
                                    </td>
<<<<<<< HEAD

=======
>>>>>>> 4ee398db56bd4ac653faf0a6175486b9a1064fcf
                                    <td>{{ $t->duration }}</td>
                                </tr>
                            @endcan
                        @empty
                            <tr>
                                <td colspan="4" class="italic py-3">No timesheets found.</td>
                            </tr>
                        @endforelse
                    </x-data-table>

<<<<<<< HEAD
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
=======
                    <div class="my-4">
                        {{ $timesheets->links() }}
                    </div>

                </div>

                <div class="border border-base-content/20 rounded-lg p-6 bg-base-100 flex-1 h-full min-h-0">
                    <div>
                        <h3 class="text-xl font-semibold flex items-center mb-4"> <span
                                class="icon-[tabler--message] size-5"></span> <span class="ms-2">Comments</span></h3>

                        @can('create', App\Models\TaskReview::class)
                            <form action="{{ route('task-reviews.store') }}" method="POST" class="mb-6">
                                @csrf

                                <input type="hidden" name="task_id" value="{{ $task->id }}">

                                <textarea name="content" class="textarea textarea-bordered w-full" placeholder="Write comment..."></textarea>

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
>>>>>>> 4ee398db56bd4ac653faf0a6175486b9a1064fcf
                    </div>
                </div>
            </div>
        </div>
</x-layout>
