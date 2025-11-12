<x-layout>
    <x-slot name="title">Create Task</x-slot>

    <div class="card p-8 max-w-xl mt-6">
        <h2 class="text-2xl font-semibold mb-6">New Task</h2>

        <form method="POST" action="{{ route('tasks.store') }}" class="flex flex-col gap-6">
            @csrf

            {{-- Project --}}
            <div>
                <label for="project_id" class="label-text font-medium">Project <span class="text-error">*</span></label>
                <div class="max-w-full">
                    <select id="project_id" name="project_id" data-select='{
                            "placeholder": "Select project",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "advance-select-toggle select-disabled:pointer-events-none select-disabled:opacity-40",
                            "hasSearch": true,
                            "dropdownClasses": "advance-select-menu max-h-52 pt-0 overflow-y-auto",
                            "optionClasses": "advance-select-option selected:select-active",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block\"></span></div>",
                            "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2\"></span>"
                        }' class="hidden" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('project_id')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Assignee --}}
            <div>
                <label for="assignee_id" class="label-text font-medium">Assign To <span
                        class="text-error">*</span></label>
                <div class="max-w-full">
                    <select id="assignee_id" name="assignee_id" data-select='{
                            "placeholder": "Select assignee",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "advance-select-toggle select-disabled:pointer-events-none select-disabled:opacity-40",
                            "hasSearch": true,
                            "dropdownClasses": "advance-select-menu max-h-52 pt-0 overflow-y-auto",
                            "optionClasses": "advance-select-option selected:select-active",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block\"></span></div>",
                            "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2\"></span>"
                        }' class="hidden" required>
                        <option value="">Select Assignee</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assignee_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('assignee_id')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Title --}}
            <div>
                <label for="title" class="label-text font-medium">Task Title <span class="text-error">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                    class="input w-full @error('title') input-error @enderror"
                    placeholder="e.g. API Integration, UI Fix, etc." required />
                @error('title')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="label-text font-medium">Description (optional)</label>
                <textarea id="description" name="description"
                    class="textarea w-full min-h-[120px] @error('description') textarea-error @enderror"
                    placeholder="Describe this task...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="label-text font-medium">Status <span class="text-error">*</span></label>
                <select id="status" name="status" class="select w-full @error('status') select-error @enderror"
                    required>
                    <option value="">Select status</option>
                    <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                    </option>
                    <option value="in_review" {{ old('status') == 'in_review' ? 'selected' : '' }}>In Review</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Priority --}}
            <div>
                <label for="priority" class="label-text font-medium">Priority <span class="text-error">*</span></label>
                <select id="priority" name="priority" class="select w-full @error('priority') select-error @enderror"
                    required>
                    <option value="">Select priority</option>
                    <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                </select>
                @error('priority')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Due Date --}}
            <div>
                <label for="due_date" class="label-text font-medium">Due Date (optional)</label>
                <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                    class="input w-full @error('due_date') input-error @enderror" />
                @error('due_date')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 justify-end mt-4">
                <a href="{{ route('tasks.index') }}" class="btn btn-soft btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Task</button>
            </div>
        </form>
    </div>
</x-layout>