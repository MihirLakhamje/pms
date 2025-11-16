<x-layout>

    <div class="space-y-6">

        <div class="border border-base-content/20 rounded-lg p-6 bg-base-100 space-y-6">
            <!-- Header -->
            <div>
                <h2 class="text-xl font-semibold">{{ $task->title }}</h2>
            </div>

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
        <div class="border border-base-content/20 rounded-lg p-6 bg-base-100">

            <nav class="tabs tabs-lifted tabs-sm justify-center sm:justify-start sm:tabs-md" aria-label="Tabs"
                role="tablist" aria-orientation="horizontal">
                <button type="button" class="tab active-tab:tab-active active" id="tabs-timesheet-item"
                    data-tab="#tabs-timesheet" aria-controls="tabs-timesheet" role="tab" aria-selected="true">
                    <span class="icon-[tabler--clock] size-5 shrink-0 me-2 hidden sm:block"></span>
                    Timesheet
                </button>
                <button type="button" class="tab active-tab:tab-active" id="tabs-attachment-item"
                    data-tab="#tabs-attachment" aria-controls="tabs-attachment" role="tab" aria-selected="false">
                    <span class="icon-[tabler--link] size-5 shrink-0 me-2 hidden sm:block"></span>
                    Attachments
                </button>
                <button type="button" class="tab active-tab:tab-active" id="tabs-comment-item" data-tab="#tabs-comment"
                    aria-controls="tabs-comment" role="tab" aria-selected="false">
                    <span class="icon-[tabler--message-circle] size-5 shrink-0 me-2 hidden sm:block"></span>
                    Comments
                </button>
            </nav>

            <div class="mt-3">
                <div id="tabs-timesheet" role="tabpanel" aria-labelledby="tabs-timesheet-item">
                    <div>

                        <x-data-table>
                            <x-slot name="header">
                                <th>Date</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Duration</th>
                            </x-slot>

                            @foreach ($task->timesheets->sortByDesc('start_time') as $t)
                                <tr>
                                    <td>{{ $t->date->format('d M Y') }}</td>
                                    <td>{{ $t->start_time }}</td>
                                    <td>{{ $t->end_time ?? '—' }}</td>
                                    <td>{{ $t->duration }}</td>
                                </tr>
                            @endforeach
                        </x-data-table>
                    </div>
                </div>
                <div id="tabs-attachment" class="hidden" role="tabpanel" aria-labelledby="tabs-attachment-item">
                    <p class="text-base-content/80">
                        This is your <span class="text-base-content font-semibold">Profile</span> tab, where you can
                        update
                        your personal information and manage your account details.
                    </p>
                </div>
                <div id="tabs-comment" class="hidden" role="tabpanel" aria-labelledby="tabs-comment-item">
                    <p class="text-base-content/80">
                        <span class="text-base-content font-semibold">Messages:</span> View your recent messages, chat
                        with
                        friends, and manage your conversations.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layout>