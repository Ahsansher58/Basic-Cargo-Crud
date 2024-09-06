<form class="row g-3 auto-width" action="{{ route('cargo-types.store')}}" method="post" enctype='multipart/form-data'>
    @csrf
    <div class="col-md-12">
        <label for="name" class="form-label"> Name <span class="text-danger"><b>*</b></span></label>
        <input type="text" class="form-control" id="name" name="name" aria-describedby="inputGroupPrepend" required>
        <span class="text-danger error_title" style="display:none;">This field is required.</span>
    </div>
    <div class="col-md-6">
        <label for="code" class="form-label">Code</label>
        <input type="text" class="form-control" id="code" name="code" aria-describedby="inputGroupPrepend" required>
        <span class="text-danger error_code" style="display:none;">This field is required.</span>
    </div>
    <div class="col-md-6">
        <label for="priority" class="form-label">Priority</label>
        <input type="text" class="form-control" id="priority" name="priority" aria-describedby="inputGroupPrepend" required value="{!! $priority + 1 !!}">
        <span class="text-danger error_priority" style="display:none;">This field is required.</span>
    </div>
</form>
<script type="text/javascript">
      $("#priority").on('input', function() {
          const onlyNumbers = $(this).val().replace(/[^0-9]/g, '');
          $("#priority").val(onlyNumbers);
      });
</script>