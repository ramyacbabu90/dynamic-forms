@extends('layouts.layout-new')
@section('content')
<h4 class="mt-4">Edit Form</h4>

<div class="row">
	<div class="col-md-6">
		<div class="">
			<form role="form" class="form" method="post" action="{{ route('saveForm') }}">
				<input type="hidden" id="js-token" name="_token" value="<?php echo csrf_token(); ?>">
				<div class="form-group">
					<label for="exampleInputEmail1">Title</label>
					<input type="text" name="name" class="form-control" id="form-title"  placeholder="Enter Form Title" required="" value=@if(isset($aData['name']) && $aData['name'] ) "{{$aData['name']}}" @else "" @endif>

                    <input class="form-control" type="hidden" name="id" id="js-id" value=@if(isset($aData['id']) && $aData['id'] ) "{{$aData['id']}}" @else "" @endif >
				</div>
				<div class="form-group">
					<label for="exampleInputEmail1">Description</label>
					<input type="text" class="form-control" name="description" id="form-description"  placeholder="Enter Form Description" required="" value=@if(isset($aData['description']) && $aData['description'] ) "{{$aData['description']}}" @else "" @endif> 
				</div>
				
					<button type="submit" class="btn btn-primary" style="margin-top: 30px;">Submit</button>
				
			</form>
		</div>
	</div>
</div>
@endsection