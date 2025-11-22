<!-- KPIs -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
        <p class="text-sm text-base-content/60">My Projects</p>
        <h2 class="text-3xl font-bold mt-2">{{ $managerProjects }}</h2>
    </div>

    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
        <p class="text-sm text-base-content/60">Tasks Under My Team</p>
        <h2 class="text-3xl font-bold mt-2">{{ $managerTasks }}</h2>
    </div>

    <div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
        <p class="text-sm text-base-content/60">Team Members</p>
        <h2 class="text-3xl font-bold mt-2">{{ $managerEmployees }}</h2>
    </div>
</div>

<!-- Chart for Manager (reuse same ApexChart input) -->
<div class="mt-10 border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm">
    <h3 class="text-xl font-semibold mb-4">Team Task Time Spent</h3>
    <div id="taskTimeChart" class="h-64"></div>
</div>

<!-- Recent Tasks -->
<div class="border border-base-content/20 rounded-xl p-6 bg-base-100 shadow-sm mt-10">
    <h3 class="text-xl font-semibold mb-4">Recent Team Tasks</h3>

    <div class="divide-y divide-base-content/10">
        @foreach ($managerRecentTasks as $task)
            <div class="py-3 flex justify-between items-center">
                <div>
                    <p class="font-semibold">{{ $task->title }}</p>
                    <p class="text-sm text-base-content/60">
                        {{ $task->project->name }} • Assigned to: {{ $task->assignee->name ?? '—' }}
                    </p>
                </div>
                <span class="badge badge-outline">
                    @if($task->status === 'in_progress') In Progress
                    @elseif($task->status === 'completed') Completed
                    @elseif ($task->status === 'in_review') In Review
                    @else N/A @endif
                </span>
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