@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">List of Alerts !!</div>

                <div class="panel-body">
                    <table class="table table-bordered" id="alerts-table">
                        <thead>
                            <tr>
                                <th>Full name</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Occurred on</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
    $('#alerts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables.reports.data') !!}',
        columns: [
            { data: 'full_name', name: 'full_name' },
            { data: 'lat', name: 'lat' },
            { data: 'lon', name: 'lon' },
            { data: 'created_at', name: 'created_at' }
        ]
    });
});
</script>
@endpush