@extends('layout.app')

@section('page_title', 'Transcript')

@section('content')
    <div class="container my-5">
        <div class="transcript-container">
            <!-- Logo and Title Section -->
            <div class="logo-section">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRo_2n4ixhk90E0WEDNpghs_skGLtJZuMNCGfqyiBtnwoKRjd8DRZxCgLlmGYCwm9fuGAg&usqp=CAU"
                    alt="PNC Logo" class="logo">
                <h1 class="transcript-title" style=" text-align: center;">ACADEMIC TRANSCRIPT</h1>
                <div style="width: 120px;"></div> <!-- Spacer for balance -->
            </div>

            <!-- Header Section -->
            <div class="header-section">
                <table width="100%">
                    <tr>
                        <td width="50%">
                            <div class="first-name">FIRST NAME: {{ $student->first_name ?? 'N/A' }}</div>
                        </td>
                        <td width="50%">
                            <div class="last-name">LAST NAME: {{ $student->last_name ?? 'N/A' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="academic-period">ACADEMIC PERIOD: {{ $academicPeriod ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="generation">GENERATION: {{ $transcript[0]['generation'] ?? 'N/A' }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Transcript Table -->
            @foreach ($transcript as $termIndex => $termData)
                @php
                    $it = $termData['it_training'] ?? [];
                    $gen = $termData['general_training'] ?? [];
                    $totalCredits = array_sum(array_column($it, 'credit')) + array_sum(array_column($gen, 'credit'));
                    $totalRows = count($it) + count($gen);
                @endphp

                <table class="transcript-table">
                    <thead>
                        <tr>
                            <th width="5%"></th> <!-- TERM column -->
                            <th width="15%"></th> <!-- Training Type column -->
                            <th width="40%">Course title</th>
                            <th width="10%">Credits</th>
                            <th width="10%">Score</th>
                            <th width="10%">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- IT Training --}}
                        @foreach ($it as $index => $course)
                            <tr>
                                @if ($index === 0)
                                    <td rowspan="{{ $totalRows }}" class="term-cell">
                                        {{ $termData['term_name'] ?? 'TERM' }}</td>
                                @endif
                                @if ($index === 0)
                                    <td rowspan="{{ count($it) }}" class="training-type">IT Training</td>
                                @endif
                                <td>{{ $course['name'] ?? 'N/A' }}</td>
                                <td>{{ $course['credit'] ?? 'N/A' }}</td>
                                <td>{{ $course['score'] ?? 'N/A' }}</td>
                                <td>{{ $course['grade'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach

                        {{-- General Training --}}
                        @foreach ($gen as $index => $course)
                            <tr>
                                @if ($index === 0 && count($it) === 0)
                                    <td rowspan="{{ $totalRows }}" class="term-cell">
                                        {{ $termData['term_name'] ?? 'TERM' }}</td>
                                @endif
                                @if ($index === 0)
                                    <td rowspan="{{ count($gen) }}" class="training-type">General Training</td>
                                @endif
                                <td>{{ $course['name'] ?? 'N/A' }}</td>
                                <td>{{ $course['credit'] ?? 'N/A' }}</td>
                                <td>{{ $course['score'] ?? 'N/A' }}</td>
                                <td>{{ $course['grade'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach

                        {{-- TOTAL Row --}}
                        <tr class="total-row">
                            <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                            <td><strong>{{ $totalCredits }}</strong></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endforeach

            <!-- Grading Scale -->
            <div class="grading-scale">
                <h5>GRADING SCALE</h5>
                <table class="grading-table">
                    <thead>
                        <tr>
                            <th>Score</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>100 - 85</td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>85 - 80</td>
                            <td>B+</td>
                        </tr>
                        <tr>
                            <td>80 - 70</td>
                            <td>B</td>
                        </tr>
                        <tr>
                            <td>70 - 65</td>
                            <td>C+</td>
                        </tr>
                        <tr>
                            <td>65 - 60</td>
                            <td>C</td>
                        </tr>
                        <tr>
                            <td>60 - 50</td>
                            <td>D</td>
                        </tr>
                        <tr>
                            <td>&lt; 50</td>
                            <td>E</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Official Academic Transcript - Issued by the Registrar's Office</p>
            </div>
        </div>
    </div>

    <!-- ====================== -->
    <!-- CSS Styling Section -->
    <!-- ====================== -->
    <style>
        .transcript-container {
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 900px;
        }

        /* Logo and Title Section */
        .logo-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            height: 80px;
        }

        .transcript-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .header-section {
            margin-bottom: 25px;
            /* border-bottom: 2px solid #000; */
            padding-bottom: 15px;
        }

        .first-name,
        .last-name {
            font-size: 18px;
            /* font-weight: bold; */
            text-transform: uppercase;
        }

        .academic-period,
        .generation {
            font-size: 16px;
        }

        .generation {
            /* font-weight: bold; */
        }

        .transcript-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .transcript-table th,
        .transcript-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .transcript-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .term-cell {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            text-align: center;
            font-weight: bold;
            background-color: #f8f8f8;
            vertical-align: middle;
        }

        .training-type {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
            vertical-align: middle;
        }

        .total-row {
            background-color: #e6f7ff;
            font-weight: bold;
        }

        .total-row td {
            border-top: 2px solid #000;
        }

        .text-right {
            text-align: right;
            padding-right: 20px;
        }

        /* Grading Table */
        .grading-scale {
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .grading-scale h5 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .grading-table {
            width: 220px;
            border-collapse: collapse;
        }

        .grading-table th,
        .grading-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 14px;
        }

        .grading-table th {
            background-color: #f0f0f0;
        }

        .grading-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
        }
    </style>
@endsection
