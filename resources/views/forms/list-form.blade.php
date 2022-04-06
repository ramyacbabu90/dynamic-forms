@extends('layouts.layout-new')
@section('content')

<h3 class="mt-4">Forms</h3>
<div class="col-md-4">
	<a class="btn btn-primary pull-right" href="{{ route('addForm') }}">Add New</a>
</div>
<div class="table-responsive" style="margin-top: 50px;">
  <table class="table">
    <tr>
    	<th>#</th>
    	<th>Title</th>
    	<th>Description</th>
    	<!-- <th>Created By</th> -->
    	<th>Created At</th>
    	<th>Action</th>
    </tr>
	    @if(isset($forms) && count($forms) > 0)
	    @php $i=1; @endphp
	    @foreach($forms as $value)
	    <tr>
	    	<td>{{$i}}</td>
	    	<td>{{ $value->name }}</td>
	    	<td>{{ $value->description }}</td>
	    	<!-- <td>{{ $value->created_by }}</td> -->
	    	<td>{{ $value->created_at }}</td>
	    	<td>
	    		<a href="{{route('formElements',[$value->id])}}" class="btn btn-success">Add Items</a>
	    		<a href="{{route('editForm',[$value->id])}}" class="btn btn-warning">Edit</a>
	    		<a href="#" class="btn btn-danger js-form-delete" data-id="{{$value->id}}">Delete</a>
	    	</td>
	    </tr>
	    @php $i=$i+1; @endphp
	    @endforeach
	    @else
	    <tbody>
	    	<tr>
	    		<td colspan="11" class="no-results text-center">No Forms Added</td>
	    	</tr>
	    </tbody>
	    @endif
  </table>
</div>
@endsection
@section('script')
<script type="text/javascript">
	$('.js-form-delete').on('click', function() {

		var id = $(this).attr('data-id');
		$.ajax({
			type: 'post',
			url: '{{ route("deleteForm") }}',
			dataType: "json",
			data: {id: id},
			success: function(res) {
				if(res.status) {
					setTimeout(function(){ location.reload(); }, 2000);
				}else{
					setTimeout(function(){ location.reload(); }, 2000);
				}
			},
			error : function(error) {
				showMessage('Request Error', 'error');
			}
		});
	});
</script>
@endsection
