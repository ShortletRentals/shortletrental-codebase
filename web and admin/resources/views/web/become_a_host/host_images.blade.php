@extends('layouts.web.master')

@section('content')
<style>
  .become_property_images{
    flex-direction: column;
  }

  .become_property_images p{
    margin-top:15px;
    margin-bottom:0;
  }
</style>
<style type="text/css">
    /*body{
        background: #f7fbf8; 
    }
    h1{
        font-weight: bold;
        font-size:23px;
    }*/
    img {
        display: block;
        max-width: 100%;
    }
    .preview {
        text-align: center;
        overflow: hidden;
        width: 160px; 
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }
    /*input{
        margin-top:40px;
    }*/
    .section{
        margin-top:150px;
        background:#fff;
        padding:50px 30px;
    }
    .modal-lg{
        max-width: 1000px !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>

<?php ///Page 8 ?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <form method="POST" action="" class="host_submit_form" id="host_submit_form" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6 grad-bg">
                <h3>Upload Your Property Photos (Multiple or one)</h3>
            </div>
            <div class="col-md-6">
              <div class="property-cont upload-sec">
                <div class="property-list">
                  <div class="upload_cls">
                    <div class="file_input">
                      <input type="file" name="image" id="file-upload"  class="image" accept="image/png, image/gif, image/jpeg">
                      <span for="fileToUpload" class="become_property_images"><img src="{{ URL::asset('assets/web/img/upload_icon_1.png')}}">
                        <p style="color:black">Drag files here or <a href="javascript:void(0)">browse for files</a> </p>
                      </span>
                      
                    </div>
                  </div>
                   <div class="img_preview_div">
                       <!-- <img src="" style="width: 200px;display: none;" class="show-image"> -->
                   </div> 
                </div>
                <div class="previewing"> </div>
                <div class="property-footer">
                  <div class="btn_group">
                    <a href="{{ route('web.become_a_host.host_price') }}" class="btn secondary_btn">Back</a>
                    <input type="submit" value="Next" name="" class="btn primary_btn finish_btn">
                    <!-- <a href="javascript:void(0)" class="btn primary_btn image_next">Finish</a> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section> 

   <!--  <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 section text-center">
                <h1>Drag files here or</h1>
                <form action="" method="POST">
                    @csrf
                    <input type="file" name="image" class="image">
                    <input type="hidden" name="image_base64">
                    <img src="" style="width: 200px;display: none;" class="show-image"> 
  
                    <br/>
                    <button class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div> -->


    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Crop Image</h5>
                   <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                   <!--  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div> 
  </main>

  <script>
    function base64ToFile(base64String, fileName, fileType) {
        const base64Data = base64String.split(',')[1];

        const binaryData = atob(base64Data);

        const arrayBuffer = new ArrayBuffer(binaryData.length);
        const uint8Array = new Uint8Array(arrayBuffer);
        for (let i = 0; i < binaryData.length; i++) {
            uint8Array[i] = binaryData.charCodeAt(i);
        }

        const blob = new Blob([arrayBuffer], { type: fileType });

        const file = new File([blob], fileName, { type: fileType });

        return file;
    }
    function resizeAndCompressImage(imageFile, compressionQuality) {
        return new Promise((resolve, reject) => {
            var image = new Image();
            image.onload = function() {
                var canvas = document.createElement("canvas");
                var width = 400;
                var height = (image.height / image.width) * width;
                canvas.width = width;
                canvas.height = height;
                var ctx = canvas.getContext("2d");
                ctx.drawImage(image, 0, 0, width, height);
                var compressedImage = canvas.toDataURL("image/jpeg", compressionQuality); // Adjust the quality (e.g., 0.5) as needed
                resolve(compressedImage);
            };
            image.src = URL.createObjectURL(imageFile);
        });
    }
  /*var file_upload = [];
  $("#file-upload").change(async function(event) {
      var fileName = document.getElementById("file-upload").value;
      var idxDot = fileName.lastIndexOf(".") + 1;
      var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
      if (extFile != "jpg" && extFile != "jpeg" && extFile != "png") {
          alert("Only jpg/jpeg and png files are allowed!");
          return false;
      }

      $(':input[type="submit"]').prop('disabled', true);

      var imageFile = event.target.files[0];
      var compressionQuality = 0.5; // Adjust the compression quality as needed
      var compressedImage = await resizeAndCompressImage(imageFile, compressionQuality);
      var imageFileName = "compressed_image.jpg"; 
      var imageFileType = "image/jpeg";
      var finalimage = base64ToFile(compressedImage, imageFileName, imageFileType);
      const formData = new FormData();
      var csrf_token =  "{{ csrf_token() }}";
      formData.append('_token', csrf_token);
      formData.append('image', finalimage);

      $.ajax({
          url: '{{ route("web.images_upload") }}',
          dataType: 'json',
          data: formData,
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function(res) {
              file_upload.push(res.data);
              $('.img_preview_div').append(
                  $('<div class="single-img"><img width="100" height="100" src="https://d1o88e3pxnk9ri.cloudfront.net/'+res.data.file+'"/></div>')
              );
              $(':input[type="submit"]').prop('disabled', false);
          },
          error: function(err) {
              console.error('Error uploading image:', err);
              $(':input[type="submit"]').prop('disabled', false);
          }
      });
  });*/


  var file_upload = [];

$("#file-upload").change(function(event) {
    var fileName = document.getElementById("file-upload").value;
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    if (extFile != "jpg" && extFile != "jpeg" && extFile != "png") {
        alert("Only jpg/jpeg and png files are allowed!");
        return false;
    }

    $(':input[type="submit"]').prop('disabled', true);
    $('.finish_btn').html('Processing...');
    $('.finish_btn').val('Processing...');

    var imageFile = event.target.files[0];
    const formData = new FormData();
    var csrf_token = "{{ csrf_token() }}";
    formData.append('_token', csrf_token);
    formData.append('image', imageFile);

    $.ajax({
        url: '{{ route("web.images_upload") }}',
        dataType: 'json',
        data: formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {
            file_upload.push(res.data);
            $('.img_preview_div').append(
                $('<div class="single-img"><img width="100" height="100" src="https://d1o88e3pxnk9ri.cloudfront.net/' + res.data.file + '"/></div>')
            );
            $(':input[type="submit"]').prop('disabled', false);
            $('.finish_btn').html('Next');
            $('.finish_btn').val('Next');
        },
        error: function(err) {
            console.error('Error uploading image:', err);
            $(':input[type="submit"]').prop('disabled', false);
            $('.finish_btn').html('Next');
            $('.finish_btn').val('Next');
        }
    });
});


   /*function resizeAndCompressImage(imageFile) {
        var canvas = document.createElement("canvas");
        var image = new Image();
        image.src = imageFile.url;

        var width = image.width;
        var height = image.height;

        canvas.width = 400;
        canvas.height = 300;

        var ctx = canvas.getContext("2d");
        ctx.drawImage(image, 0, 0);

        var resizedImage = canvas.toDataURL("image/jpeg");

        var compressedImage = resizedImage.replace("image/jpeg", "image/jpeg; quality=50");

        return compressedImage;
    }
    var file_upload = [];
    $("#file-upload").change(function(){

      

      var fileName = document.getElementById("file-upload").value;
      var idxDot = fileName.lastIndexOf(".") + 1;
      var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
      if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){

      }else{
        alert("Only jpg/jpeg and png files are allowed!");
        return false;
      }

      const form = document.getElementById("host_submit_form");
      const submitter = document.querySelector("button[value=save]");
      const formData = new FormData(form, submitter);
  
      $(':input[type="submit"]').prop('disabled', true);

    //  var formData = new FormData('form');
      var imageFile = event.target.files[0];
      var compressedImage = resizeAndCompressImage(imageFile);

      console.log(imageFile);
      console.log(compressedImage);
      formData.append('image',compressedImage);
      $.ajax({
          url: '{{ route("web.images_upload") }}',
          dataType: 'json',
          data: formData,
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function(res) {
            file_upload.push(res.data)
          console.log(res.data);
          $('.img_preview_div').append(
              $('<div class="single-img"><img width="100" heigth="100" src="https://d1o88e3pxnk9ri.cloudfront.net/'+res.data.file+'"/></div>')
            )
            $(':input[type="submit"]').prop('disabled', false);
          }
        });
		});*/

    /*Add card Start */
    $(document).on('submit', "#host_submit_form", function(e) {
      e.preventDefault();
      const fi = document.getElementById('file-upload');
      
      if(fi.files.length > 0){
        $(':input[type="submit"]').prop('disabled', true);
        var _this = $(this);
        var formData = new FormData(this);
        
        formData.append('file_upload',JSON.stringify(file_upload));
        formData.append('type',sessionStorage.getItem("host_type"));
        formData.append('category',sessionStorage.getItem("host_category"));
        formData.append('country_id',sessionStorage.getItem("host_country_id"));
        formData.append('province_id',sessionStorage.getItem("host_province_id"));
        formData.append('city_id',sessionStorage.getItem("host_city_id"));
        formData.append('area',sessionStorage.getItem("host_area"));
        formData.append('postal_code',sessionStorage.getItem("host_postal_code"));
        formData.append('address',sessionStorage.getItem("host_address"));
        formData.append('latitude',sessionStorage.getItem("host_latitude"));
        formData.append('longitude',sessionStorage.getItem("host_longitude"));
        formData.append('street_type',sessionStorage.getItem("host_street_type"));
        formData.append('street_name',sessionStorage.getItem("host_street_name"));
        formData.append('street_number',sessionStorage.getItem("host_street_number"));
        formData.append('house_number',sessionStorage.getItem("host_house_number"));
        formData.append('floor',sessionStorage.getItem("host_floor"));
        formData.append('staircase',sessionStorage.getItem("host_staircase"));
        formData.append('elevator',sessionStorage.getItem("host_elevator"));
        formData.append('apartment_door_no',sessionStorage.getItem("host_apartment_door_no"));
        formData.append('beds',sessionStorage.getItem("host_beds"));
        formData.append('bedrooms',sessionStorage.getItem("host_bedrooms"));
        formData.append('bathrooms',sessionStorage.getItem("host_bathrooms"));
        formData.append('bathrooms_shower',sessionStorage.getItem("host_bathrooms_shower"));

        formData.append('kitchens',sessionStorage.getItem("host_kitchens"));
        formData.append('amenity',sessionStorage.getItem("host_amenity"));
        formData.append('title',sessionStorage.getItem("host_title"));
        formData.append('additional_notes',sessionStorage.getItem("host_description"));
        formData.append('extra_services',sessionStorage.getItem("host_services"));
        formData.append('price',sessionStorage.getItem("host_price"));

        formData.append('max_guest',sessionStorage.getItem("max_guest"));
        formData.append('cctv',sessionStorage.getItem("cctv"));
        formData.append('cctv_locations',sessionStorage.getItem("cctv_locations"));
        formData.append('wifi_username',sessionStorage.getItem("wifi_username"));
        formData.append('wifi_password',sessionStorage.getItem("wifi_password"));
        formData.append('no_of_television',sessionStorage.getItem("no_of_television"));
        formData.append('location_of_television',sessionStorage.getItem("location_of_television"));

        formData.append('pets_allow',sessionStorage.getItem("pets_allow"));
        formData.append('response_time',sessionStorage.getItem("response_time"));
        formData.append('party_rate_commission',sessionStorage.getItem("party_rate_commission"));
        formData.append('apartment_responsible',sessionStorage.getItem("apartment_responsible"));
        formData.append('other_responsibility',sessionStorage.getItem("other_responsibility"));
        formData.append('estate_located',sessionStorage.getItem("estate_located"));
        formData.append('estate_name',sessionStorage.getItem("estate_name"));
        formData.append('landmark',sessionStorage.getItem("landmark"));
        formData.append('tarred_located',sessionStorage.getItem("tarred_located"));
        // formData.append('tarred_road',sessionStorage.getItem("tarred_road"));
        formData.append('home_support',sessionStorage.getItem("home_support"));
        formData.append('people_allowed_parties',sessionStorage.getItem("people_allowed_parties"));
        formData.append('standout_amenities',sessionStorage.getItem("standout_amenities"));
        formData.append('minimum_no_of_nights',sessionStorage.getItem("minimum_no_of_nights"));
        // formData.append('allow_a_day_booking',sessionStorage.getItem("allow_a_day_booking"));
        formData.append('house_rule',sessionStorage.getItem("house_rule"));
        // formData.append('building',sessionStorage.getItem("building"));

        $.ajax({
          url: '{{ route("web.host_submit_form") }}',
          dataType: 'json',
          data: formData,
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function(res) {
            if (res.status === true) {
            // toastr.success(res.message);
            localStorage.removeItem("host_type");
            localStorage.removeItem("host_category");
            localStorage.removeItem("host_country_id");
            localStorage.removeItem("host_province_id");
            localStorage.removeItem("host_city_id");
            localStorage.removeItem("host_area");
            localStorage.removeItem("host_postal_code");
            localStorage.removeItem("host_address");

            localStorage.removeItem("host_latitude");
            localStorage.removeItem("host_longitude");
            localStorage.removeItem("host_street_type");
            localStorage.removeItem("host_street_name");
            localStorage.removeItem("host_street_number");
            localStorage.removeItem("host_house_number");
            localStorage.removeItem("host_floor");
            localStorage.removeItem("host_staircase");
            localStorage.removeItem("host_elevator");
            localStorage.removeItem("host_apartment_door_no");
            localStorage.removeItem("host_beds");
            localStorage.removeItem("host_bedrooms");
            localStorage.removeItem("host_bathrooms");
            localStorage.removeItem("host_bathrooms_shower");
                        
            localStorage.removeItem("host_kitchens");
            localStorage.removeItem("host_amenity");
            localStorage.removeItem("host_title");
            localStorage.removeItem("host_description");
            localStorage.removeItem("host_services");
            localStorage.removeItem("host_price");

            localStorage.removeItem("max_guest");
            localStorage.removeItem("cctv");
            localStorage.removeItem("cctv_locations");
            localStorage.removeItem("wifi_username");
            localStorage.removeItem("wifi_password");
            localStorage.removeItem("no_of_television");
            localStorage.removeItem("location_of_television");

            localStorage.removeItem("pets_allow");
            localStorage.removeItem("response_time");
            localStorage.removeItem("party_rate_commission");
            localStorage.removeItem("standout_amenities");
            localStorage.removeItem("minimum_no_of_nights");
            // localStorage.removeItem("allow_a_day_booking");
            localStorage.removeItem("house_rule");
            // localStorage.removeItem("building");
            setInterval(function () {  
              window.location.replace("{{ route('web.host_profile') }}");
              // window.location.replace("{{ url('/') }}");
              // window.location.reload();
            }, 2000); 
            } else {
              toastr.error(res.message);
            }
          },
          error: function(jqXHR, textStatus, textStatus) {
            if (jqXHR.responseJSON.errors) {
            $.each(jqXHR.responseJSON.errors, function(index, value) {
              toastr.error(value)
            });
            } else {
            toastr.error(jqXHR.responseJSON.message)
            }
          }
        });
      }else{
        $(':input[type="submit"]').prop('disabled', false);
        alert('Please select atleast one image.');
        return false;
      }
    });
    // $(document).on('click', ".image_next", function(e) {
    //   var image = $("#file-upload").target.files.length;

    //   if(image != ''){
    //     alert(image);
    //       // sessionStorage.setItem("host_price", title);
    //       // window.location.replace("{{ route('web.become_a_host.host_description') }}");
    //   }else{
    //     alert('Please enter valid price.');
    //     // return false;
    //   }
    // });
    // $("#file-upload").change(function(){
    //   var fileObj = this.files[0];
    //   var imageFileType = fileObj.type;
    //   var imageSize = fileObj.size;

    //   var file = $('#file-upload')[0].files[0].name;
    //   $(this).prev('label').text(file);
    
    //   var match = ["image/jpeg","image/png","image/jpg"];
    //   if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]))){
    //     $('#previewing').attr('src','images/image.png');
    //     toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg and png Images Type Allowed!!');
    //     return false;
    //   }else{
    //     //console.log(imageSize);
    //     if(imageSize < 5000000){
    //       var reader = new FileReader();
    //       reader.onload = imageIsLoaded;
    //       reader.readAsDataURL(this.files[0]);
    //     }else{
    //       toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
    //       return false;
    //     }
    //   }
    // });
  
    // function imageIsLoaded(e){
    //   //console.log(e);
    //   $("#file-upload").css("color","green");
    //   $('#previewing').attr('src',e.target.result);
    // }


  /*Add card End*/
  </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<script>
       /* var $modal = $('#modal');
        var image = document.getElementById('image');
        var cropper;
  
        $("body").on("change", ".image", function(e){
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
                $modal.modal('show');
            };
  
            var reader;
            var file;
            var url;
  
            if (files && files.length > 0) {
                file = files[0];
  
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                reader.readAsDataURL(file);
                }
            }
        });
  
        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });
  
        $("#crop").click(function(){
            canvas = cropper.getCroppedCanvas({
                width: 160,
                height: 160,
            });
  
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result; 
                    $("input[name='image_base64']").val(base64data);
                    $(".show-image").show();
                    $(".show-image").attr("src",base64data);
                    $("#modal").modal('toggle');

                    var imageFileName = "compressed_image.jpg"; 
                    var imageFileType = "image/jpeg";
                    var finalimage = base64ToFile(base64data, imageFileName, imageFileType);
                    const formData = new FormData();
                    var csrf_token =  "{{ csrf_token() }}";
                    formData.append('_token', csrf_token);
                    formData.append('image', finalimage);

                    $.ajax({
                        url: '{{ route("web.images_upload") }}',
                        dataType: 'json',
                        data: formData,
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            file_upload.push(res.data);
                            $('.img_preview_div').html(
                                $('<div class="single-img"><img width="100" height="100" src="https://d1o88e3pxnk9ri.cloudfront.net/'+res.data.file+'"/></div>')
                            );
                            $(':input[type="submit"]').prop('disabled', false);
                        },
                        error: function(err) {
                            console.error('Error uploading image:', err);
                            $(':input[type="submit"]').prop('disabled', false);
                        }
                    });
                }
            });
        });*/
    </script>

@endsection