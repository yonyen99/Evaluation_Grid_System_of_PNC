@php
if (!function_exists('scoreToGrade')) {
    function scoreToGrade($score) {
        if ($score >= 85) return 'A';
        if ($score >= 80) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'C+';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'E';
    }
}
@endphp

@foreach ($students as $index => $student)
    @php
        $subjectsByClass = $subjectsByStudent[$student->student_id] ?? collect();
    @endphp

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Student</th>
                @foreach ($subjectsByClass as $className => $classSubjects)
                    <th colspan="{{ $classSubjects->count() }}">
                        {{ $className }}<br>
                        <small>{{ $classSubjects->first()->term_name }}</small>
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($subjectsByClass as $classSubjects)
                    @foreach ($classSubjects as $subject)
                        <th>{{ $subject->name }}</th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                @foreach ($subjectsByClass as $classSubjects)
                    @foreach ($classSubjects as $subject)
                        @php
                            $score = $scores[$student->student_id][$subject->id] ?? null;
                        @endphp
                        <td>{{ $score !== null ? number_format($score, 2) : '-' }}</td>
                    @endforeach
                @endforeach
            </tr>
        </tbody>
    </table>
@endforeach
