<!-- KPIs -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Total Projects -->
    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm hover:shadow-md transition">
        <p class="text-sm text-base-content/60">Total Projects</p>
        <h2 class="text-3xl font-bold mt-2">{{ $totalProjects }}</h2>
    </div>

    <!-- Total Tasks -->
    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm hover:shadow-md transition">
        <p class="text-sm text-base-content/60">Total Tasks</p>
        <h2 class="text-3xl font-bold mt-2">{{ $totalTasks }}</h2>
    </div>

    <!-- Total Employees -->
    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm hover:shadow-md transition">
        <p class="text-sm text-base-content/60">Employees</p>
        <h2 class="text-3xl font-bold mt-2">{{ $totalUsers }}</h2>
    </div>
</div>

<!-- Middle Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Task Time Spent Chart -->
    <div class="sm:col-span-2 col-span-1 border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
        <h3 class="text-xl font-semibold mb-4">Task Time Spent (Hours)</h3>

        <div id="taskTimeChart" class="h-64"></div>
    </div>

    <!-- Top Employees -->
    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
        <h3 class="text-xl font-semibold mb-4">Top Employees</h3>

        <div class="space-y-4">
            @foreach ($topEmployees as $user)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ $user->assignee->name ?? 'Unassigned' }}</p>
                        <p class="text-sm text-base-content/60">{{ $user->total }} tasks</p>
                    </div>
                    <span class="badge badge-primary badge-md">#{{ $loop->iteration }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Recent Tasks -->
<div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
    <h3 class="text-xl font-semibold mb-4">Recent Tasks</h3>

    <div class="divide-y divide-base-content/10">
        @foreach ($recentTasks as $task)
            <div class="py-3 flex justify-between items-center">
                <div>
                    <p class="font-semibold">{{ $task->title }}</p>
                    <p class="text-sm text-base-content/60">
                        {{ $task->project->name }} • Assigned to: {{ $task->assignee->name ?? '—' }}
                    </p>
                </div>
                <span class="badge badge-outline">@if($task->status === 'in_progress') In Progress
                @elseif($task->status === 'completed') Completed @elseif ($task->status === 'in_review') In
                    Review @else N/A (Unknown) @endif</span>
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    const taskNames = @json($taskTimeData->pluck('task'));
    const taskHours = @json($taskTimeData->pluck('hours'));

    var options = {
        chart: {
            type: 'bar',
            height: 320
        },
        series: [{
            name: 'Hours Spent',
            data: taskHours,
        }],
        xaxis: {
            categories: taskNames,
            labels: { rotate: -45 },
            title: {
                text: 'Tasks',
                style: {
                    color: '#6366F1',
                    fontSize: '14px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Hours',
                style: {
                    color: '#6366F1',
                    fontSize: '14px'
                }
            },
            min: 0,
            max: Math.max(...taskHours),
        },
        colors: ['#6366F1'], // Indigo
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " hours";
                }
            }
        },
        dataLabels: {
            enabled: false
        }
    };

    var chart = new ApexCharts(document.querySelector("#taskTimeChart"), options);
    chart.render();
</script>