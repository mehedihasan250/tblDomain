@extends('layouts.app')
@push('custom-scripts')


<link href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>

<style>
    /* .dataTables_length label{
        display: flex;
        margin-bottom: 20px;
    }
    .dataTables_length label select{
        margin: 0px 7px;
    }

    .dataTables_filter label{
        display: flex;
    } */
    .dataTables_length{
        padding-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex">
        <h4>List Domain</h4>
        <a href="{{route('home')}}" class="btn btn-primary ms-auto">Back</a>
    </div>
    <hr>

    <table class="table table-bordered data-table table-striped table-hover">
        <thead>
            <tr>
                <th>Domain name</th>
                <th>Dmarc flag</th>
                <th>Dkim flag</th>
                <th>Process status</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script>

    $(document).ready(function(){
        // var _token = $('meta[name="csrf-token"]').attr('content');
        // console.log(_token);
        var modal = '#staticBackdrop';
        var form = '#domain_form';

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            
            ajax: "{{ route('list-index', $listId) }}",
            columns: [
                {data: 'domain_name', name: 'domain_name', width:"25%"},
                {data: 'dmarc_flag_status', name: 'dmarc_flag_status', width:"25%"},
                {data: 'dkim_flag_status', name: 'dkim_flag_status', width:"25%"},
                {data: 'process_status', name: 'process_status', width:"25%"},
            ],
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        

    });
</script>

@endsection
