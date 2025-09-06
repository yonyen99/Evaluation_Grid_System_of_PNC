@extends('layout.app')
@section('page_title', 'Teacher Report')

@section('content')
    <div class="container">

        {{-- 🔹 Filter Form --}}
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('teacher-report') }}">
                    <input type="hidden" name="type" value="teaching_assigment">
                    <input type="hidden" name="action" value="submit">

                    <div class="row g-3 align-items-end">
                        {{-- Generation --}}
                        <div class="col-md-4">
                            <label for="generation" class="form-label">Generation</label>
                            <select name="generation" id="generation" class="form-control" onchange="this.form.submit()">
                                <option value="">-- All Generations --</option>
                                @foreach ($generations as $gen)
                                    <option value="{{ $gen->id }}"
                                        {{ request('generation') == $gen->id ? 'selected' : '' }}>
                                        {{ $gen->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Term --}}
                        <div class="col-md-4">
                            <label for="term" class="form-label">Term</label>
                            <select name="term" id="term" class="form-control" onchange="this.form.submit()">
                                <option value="">-- All Terms --</option>
                                @php
                                    // get terms dynamically when generation is chosen
                                    $terms = [];
                                    if (request('generation')) {
                                        $terms = \App\Models\Term::where('generation_id', request('generation'))->get();
                                    }
                                @endphp
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}"
                                        {{ request('term') == $term->id ? 'selected' : '' }}>
                                        {{ $term->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- 🔹 Report Table --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5>Teaching Report</h5>
            </div>
            <div class="card-body table-responsive">
                @if ($reports->isEmpty())
                    <p>No records found for this teacher.</p>
                @else
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Generation (Year)</th>
                                <th>Term (Semester)</th>
                                <th>Class</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td>{{ $report->generation }}</td>
                                    <td>{{ $report->term }}</td>
                                    <td>{{ $report->class_name }}</td>
                                    <td>{{ $report->subject }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
