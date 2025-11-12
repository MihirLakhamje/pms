<x-layout>
    <x-slot name="title">Projects</x-slot>

    <div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary mb-4">Add Project</a>
    </div>

    <x-data-table>
        <x-slot name="header">
            <th>Project Name</th>
            <th>Status</th>
            <th>Deadline</th>
            <th>No. of Members</th>
            <th>Action</th>
        </x-slot>
        @forelse ($projects as $project)
            <tr>
                <td>{{ ucfirst($project->name) }}</td>
                <td>
                    @if ($project->status === 'in_progress')
                        <span class="font-semibold text-warning">In Progress</span>
                    @elseif ($project->status === 'on_hold')
                        <span class="font-semibold text-error">On Hold</span>
                    @elseif ($project->status === 'completed')
                        <span class="font-semibold text-success">Completed</span>
                    @endif
                </td>
                <td>{{ optional($project->deadline)->format('d M Y') ?? 'N/A' }}</td>
                <td>{{ $project->users->count() }}</td>
                <td>
                    <div class="flex gap-4">
                        <a href="{{ route('projects.edit', $project) }}" class="link link-success" aria-label="Edit">
                            Edit
                        </a>
                        <form action="{{ route('projects.destroy', $project) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="link link-error" aria-label="Delete">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="italic">No projects found.</td>
            </tr>
        @endforelse
    </x-data-table>


    <div class="">
        {{ $projects->links() }}
    </div>
</x-layout>