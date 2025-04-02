@extends('backend.layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif

@if(session()->has('error'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('error') }}</div>
@endif

@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="text-center">{{trans('file.IntegrationsList')}}</h3>
            </div>
            {!! Form::open(['route' => 'integrations.index', 'method' => 'get']) !!}
            <div class="row ml-1 mt-2">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>{{trans('file.Date')}}</strong></label>
                    </div>
                </div>
              {{--  <div class="col-md-3 @if(\Auth::user()->role_id > 2){{'d-none'}}@endif">--}}
                    <div class="form-group">
                        <label><strong>{{trans('file.IntegrationType')}}</strong></label>
                        <select id="warehouse_id" name="warehouse_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" >
                            <option value="0">{{trans('file.All IntegrationType')}}</option>
                            @foreach($integration_list as $integration)
                                <option value="{{$integration->id}}">{{$integration->name}}</option>
                            @endforeach
                        </select>
                    </div>
              {{--  </div>--}}
                <div class="col-md-3">
                  
                </div>
                <div class="col-md-3">
                   
                </div>
                <div class="col-md-2 mt-3">
                    <div class="form-group">
                        <button class="btn btn-primary" id="filter-btn" type="submit">{{trans('file.submit')}}</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
      {{--  @if(in_array("integrations-add", $all_permission))
            <a href="{{route('integrations.create')}}" class="btn btn-info"><i class="dripicons-plus"></i> {{trans('file.Add Integration')}}</a>&nbsp;
        @endif--}}
    </div>
    <div class="table-responsive">
        <table id="integration-table" class="table integration-list" style="width: 100%">
            <thead>
                <tr>
                    <th>{{trans('file.key')}}</th>
                    <th>{{trans('file.date')}}</th>
                    <th>{{trans('file.integrationType')}}</th>
                    <th>{{trans('file.link')}}</th>
                  
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>

            <tfoot class="tfoot active">
             
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
            
        </table>
    </div>
</section>
@endsection

@push('scripts')
<script type="text/javascript">


    var columns = [
        
        {"data": "key"},
        {"data": "date"},
        {"data": "integration_type"},
        {"data": "link"}
    ];
columns.push({"data": "options"});
    $('#integration-table').DataTable( {
    
        "processing": true,
        "serverSide": true,
        
         "ajax": {
        url: "{{ route('integration.data') }}",
        type: "POST",
        data: function(d) {
            d._token = "{{ csrf_token() }}"; // Send CSRF token with AJAX request
        },
        dataType: "json",
        
        },
     "createdRow": function( row, data, dataIndex ) {
            $(row).addClass('integration-link');
            $(row).attr('data-integration', data['integration']);
        },
        
        "columns": columns,
        'language': {
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
             "info":      '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
            "search":  '{{trans("file.Search")}}',
            'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
            }
        },
        order:[['1', 'desc']],
        'columnDefs': [
            {
                "orderable": true,
                'targets': [1,2, 3]
            },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
       
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
               
                footer:false
            },
            {
                extend: 'excel',
                text: '<i title="export to excel" class="dripicons-document-new"></i>',
                exportOptions: {
                    columns: ':visible:not(.not-exported)',
                    rows: ':visible'
                },
              
                footer:false
            },
            {
                extend: 'csv',
                text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                exportOptions: {
                    columns: ':visible:not(.not-exported)',
                    rows: ':visible'
                },
              
                footer:false
            },
            {
                extend: 'print',
                text: '<i title="print" class="fa fa-print"></i>',
                exportOptions: {
                    columns: ':visible:not(.not-exported)',
                    rows: ':visible'
                },
              
                footer:false
            },
            {
                extend: 'colvis',
                text: '<i title="column visibility" class="fa fa-eye"></i>',
                columns: ':gt(0)'
            },
        ],
      
    });

</script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>


@endpush
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<script>
   $(document).on("click", ".get-sync", function(event) {
        rowindex = $(this).closest('tr').index();
        var id = $(this).data('id').toString();
        $.get('integrations1/getsync/' + id, function(response) {
                // Check if response has a redirect URL
                if (response.redirect_url) {
                    // Redirect the browser to the URL provided in the response
                    window.location.href = response.redirect_url;
                } else {
                    alert('Unexpected response from server.');
                }
            }).fail(function(xhr, status, error) {
                // Handle any errors
                alert('An error occurred: ' + xhr.responseText);
            });
    });
    </script>
