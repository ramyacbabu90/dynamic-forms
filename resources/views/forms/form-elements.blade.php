@extends('layouts.layout-new')
@section('css')
<style type="text/css">
	.error {
		color: red;
	}
</style>
@endsection
@section('content')


<h3 class="mt-4">Form Elements</h3>

<p>Form Title: <code>{{$form['name']}}</code></p>

<div class="row">
	<form role="form" class="form" id="js-add-form-elements">
		<input type="hidden" name="form_id" id="js-form-id" value="{{$form['id']}}">
        <input type="hidden" name="element_id" id="js-element-id" value="">
		<input type="hidden" id="js-token" name="_token" value="<?php echo csrf_token(); ?>">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleInputEmail1">Label</label>
					<input type="text" name="label" id="js-label" class="form-control" required="">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleInputEmail1">Name</label>
					<input type="text" name="name" id="js-name" class="form-control" required="">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleInputEmail1">Type</label>
					<select name="element_type" id="js-element-type" class="form-control" required="">
						<option value="1">Text</option>
						<option value="2">Number</option>
						<option value="3">Drop Down</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleInputEmail1">Placeholder</label>
					<input type="text" name="placeholder" id="js-placeholder" class="form-control" required="">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="div-options" style="display: none;">
				<div class="row">
					<input type="hidden" id="js-last-row-count" value="1">
					<div class="col-sm-12 col-md-12 js-each-option" id="">
						<div class="row">
							<div class="col-md-6 form-group">
								<label class="control-label">Option1:</label>
								<input type="text" placeholder="Enter your option" value="" name="choice_1_text" id="js-choice-1" class="form-control js-option-text">
							</div>
							
							<div class="col-md-3 form-group">
								<a class="btn btn-sm btn-primary js-add-choice"  style="margin-top: 27px;">Add</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<button class="btn btn-primary" style="margin-top: 30px;" value="Submit" id="js-save-element">Save Element</button>

	</form>
</div>
<div class="row">
	<div class="table-responsive" id="form-elements-table" style="margin-top: 50px;">
		<table class="table">
			<tr>
				<th>#</th>
				<th>Element Type</th>
				<th>Label</th>
				<th>Name</th>
				<th>Placeholder</th>
				<!-- <th>Created By</th> -->
				<th>Created At</th>
				<th>Action</th>
			</tr>
			@if(isset($elements) && count($elements) > 0)
			@php $i=1; @endphp
			<tbody>
			@foreach($elements as $element)
			<tr>
				<td>{{$i}}</td>
				<td>@if($element->element_type == 1)
					Text Field
					@elseif($element->element_type == 2)
					Number Field
					@elseif($element->element_type==3)
					Dropdown
					@endif
				</td>
				<td>{{$element->label}}</td>
				<td>{{$element->name}}</td>
				<td>{{$element->placeholder}}</td>
				<td>{{$element->created_at}}</td>
				<td>
					<a href="#" id="js-edit-element-button_{{$element->id}}" data-id="{{ $element->id}}" class="btn btn-sm btn-info js-edit-element-button">Edit</a>
					<a href="#" id="js-edit-element-button_{{$element->id}}" data-id="{{ $element->id}}" class="btn btn-sm btn-warning js-delete-element-button">Delete</a>
				</td>
			</tr>
			@php $i=$i+1; @endphp
			@endforeach
			</tbody>
			@else
			<tbody>
				<tr>
					<td colspan="11" class="no-results text-center">No Elements Added</td>
				</tr>
			</tbody>
			@endif
		</table>
	</div>
</div>
@endsection
@section('script')

<script src="{{ asset('vendor/jquery/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){

		var basePath = "{{url('/')}}";
		// console.log(basePath);
		$("#js-add-form-elements").validate({
			rules: {
				"label"   : {
					required : true,
					begining_space_not_allowed  :true,
				},
				"name":{
					required : true,
				},
			},
			messages: {

			},
			highlight: function (element) {
				$("select").on('change', function () {
					if (element.value) {
						$('#' + element.id + '-error').hide();
					} else {
						$('#' + element.id + '-error').show();
					}
				});
			},

			submitHandler: function(form,e) {
				e.preventDefault();

				var oData = {};

				oData.label = $('#js-label').val();
				oData.name = $('#js-name').val();
				oData.placeholder = $('#js-placeholder').val();
				oData.element_type = $('#js-element-type').val();
				oData.form_id = $('#js-form-id').val();

				if($('#js-element-id').val()!== '') {
					oData.id = $('#js-element-id').val();
				}

				if(oData.element_type == 3) {

					var options = [];
					$('#div-options .js-each-option').each(function(index, element){
						var multiple_options= {};
						multiple_options.op_text = $(element).find('.js-option-text').val();
						options.push(multiple_options);
					});
					oData.options = options;
				}
				
				// $("tr.item").each(function() {
				// 	var quantity1 = $(this).find("input.name").val(),
				// 	quantity2 = $(this).find("input.id").val();
				// });
                //option array ends

                var url = '{{ route("saveFormElements")}}';
                $.ajax({
                	type: 'POST',
                	url: url,
                	dataType: "json",
                	data: {data: oData},
                	success: function(res) {

                		if(res.status) {
                			
                            setTimeout(function () {                       
                                location.href= basePath+'/form-elements/'+oData.form_id;
                            });
                			
                		}else{
                			console.log(res.msg);
                		}
                	},
                	error : function(error) {
                		console.log('Request Error');
                	}
                });
                return false;
            }
        });//end submit handler


		$('#js-element-type').on('change', function() {

			var val = $(this).val();
	        if(val == 3) {//text field
	            $('#div-options').show();
	        }else { 
				$('#div-options').hide();
	        }
    	});

		$(document).on("click",".js-add-choice",function(e) {

			e.preventDefault();
			var rowCount = parseInt($('#js-last-row-count').val());
			var html = '<div class="row"><div class="col-sm-12 col-md-12 js-each-option" id=""><div class="row">';
			html += '<div class="col-md-6 form-fields-single"><label class="control-label">Option'+ (rowCount + 1) +'</label>';
			html += '<input type="text" value="" placeholder="Enter your option" name="choice_'+ (rowCount + 1) +'_text" id="js-choice-'+ (rowCount + 1) +'" class="form-control js-option-text" required></div>';
			html+= '<div class="col-md-3 form-fields-add-button sameLineFitter"><button class="btn btn-sm btn-sm btn-danger js-remove-choice"  style="margin-top: 27px;"><i class="fa fa-trash"></i>Delete</button></div>';
			html+= '</div></div></div>';                            

			$('#div-options').append(html); 
			$('#js-last-row-count').val(rowCount + 1);
		});

		$(document).on("click", ".js-remove-choice", function(e) {
			e.preventDefault();
			$(this).closest(".js-each-option").remove();
		});

		$(document).on("click", ".js-delete-element-button", function(e) {
			e.preventDefault();
			
			var element_id = $(this).attr('data-id');
			var form_id = $('#js-form-id').val();;

			if(deleteItem()) {

				$.ajax({
					url  : basePath+'/delete-form-elements/'+element_id,
					type : 'DELETE',
					
					success : function (res) {
						if(res.status) {
							
							setTimeout(function () {                       
                                location.href= basePath+'/form-elements/'+form_id;
                            });
						} else {
							console.log(res.msg);
							setTimeout(function () {                       
                                location.href= basePath+'/form-elements/'+form_id;
                            });
						}
					}
				})
			}

		});

		$(".js-edit-element-button").on("click", function(e) {

                e.preventDefault();
                console.log('dddd');
                var element_id = $(this).attr('data-id');

                $.ajax({
                        url  : '{{ route("editFormElements")}}',
                        type : 'post',
                        data : {
                            //_token : csrf,
                            id:element_id
                        },

                        success : function (res) {
                            // console.log(res);
                            if(res.status) {
                                var data = res.data;
                                $('#js-label').val(data.label);
                                $('#js-name').val(data.name);
                                $('#js-placeholder').val(data.placeholder);
                                $('#js-element-type').val(data.element_type);
                                $('#js-form-id').val(data.form_id);
                                $('#js-element-id').val(data.id);
                                $('#js-save-element').html('Update');
                                
                                if(data.element_type == 3) {
                                   $('#div-options').show(); 
                                   var count = data.options.length;
                                   for(var i=0;i<count;i++) {
                                        if(i==0) {
                                            $('#js-choice-1').val(data.options[i].value);
                                        }else{
                                            appendChoice(data.options[i].value)
                                        }
                                   }
                                }else{
                                	$('#div-options').hide();
                                } 
                                                   
                            }
                            else {
                                console.log(res.msg);
                            }
                        },
                        error: function () {
                            console.log('Request Error');
                        }

                    });//end ajax
    
            });//end option remove button



	});//end ready function

function appendChoice(option) {
  
  var rowCount = parseInt($('#js-last-row-count').val());
  var html = '<div class="row"><div class="col-sm-12 col-md-12 js-each-option" id=""><div class="row">';
  html += '<div class="col-md-6 form-fields-single"><label class="control-label">Option'+ (rowCount + 1) +'</label>';
  html += '<input type="text" value="'+option+'" placeholder="Enter your option" name="choice_'+ (rowCount + 1) +'_text" id="js-choice-'+ (rowCount + 1) +'" class="form-control js-option-text" required></div>';
  html+= '<div class="col-md-3 form-fields-add-button sameLineFitter"><button class="btn btn-sm btn-danger js-remove-choice"  style="margin-top: 27px;"><i class="fa fa-trash"></i>Delete</button></div>';
  html+= '</div></div></div>';
                              
  $('#div-options').append(html); 
  $('#js-last-row-count').val(rowCount + 1);  
}

function deleteItem() {
	if (confirm("Are you sure you want to delete?")) {
		return true;
	}
	return false;
}

</script>
@endsection