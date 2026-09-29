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
                    <li class="breadcrumb-item active" aria-current="page">Set Permission For User ({{$user['name']}})</li>
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
                                  <th>Actions/User</th>
                                  <th>{{$user['name']}}</th>
                                </tr>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{$permission['name']}}</td>
                                        @if (in_array($permission['id'],$user_permission))
                                            <td><input type="checkbox" class="assign_permission" data-permission_name = "{{$permission['id']}}" data-user_id = "{{$user['id']}}" checked /></td>
                                        @else
                                             <td><input type="checkbox" class="assign_permission" data-permission_name = "{{$permission['id']}}" data-user_id = "{{$user['id']}}"/></td>
                                        @endif
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
 $(document).ready(function(){
        
    $('.assign_permission').change(function(e){
        var action = '';
        if($(this).is(':checked'))
        {
            action = 'insert';
            
        }else{
            action = 'delete';
        }
        var permission_name = $(this).attr('data-permission_name');
        var user_id = $(this).attr('data-user_id');
        assign_permission_to_user(action,permission_name,user_id);
      });
      
      function assign_permission_to_user(action,permission_name,user_id)
      {
        var p_name = permission_name;
        var u_id = user_id;
        if(action == 'insert')
        {
            url_link="{!! url('admin/permissions/add_user_permission' ) !!}" + "/" + u_id+'/'+p_name;
        }else{
             url_link="{!! url('admin/permissions/delete_user_permission' ) !!}" + "/" + u_id+'/'+p_name;
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