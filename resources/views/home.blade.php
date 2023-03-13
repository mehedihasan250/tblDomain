@extends('layouts.app')
@push('custom-scripts')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" /> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<link href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
<link href="{{ asset('toaster/css/toastr.min.css') }}" rel="stylesheet">
<script src="{{ asset('toaster/js/toastr.min.js') }}"></script>
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
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex">
        <h4>Domains</h4>
        <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-primary ms-auto">New</button>
    </div>
    <hr>

    <table class="table table-bordered data-table table-striped table-hover">
        <thead>
            <tr>
                <th>List id</th>
                <th>Total</th>
                <th>Processed</th>
                <th >Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>



      
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Domain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="domain_form">
                    @csrf
                    <div class="mb-3 form-group">
                      <label class="form-label"><b>Domains </b><span class="text-danger">*</span></label>
                      <textarea  class="mb-3 form-control" name="domains" id="domains" cols="30" rows="10"></textarea>
                      <small>Enter multiple values with comma separated (Ex. orangetoolz.com,google.com)</small>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div> --}}
            </div>
        </div>
    </div>


</div>


<script>

    $(document).ready(function(){
        // var _token = $('meta[name="csrf-token"]').attr('content');
        // console.log(_token);
        var modal = '#staticBackdrop';
        var form = '#domain_form';

        $.toastr.config({
            time: 4000,
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('home') }}",
            columns: [
                {data: 'list_id', name: 'list_id', width:"25%"},
                {data: 'total', name: 'total', width:"25%"},
                {data: 'totalStatus', name: 'totalStatus', width:"25%"},
                {data: 'action', name: 'action', orderable: false, searchable: false, width:"25%", className: "text-center"},
            ]
        });

        $(form).on('submit', function(event){
            event.preventDefault();

            $.ajax({
               type:'POST',
               url:'{{route('insert-domain-form')}}',
               data:$(form).serialize(),
               success:function(data) {
                    
                    if(data.status=='success'){
                        $(form).trigger("reset");
                        $(modal).modal('hide');
                        table.ajax.reload();
                        $.toastr.success(data.html);
                    }else{
                        $.toastr.error(data.html);
                    }
               }
            });
        });

    });
</script>

@endsection
