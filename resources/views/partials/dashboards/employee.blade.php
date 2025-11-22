<div class="space-y-6">

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- My Projects -->
        <div class="border border-base-content/20 rounded-xl p-6 bg-base-100">
            <p class="text-sm text-base-content/60">My Projects</p>
            <h2 class="text-3xl font-bold mt-2">{{ $employeeProjects }}</h2>
        </div>

        <!-- My Tasks -->
        <div class="border border-base-content/20 rounded-xl p-6 bg-base-100">
            <p class="text-sm text-base-content/60">My Tasks</p>
            <h2 class="text-3xl font-bold mt-2">{{ $employeeTasks }}</h2>
        </div>

        <!-- Completed Tasks -->
        <div class="border border-base-content/20 rounded-xl p-6 bg-base-100">
            <p class="text-sm text-base-content/60">Completed</p>
            <h2 class="text-3xl font-bold mt-2">{{ $employeeCompleted }}</h2>
        </div>

    </div>


    <!-- Task Time Chart -->
    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
        <h3 class="text-xl font-semibold mb-4">My Task Time Spent (Hours)</h3>
        <div id="employeeTaskTimeChart" class="h-64"></div>
    </div>


    <!-- Recent Tasks -->
    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
        <h3 class="text-xl font-semibold mb-4">My Recent Tasks</h3>

        <div class="divide-y divide-base-content/10">
            @foreach ($employeeRecentTasks as $task)
                <div class="py-3 flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $task->title }}</p>
                        <p class="text-sm text-base-content/60">
                            {{ $task->project->name }}
                        </p>
                    </div>

                    <span class="badge badge-outline">
                        @if($task->status === 'in_progress') In Progress
                        @elseif($task->status === 'completed') Completed
                        @elseif($task->status === 'in_review') In Review
                        @else N/A @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const empTaskNames = @json($employeeTimeData->pluck('task'));
    const empTaskHours = @json($employeeTimeData->pluck('hours'));

    var empChart = new ApexCharts(document.querySelector("#employeeTaskTimeChart"), {
        chart: { type: 'bar', height: 320 },
        series: [{
            name: 'Hours Spent',
            data: empTaskHours,
        }],
        xaxis: { categories: empTaskNames },
        colors: ['#10B981'], // green
        tooltip: {
            y: { formatter: val => val + " hours" }
        }
    });

    empChart.render();
</script>