<x-layout>
    <x-slot name="title">Create Task</x-slot>

    <div class="card p-8 w-full mt-6">
        <form method="POST" action="{{ route('tasks.store') }}" class="flex flex-col gap-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="project_id" class="label-text font-medium">Project <span
                            class="text-error">*</span></label>
                    <div x-data="assignUser()" x-init="init()" class="flex gap-5">
                        <!-- Project Select -->
                        <select id="project_id" name="project_id" x-model="projectId" @@change="loadUsers"
                            class="border w-full select select-bordered">
                            <option value="">Select Project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>

                        <!-- Assigned To Select -->
                        <select id="assigned_to" name="assignee_id" class="border w-full select select-bordered">
                            <template x-for="user in users" :key="user.id">
                                <option :value="user.id" x-text="user.name.replace(/\b\w/g, c => c.toUpperCase())">
                                </option>
                            </template>

                            <option x-show="users.length === 0">No users for this project</option>
                        </select>
                    </div>
                </div>

                {{-- Title --}}
                <div>
                    <label for="title" class="label-text font-medium">Task Title <span
                            class="text-error">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        class="input w-full @error('title') input-error @enderror"
                        placeholder="e.g. API Integration, UI Fix, etc." required />
                    @error('title')
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
                    <label for="priority" class="label-text font-medium">Priority <span
                            class="text-error">*</span></label>
                    <select id="priority" name="priority"
                        class="select w-full @error('priority') select-error @enderror" required>
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
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 justify-end mt-4">
                <a href="{{ route('tasks.index') }}" class="btn btn-soft btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Task</button>
            </div>
        </form>
    </div>
</x-layout>

<script>
    function assignUser() {
        return {
            projectId: '',
            users: [],

            init() {
                // If editing a project, preload users here if needed
            },

            async loadUsers() {
                if (!this.projectId) {
                    this.users = [];
                    return;
                }

                try {
                    let res = await fetch(`/projects/${this.projectId}/users`);
                    this.users = await res.json();
                } catch (e) {
                    console.error("Failed to load users:", e);
                    this.users = [];
                }
            }
        }
    }
</script>