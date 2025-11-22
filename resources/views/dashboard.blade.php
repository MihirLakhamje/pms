<x-layout>
    <x-slot name="title">Welcome back, {{ auth()->user()->name }}</x-slot>
    <div class="space-y-6">
        @can('role', [['admin']])
            @include('partials.dashboards.admin')
        @endcan

        @can('role', [['manager']])
            @include('partials.dashboards.manager')
        @endcan

        @can('role', [['employee']])
            @include('partials.dashboards.employee')
        @endcan

    </div>
</x-layout>



