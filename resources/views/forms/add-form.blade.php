@extends('layouts.layout-new')
@section('content')
<h4 class="mt-4">Add New Form</h4>

<div class="row">
	<div class="col-md-6">
		<div class="">
			<form role="form" class="form" id="js-add-form" method="post" action="{{ route('saveForm') }}">
				<input type="hidden" id="js-token" name="_token" value="<?php echo csrf_token(); ?>">
				<div class="form-group">
					<label for="exampleInputEmail1">Title</label>
					<input type="text" name="name" class="form-control" id="form-title"  placeholder="Enter Form Title" required="">
					@if($errors->has('name')) <p>{{ $errors->first('name') }}</p> @endif
				</div>
				<div class="form-group">
					<label for="exampleInputEmail1">Description</label>
					<textarea class="form-control" name="description" id="form-description"  placeholder="Enter Form Description" required=""></textarea> 
					@if($errors->has('description')) <p>{{ $errors->first('description') }}</p> @endif
				</div>
				
					<input type="submit" class="btn btn-primary" style="margin-top: 30px;" value="Submit">
				
			</form>
		</div>
	</div>
</div>
@endsection
@section('script')

@endsection