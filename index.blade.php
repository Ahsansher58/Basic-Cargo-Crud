@extends('layouts.app')
@section('title','Cargo Types')
@section('pages')
<style type="text/css">
    .breadcrumb-title {
      border-right: none;
    }
</style>

    <!--breadcrumb-->
    <div class="page-breadcrumb row align-items-center justify-content-between mb-3 px-3 px-md-0">
      <div class="breadcrumb-title col-md-6"> Cargo Types
      </div>
      <div class="col-md-6">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb justify-content-md-end mb-0">
            <li class="breadcrumb-item"><i class="bi bi-info-circle-fill text-info show-help-support" data-id="40" data-bs-toggle="modal" data-bs-target="#commonModal"></i></li>
            <li class="breadcrumb-item" aria-current="page"></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="bi bi-dot"></i> Cargo Types</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

  <div class="card style_table shadow-none radius-5 min-height">
      <div class="card-header py-3">
        @include('layouts.partials.nav-menu')
        <div class="row gx-2">
          <div class="col-lg-3 col-md-3 col-8">
              <div class="position-relative">
                  <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-search"></i></div>
                  <input class="form-control ps-5" type="search" id="search" placeholder="Search">
              </div>
          </div>

          <div class="col-lg-2 col-md-2 col-4 dataTables_length" id="example_length">
            <i class="bi bi-funnel"></i>
            <select class="form-select sorting sorting-box" id="" name="example_length" aria-controls="example">
                <option value="10">Show: 10</option>
                <option value="30">Show: 30</option>
                <option value="50">Show: 50</option>
            </select>
          </div>

          <div class="col-lg-7 col-md-7">
            <div class="ms-auto float-md-end">

              <!-- <div class="btn-group">
                  <a href="{{route('cargo-types.index')}}" class="btn btn-outline-secondary me-1"><i class="bx bx-arrow-back ms-0 me-1"></i>Back</a>
              </div> -->

              <div class="btn-group">
                {{--@if(in_array('cargo-types-create', $authRolePermissions)) --}}
              
                  <button type="button" class="btn btn-primary" id="create"  data-bs-toggle="modal" data-bs-target="#commonModal"><i class="bx bx-plus"></i> Add New</button>
                  {{--@endif --}}
                
              </div>

            </div>
          </div>
        </div>
      </div>

    <div class="card-body" id="tables">

      @include('cargo-types.table')

      <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
      <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="name" />
      <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
    </div>

  <script>

     /***************** Global Delay Fucntion ***************/
     function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() {
                callback.apply(context, args);
            }, ms || 0);
        };
    }

    $(document).ready(function(){
      function clear_icon(){
        $('#id_icon').html('');
        $('#post_title_icon').html('');
      }

      function fetch_data(page, sort_type, sort_by, query){
        $.ajax({
          url:"?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query,
          success:function(data){
            $('#tables').html('');
            $('#tables').html(data);
            $(document).ready(function(){$.switcher();});
          }
        })
      }

      $(document).on('click', '#create', function(){
        $('#commonModalLabel').text('Add New Cargo Type');
        $('.save_button_span').text('Save');
        $.ajax({
          url:"{!! route('cargo-types.create') !!}",
          success:function(data){
            $('#commonModal').find('.modal-body').html('');
            $('#commonModal').find('.modal-body').html(data);
          }
        })
      })


      $(document).on('click', '.edit', function(){
        $('#commonModalLabel').text('Edit Cargo Type');
        $('.save_button_span').text('Update Cargo Type');
        var id = $(this).data('id');
        var url = `{!! route('cargo-types.edit', ':id') !!}`.replace(':id', id);

        $.ajax({
          url:url,
          success:function(data){
            $('#commonModal').find('.modal-body').html('');
            $('#commonModal').find('.modal-body').html(data);
          }
        })
      })


      $(document).on('click', '.save_button', function(event){
        event.preventDefault();
          $('.error_title').hide();
        var error = 0;

        if ($('#commonModal').find('form').find('input[name="name"]').val().trim() == '') {
          $('.error_title').show();
          error = 1;
          setTimeout(function () {
            $('.error_title').hide();
            }, 3000);
        }

        if (error == 0) {
          let formdata = new FormData($('#commonModal').find('form')[0]);

          $.ajax({
              url: $('#commonModal').find('form').attr('action'),
              data: formdata,
              processData: false,
              contentType: false,
              type: 'POST',
              success: function (response) {
                  if (response.success === true) {
                      toastr.success(response.message, 'Success.');

                      var page        = $('.pager').find('.active a').attr('href') == undefined ? 1 : $('.pager').find('.active a').attr('href').split('page=')[1];
                      var column_name = $('.sorting').val()?$('.sorting').val():'';
                      var sort_type   = $('#hidden_sort_type').val();
                      var query       = $('#search').val()?$('#search').val():'';

                      fetch_data(page, sort_type, column_name, query);

                      // hide modal
                      $('#commonModal').modal('hide');
                      $('#commonModal').find('.modal-body').html('');
                  } else {
                      toastr.error(response.message, 'Error!');
                  }
              }
          })
          .fail(function(jqXHR, textStatus, errorThrown) {
              console.log('AJAX request failed:', textStatus, errorThrown);
              toastr.error('An error occurred during the request.', 'Error!');

              // hide modal
              $('#commonModal').modal('hide');
              $('#commonModal').find('.modal-body').html('');
          });
        }
        error = 1;
      })

       /******** searching ********/
        $(document).on('keyup', '#search', delay(function() {
            var query = $('#search').val();

            if (query.length < 2 && query.length != 0) {
                return;
            }

            var column_name = $('.sorting').val();
            var sort_type   = $('#hidden_sort_type').val();
            var page        = 1 ? 1 : (this).attr('href').split('page=')[1];
            fetch_data(page, sort_type, column_name, query);
        }, 700));


      $('body').on('change', '.sorting', function(){
        var column_name   = $(this).val();
        var order_type    = $(this).data('sorting_type');
        var reverse_order = '';
        var page          = 1?1:(this).attr('href').split('page=')[1];
        var query         = $('#search').val()?$('#search').val():'';
        fetch_data(page, reverse_order, column_name, query);
      });

      $('body').on('click', '.pager a', function(event){
        event.preventDefault();
        $('#hidden_page').val(page);
        var page        = $(this).attr('href').split('page=')[1];
        var column_name = $('.sorting').val()?$('.sorting').val():'';
        var sort_type   = $('#hidden_sort_type').val();
        var query       = $('#search').val()?$('#search').val():'';
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_data(page, sort_type, column_name, query);
      });

      /*********delete data**********/
      $("body").on("click",'.deleteDataCustom', function(event){
          dataString    = {"id":$(this).data('id')};
          var UrlValue  = $(this).data('url');
          var btn = $(this);

          Swal.fire({
              title: 'Are you sure you want to delete this?',
              icon: 'warning',
              showDenyButton: true,
              showCancelButton: false,
              confirmButtonText: 'Yes',
              denyButtonText: 'No',
          }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                  $.ajax({
                  url     : UrlValue,
                  method  : 'post',
                  data    :{
                          "_token": $('meta[name="csrf-token"]').attr('content'),
                          "id": $(this).data('id')
                  },
                  headers:
                  {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  beforeSend: function( xhr ) {
                      // xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                  },
                  success : function(response){
                      if(response.success){
                          var ErroMsg = $(this).printErrorMsg(response.message);

                          $(this).Toastshow('success',ErroMsg);


                          var page        = $('.pager').find('.active a').attr('href') == undefined ? 1 : $('.pager').find('.active a').attr('href').split('page=')[1];
                          var column_name = $('.sorting').val()?$('.sorting').val():'';
                          var sort_type   = $('#hidden_sort_type').val();
                          var query       = $('#search').val()?$('#search').val():'';

                          fetch_data(page, sort_type, column_name, query);
                      }else{
                          var ErroMsg = $(this).printErrorMsg(response.message);
                          $(this).Toastshow('error',ErroMsg);
                      }
                  },
                  error: function (data) {
                    console.log("error ",data);
                  }
              });
              }
          });
      });

      /*********change status**********/
      $("body").on("click",'.changeStatusCustom', function(event){
          dataString       = {"id":$(this).data('id'),"status":$(this).data('status')};
          var UrlValue     = $(this).data('url');
          var status       = $(this).data('status');
          var changeStatus = $(this);
          var btn = $(this);

          Swal.fire({
              title: 'Are you sure you want to change status?',
              icon: 'warning',
              showDenyButton: true,
              showCancelButton: false,
              confirmButtonText: 'Yes',
              denyButtonText: 'No',
          }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      url     : UrlValue,
                      method  : 'post',
                      data    :dataString,
                      headers:
                      {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      beforeSend: function( xhr ) {
                          // xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                      },
                      success : function(response){
                          if(response.success){
                              var ErroMsg = $(this).printErrorMsg(response.message);
                              $(this).Toastshow('success',ErroMsg);

                              var page        = $('.pager').find('.active a').attr('href') == undefined ? 1 : $('.pager').find('.active a').attr('href').split('page=')[1];
                              var column_name = $('.sorting').val()?$('.sorting').val():'';
                              var sort_type   = $('#hidden_sort_type').val();
                              var query       = $('#search').val()?$('#search').val():'';

                              fetch_data(page, sort_type, column_name, query);
                          } else {
                              var ErroMsg = $(this).printErrorMsg(response.message);
                              if (ErroMsg === '') {
                                  ErroMsg = "Something went wrong!";
                              }
                              $(this).Toastshow('error',ErroMsg);

                              if (status == 1) {
                                  changeStatus.parent().find('.ui-switcher').attr('aria-checked', false);
                              } else {
                                  changeStatus.parent().find('.ui-switcher').attr('aria-checked', true);
                              }
                          }
                      },
                      error: function (data) {
                          console.log("error ",data);

                          if (status == 1) {
                              changeStatus.parent().find('.ui-switcher').attr('aria-checked', false);
                          } else {
                              changeStatus.parent().find('.ui-switcher').attr('aria-checked', true);
                          }
                      }
                  });
              }
              else if (result.isDenied) {
                  var page        = $('.pager').find('.active a').attr('href') == undefined ? 1 : $('.pager').find('.active a').attr('href').split('page=')[1];
                  var column_name = $('.sorting').val()?$('.sorting').val():'';
                  var sort_type   = $('#hidden_sort_type').val();
                  var query       = $('#search').val()?$('#search').val():'';

                  fetch_data(page, sort_type, column_name, query);
              }
              //return false;
          });
      //return false;
      });
        
    });
  </script>
@endsection
