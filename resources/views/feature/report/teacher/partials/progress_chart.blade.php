<div class="chart-container" style="position: relative; height:500px; width:100%">
    <canvas id="progressChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('progressChart').getContext('2d');

    // Prepare x-axis labels: all subjects of the first student (assume all students share same subjects)
    const chartLabels = @json(
        $subjectsByStudent[$students->first()->student_id]
            ->flatMap(fn($classSubjects) => $classSubjects->pluck('name'))
            ->values()
    );

    // Prepare datasets: one line per student
    const datasets = [
        @foreach ($students as $student)
            {
                label: "{{ $student->first_name }} {{ $student->last_name }}",
                data: [
                    @foreach ($subjectsByStudent[$student->student_id] ?? [] as $classSubjects)
                        @foreach ($classSubjects as $subject)
                            {{ $scores[$student->student_id][$subject->id] ?? 'null' }},
                        @endforeach
                    @endforeach
                ],
                borderColor: `hsl({{ $loop->index * 60 }}, 70%, 50%)`,
                backgroundColor: `hsla({{ $loop->index * 60 }}, 70%, 50%, 0.2)`,
                borderWidth: 2,
                tension: 0.3,
                fill: false
            },
        @endforeach
    ];

    // Create chart
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Student Progress by Subject'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    position: 'top'
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
