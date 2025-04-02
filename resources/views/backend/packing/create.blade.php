@extends('backend.layout.main')
@section('content')
@if($errors->has('name'))
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
@endif
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="container-fluid">
        <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-info add-warehouse-btn"><i class="dripicons-plus"></i> {{trans('file.AddPackingType')}}</a>
    </div>
    <div class="table-responsive">
        <table id="warehouse-table" class="table">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.PackingType')}}</th>
                  
                   
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lims_warehouse_all as $key=>$warehouse)
            
                <tr data-id="{{$warehouse->id}}">
                    <td>{{$key}}</td>
                    <td>{{ $warehouse->name }}</td>
                 
                   
               
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <button type="button" data-id="{{$warehouse->id}}" class="open-EditWarehouseDialog btn btn-link" data-toggle="modal" data-target="#editModal"><i class="dripicons-document-edit"></i> {{trans('file.edit')}}
                                </button>
                                </li>
                                <li class="divider"></li>
                                {{ Form::open(['route' => ['packing.destroy', $warehouse->id], 'method' => 'DELETE'] ) }}
                                <li>
                                    <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> {{trans('file.delete')}}</button>
                                </li>
                                {{ Form::close() }}
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>


<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
        {!! Form::open(['route' => 'packing.store', 'method' => 'post']) !!}
      <div class="modal-header">
        <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Add Packing')}}</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
      </div>
      <div class="modal-body">
        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
          <div class="form-group">
            <label>{{trans('file.name')}} *</label>
            <input type="text" placeholder="Type Packing Name..." name="name" required="required" class="form-control">
          </div>

          <div class="form-group">
            <label>{{trans('file.size')}} *</label>
            <input type="text" placeholder="Type Size..." name="size" required="required" class="form-control">
          </div>

          <div class="form-group">
            <label>{{trans('file.width')}} *</label>
            <input type="number" placeholder="Enter Width..." name="width" required="required" class="form-control">
          </div>

          <div class="form-group">
            <label>{{trans('file.height')}} *</label>
            <input type="number" placeholder="Enter Height..." name="height" required="required" class="form-control">
          </div>
       
          <div class="form-group">
            <label>{{trans('file.weight')}} *</label>
            <input type="number" placeholder="Enter Weight..." name="weight" required="required" class="form-control">
          </div>
          
                <div class="form-group">
                    <label>{{trans('file.Unit')}} *</label>
                        <select required name="packing_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Unit...">
                                @foreach($lims_unit_all as $unit)
                                <option value="{{$unit->id}}">{{$unit->unit_name . ' (' . $unit->unit_code .', '. $unit->base_unit.', '. $unit->operator .', '. $unit->operation_value .')'}}</option>
                                @endforeach
                        </select>
                </div>
         
       
          <div class="form-group">
            <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
          </div>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>


<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
        {!! Form::open(['route' => ['packing.update',1], 'method' => 'put']) !!}
      <div class="modal-header">
        <h5 id="exampleModalLabel" class="modal-title"> {{trans('file.Update Packing')}}</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
      </div>
      <div class="modal-body">
        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
          <div class="form-group">
            <input type="hidden" name="packing_id">
            <label>{{trans('file.name')}} *</label>
            <input type="text" placeholder="Type Packing Name..." name="name" required="required" class="form-control">
          </div>

          <div class="form-group">
            <label>{{trans('file.size')}} *</label>
            <input type="text" placeholder="Type Size..." name="size" required="required" class="form-control">
          </div>

          <div class="form-group">
            <label>{{trans('file.width')}} *</label>
            <input type="number" placeholder="Enter Width..." name="width" required="required" class="form-control">
          </div>

          <div class="form-group">
            <label>{{trans('file.height')}} *</label>
            <input type="number" placeholder="Enter Height..." name="height" required="required" class="form-control">
          </div>
       
          <div class="form-group">
            <label>{{trans('file.weight')}} *</label>
            <input type="number" placeholder="Enter Weight..." name="weight" required="required" class="form-control">
          </div>

         
                <div class="form-group">
                    <label>{{trans('file.Unit')}} *</label>
                        <select required name="unit_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Unit...">
                                @foreach($lims_unit_all as $unit)
                                <option value="{{$unit->id}}">{{$unit->unit_name . ' (' . $unit->unit_code .', '. $unit->base_unit.', '. $unit->operator .', '. $unit->operation_value .')'}}</option>
                                @endforeach
                        </select>
                </div>
         
   
          <div class="form-group">
            <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
          </div>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script type="text/javascript">

    $("ul#setting").siblings('a').attr('aria-expanded','true');
    $("ul#setting").addClass("show");
    $("ul#setting #warehouse-menu").addClass("active");

    @if(config('database.connections.saleprosaas_landlord'))
        numberOfWarehouse = <?php echo json_encode($numberOfWarehouse)?>;
        $.ajax({
            type: 'GET',
            async: false,
            url: '{{route("package.fetchData", $general_setting->package_id)}}',
            success: function(data) {
                if(data['number_of_warehouse'] > 0 && data['number_of_warehouse'] <= numberOfWarehouse) {
                    $("a.add-warehouse-btn").addClass('d-none');
                }
            }
        });
    @endif

    var warehouse_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  function confirmDelete() {
      if (confirm("Are you sure want to delete?")) {
          return true;
      }
      return false;
  }

    $(document).ready(function() {

        $(document).on('click', '.open-EditWarehouseDialog', function() {
            var url = "packing/"
            var id = $(this).data('id').toString();
            url = url.concat(id).concat("/edit");

            $.get(url, function(data) {
                    $("#editModal input[name='name']").val(data['name']);
                $("#editModal input[name='size']").val(data['size']);
                $("#editModal input[name='height']").val(data['height']);
                $("#editModal input[name='weight']").val(data['weight']);
                $("#editModal input[name='width']").val(data['width']);
                $("#editModal select[name='unit_id']").val(data['unit_id']).trigger('change');
                $("#editModal input[name='packing_id']").val(data['id']);

            });
        });
  });

  $('#warehouse-table').DataTable( {
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
             "info":      '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
            "search":  '{{trans("file.Search")}}',
            'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
            }
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 2]
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
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                text: '<i title="delete" class="dripicons-cross"></i>',
                className: 'buttons-delete',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        warehouse_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                warehouse_id[i-1] = $(this).closest('tr').data('id');
                            }
                        });
                        if(warehouse_id.length && confirm("Are you sure want to delete?")) {
                            $.ajax({
                                type:'POST',
                                url:'packing/deletebyselection',
                                data:{
                                    warehouseIdArray: warehouse_id
                                },
                                success:function(data){
                                    alert(data);
                                }
                            });
                            dt.rows({ page: 'current', selected: true }).remove().draw(false);
                        }
                        else if(!warehouse_id.length)
                            alert('No packing is selected!');
                    }
                    else
                        alert('This feature is disable for demo!');
                }
            },
            {
                extend: 'colvis',
                columns: ':gt(0)'
            },
        ],
    } );

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$( "#select_all" ).on( "change", function() {
    if ($(this).is(':checked')) {
        $("tbody input[type='checkbox']").prop('checked', true);
    }
    else {
        $("tbody input[type='checkbox']").prop('checked', false);
    }
});

$("#export").on("click", function(e){
    e.preventDefault();
    var warehouse = [];
    $(':checkbox:checked').each(function(i){
      warehouse[i] = $(this).val();
    });
    $.ajax({
       type:'POST',
       url:'/exportwarehouse',
       data:{

            warehouseArray: warehouse
        },
       success:function(data){
        alert('Exported to CSV file successfully! Click Ok to download file');
        window.location.href = data;
       }
    });
});
</script>
@endpush
