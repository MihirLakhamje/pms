<x-layout>
    <x-slot name="title">Create Project</x-slot>

    <div class="card p-8 max-w-xl w-full mt-6">
        <form method="POST" action="{{ route('projects.store') }}" class="flex flex-col gap-6">
            @csrf

            {{-- Project Name --}}
            <div>
                <label for="name" class="label-text font-medium">Project Name <span class="text-error">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="input w-full @error('name') input-error @enderror" placeholder="e.g. Website Redesign" />
                @error('name')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="label-text font-medium">Description (optional)</label>
                <textarea id="description" name="description"
                    class="textarea w-full min-h-[120px] @error('description') textarea-error @enderror"
                    placeholder="Write about the project...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="label-text font-medium">Status</label>
                <select id="status" name="status" class="select w-full @error('status') select-error @enderror">
                    <option value="">Select status</option>
                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress
                    </option>
                    <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Deadline --}}
            <div>
                <label for="deadline" class="label-text font-medium">Deadline (optional)</label>
                <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                    class="input w-full @error('deadline') input-error @enderror" />
                @error('deadline')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Members --}}
            <div>
                <label for="users" class="label-text font-medium mb-2 block">Assign Team Members (optional)</label>

                <div class="max-w-full">
                    <select id="users" name="users[]" multiple data-select='{
                            "placeholder": "Select team members",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "advance-select-toggle select-disabled:pointer-events-none select-disabled:opacity-40",
                            "hasSearch": true,
                            "dropdownClasses": "advance-select-menu max-h-52 pt-2 overflow-y-auto",
                            "optionClasses": "advance-select-option selected:select-active",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block\"></span></div>",
                            "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2\"></span>"
                        }' class="hidden">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ collect(old('users'))->contains($user->id) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @error('users')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 justify-end mt-4">
                <a href="{{ route('projects.index') }}" class="btn btn-soft btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Project</button>
            </div>
        </form>
    </div>
</x-layout>