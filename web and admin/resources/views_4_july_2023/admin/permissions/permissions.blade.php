@extends('layouts.master')
@section('css') 
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <!--start page wrapper -->
      <div class="page-wrapper">
          <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
              <!-- <div class="breadcrumb-title pe-3">eCommerce</div> -->
              <div class="ps-3">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Permission</li>
                  </ol>
                </nav>
              </div>
            </div>
            <!--end breadcrumb-->
            <div class="row ">
              <div class="col-md-12">
                <!-- <h6 class="mb-0 text-uppercase">Primary Nav Tabs</h6> -->
                <hr/>
                <div class="card">
                  <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                          <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                            </div>
                            <div class="tab-title">Roles Permissions</div>
                          </div>
                        </a>
                      </li>
                      <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false">
                          <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                            </div>
                            <div class="tab-title">User Permissions</div>
                          </div>
                        </a>
                      </li> -->
                    </ul>
                    <div class="tab-content py-3">
                      <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                        <div class="table-responsive">
                          <table id="role_permission_listing" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                  <th>Actions</th>
                                  @foreach ($roles as $role)
                                    <th>{{$role['name']}}</th>
                                  @endforeach
                                </tr>
                                @foreach ($permissions as $permission)
                                  <tr>
                                    <td>{{$permission['name']}}</td>
                                   @foreach ($roles as $role)
                                    @if (in_array($permission['id'],$role['permission_ids'] ))
                                      <td><input type="checkbox" class="assign_permission" data-permission_name = "{{$permission['id']}}" data-role_id = "{{$role['id']}}" checked /></td>
                                    @else
                                       <td><input type="checkbox" class="assign_permission" data-permission_name = "{{$permission['id']}}" data-role_id = "{{$role['id']}}"/></td>
                                    @endif
                                   @endforeach
                                  </tr>
                                @endforeach
                            </thead>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                        <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--end row-->
          </div>
      </div>
    <!--end page wrapper -->
    <!--start overlay-->
    <div class="overlay toggle-icon"></div>
    <!--end overlay-->
    <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->
  </div>
  <!--end wrapper-->
  <script src="{{ URL::asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
  var userdatatable="";
  $(document).ready(function(){
    // userdatatable = $('#permissionsUser_listing').DataTable({
    //     processing: true,
    //     serverSide: true,
    //      "fnDrawCallback" : function() {
    //           $('#permissionsUser_listing').width("100%");
    //          },
    //     ajax: "{!!route('admin.ajax.permUserdata') !!}",
    //     columns: [
    //         { data: 'email', name: 'email' },
    //         { data: 'name', name: 'name' },
    //         { data: 'type', name: 'type' },
    //         {data: 'action', name: 'action', orderable: false, searchable: false}
    //     ],
    //     rowCallback: function(row, data, iDisplayIndex) {
    //       console.log(data, '-----------------------')
    //       var status = '';

    //       if(data.type === "4") {
    //         status += `<a href="#" data-staff_id="${data.id}" title="User Type"><span class='label label-rounded label-success'>Restaurant</span></a>`;
    //       } else if(data.type === "2") {
    //         status += `<a href="#" data-staff_id="${data.id}" title="User Type"><span class='label label-rounded label-success'>Sub-Admin</span></a>`;
    //       } else if(data.type === "8") {
    //         status += `<a href="#" data-staff_id="${data.id}" title="User Type"><span class='label label-rounded label-success'>Pickup Point</span></a>`;
    //       } else if(data.type === "7") {
    //         status += `<a href="#" data-staff_id="${data.id}" title="User Type"><span class='label label-rounded label-success'>Warehouse</span></a>`;
    //       } else {
    //         status += `<a href="#" data-staff_id="${data.id}" title="User Type"><span class='label label-rounded label-warning'>Customer</span></a>`;
    //       }

    //       $('td:eq(2)', row).html(status);
    //     },
    // });

    $('.assign_permission').change(function(e){
        var action = '';
        if($(this).is(':checked'))
        {
            action = 'insert';
            
        }else{
            action = 'delete';
        }
        var permission_name = $(this).attr('data-permission_name');
        var role_id = $(this).attr('data-role_id');
        assign_permission_to_role(action,permission_name,role_id);
      });
      
      function assign_permission_to_role(action,permission_name,role_id)
      {
        // console.log(action+"and"+permission_name+"and"+role_id);
        var p_name = permission_name;
        var u_id = role_id;
        if(action == 'insert')
        {
            url_link="{!! url('admin/permissions/add_role_permission' ) !!}" + "/" + u_id+'/'+p_name;
        }else{
             url_link="{!! url('admin/permissions/delete_role_permission' ) !!}" + "/" + u_id+'/'+p_name;
        }
        $.ajax({
          url:url_link,
          dataType:'json',
          type:'GET',
          success:function(result){
              if(result.status==1)
              {  
                toastr.success(result.message);
              }
              else
              {
                toastr.error(result.message);
              }
              
            }
            });
            return false;   
      }
  });
</script>
@endsection