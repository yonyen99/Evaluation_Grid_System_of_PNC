@extends('layout.app')
@section('page_title', 'Test')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="log-history-content-wrapper mt-3 overflow-auto">
        <table class="table table-striped table-bordered align-middle">
            <!-- Table Head -->
            <thead class="table-light">
                <tr>
                    <th>Action Time</th>
                    <th>User</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody>
                @foreach ($logHistories as $logHistory)
                    <tr> 
                        <td>{{ $logHistory->created_at }}</td>
                        <td>{{ ucwords($logHistory->username) }}</td>
                        <td class="w-60">{{ $logHistory->description }}</td>
                        <td>{{ $logHistory->log_header }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>    
    </div>

@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script>
        $("#log-history-search").on("keyup", function(){
            var value = $(this).val().toLowerCase();
            console.clear();
            $("table tr").each(function(index){
                if (index !== 0) {
                    $row = $(this);
                    $row.find("td").each(function(i, td){
                        var id = $(td).text().toLowerCase();
                        console.log(id + " | " + value + " | " + id.indexOf(value));

                        if (id.indexOf(value) !== -1) {
                            $row.show();
                            return false;
                        } else {
                            $row.hide();
                        }
                    });
                }
            });
        });
        
    </script>
@endsection
