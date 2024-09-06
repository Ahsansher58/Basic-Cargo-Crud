<div class="table-responsive">
    <table id="example" class="table table-bordered align-middle" style="width:100%">
        <thead class="table-light">
            <tr>
                <th class="thbutton">Sl No</th>
                <th>Name</th>
                <th>Code</th>
                <th>Priority</th>
                <th class="thbutton">Default</th>
                <th class="thbutton">Status</th>
                {{--    @if(in_array('cargo-types-edit', $authRolePermissions)) --}}
           
                        <th class="thbutton">Edit</th>
                        {{--      @endif
               @if(in_array('cargo-types-delete', $authRolePermissions)) --}}
         
                        <th class="thbutton">Delete</th>
                        {{--   @endif  --}}
            
            </tr>
        </thead>
        <tbody>
            @php $i=1;@endphp
            @if(count($data)>0)
                @foreach($data as $key=> $list)
                    <tr>
                        <td>{{$data->firstItem() + $key}}</td>
                        <td>{{$list->name}}</td>
                        <td>{{$list->code}}</td>
                        <td>{{$list->priority}}</td>
                        <td>
                            <div class="form-check form-check-inline{{$i}}">
                                <input class="form-check-input{{$i}} btn-lg changeStatusCustom" type="checkbox" id="inlineCheckbox{{$i}}" data-url="{{route('cargo-types.changedefualt')}}" data-id="{{$list->id}}" data-status="{{$list->is_default=='0' ? '1' : '0'}}" {{ $list->is_default=='1' ? 'checked' : ''}}>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-check-inline{{$i}}">
                                <input class="form-check-input{{$i}} btn-lg changeStatusCustom" type="checkbox" id="inlineCheckbox{{$i}}" data-url="{{route('cargo-types.changestatus')}}" data-id="{{$list->id}}" data-status="{{$list->status=='0' ? '1' : '0'}}" {{ $list->status=='1' ? 'checked' : ''}}>
                            </div>
                        </td>
                        {{-- @if(in_array('cargo-types-edit', $authRolePermissions))--}}
                       
                        <td class="text-center">
                            <button data-id="{{ $list->id }}" class="btn btn-outline-secondary edit" data-bs-toggle="modal" data-bs-target="#commonModal" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                        {{--  @endif
                        @if(in_array('cargo-types-delete', $authRolePermissions)) --}}
                        <td class="text-center">
                            <a data-url="{{route('cargo-types.destroy')}}" data-id="{{$list->id}}" class="btn btn-danger deleteDataCustom" href="#" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                <i class="bi bi-trash text-white"></i>
                            </a>
                        </td>
                        {{--@endif --}}
                       
                    </tr>
                    @php $i++; @endphp
                @endforeach
            @else
                <tr>
                    <td colspan="5">No Data Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<nav class="float-start mt-2">
    <ul class="pagination">
        {!! $data->onEachSide(0)->render('cargo-types.pagination') !!}
    </ul>
</nav>
</div>

