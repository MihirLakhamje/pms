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
            <div class="join">
                {{-- Kanban --}}
                <a href="{{ request()->fullUrlWithQuery(['task-view' => 'kanban']) }}"
                    class="btn btn-square join-item {{ $view === 'kanban' ? 'btn-primary' : 'btn-soft btn-primary' }}">
                    <span class="icon-[tabler--layout-kanban]"></span>
                </a>

                {{-- Table --}}
                <a href="{{ request()->fullUrlWithQuery(['task-view' => 'table']) }}"
                    class="btn btn-square join-item {{ $view === 'table' ? 'btn-primary' : 'btn-soft btn-primary' }}">
                    <span class="icon-[tabler--table]"></span>
                </a>
            </div>
        </div>
    </div>

    @if ($view === 'kanban')
        @include('partials.tasks.kanban')
    @elseif ($view === 'table')
        @include('partials.tasks.table')
    @endif
</x-layout>