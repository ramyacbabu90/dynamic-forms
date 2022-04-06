<?php

namespace App\Http\Controllers;
use App\Models\Form;
use App\Models\FormElements;
use App\Models\OptionValues;
use Illuminate\Http\Request;
use Auth;
use DB;

use Validator;

class FormController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}


	public function listForm(Request $request) {

		$oUser              = Auth::user();
		$loggedUserId       = $oUser->id;

		$forms = Form::where('status',1)->get();
		return view('forms.list-form', [
			'forms' => $forms,
		]);
	}


	public function addForm(Request $request) {

		$oUser              = Auth::user();
		$loggedUserId       = $oUser->id;
		return view('forms.add-form');
	}

	public function editForm(Request $request) {

		$oLoggedUser  = Auth::user();
		$userId       = $oLoggedUser->id;
		try {
			$aData = Form::find($request->id);
			return view('forms.edit-form', ['userId'  => $userId, 'aData' => $aData]); 

		} catch (Exception $e) {
			ErrorLog::log($e->getMessage(), 'error', __METHOD__);
		}
	}

	public function saveForm(Request $request) {

		if ($request->getMethod() == 'POST') { 

			try {

				$oLoggedUser  = Auth::user();
				$userId       = $oLoggedUser->id;

				$aRequest = $request->all();
				$message = '';

				$aRules   = [
					'name' => 'required|min:2|max:60',
					'description' => 'required|min:2|max:60',
				];

				$validation = Validator::make($aRequest, $aRules);
				$aError     = [];
				if($validation->fails()) {
					return redirect()->back()->withErrors($validator->errors());
				}

				if(isset($aRequest['id']) && $aRequest['id'] != '' ) {
					$form = Form::find($aRequest['id']);
					$message = 'Form Updated succesfully';
					
				} else {
					$form = new Form();
					$message = 'Form Added succesfully';
					$form->created_by = $userId;
				}

				$form->name = $aRequest['name'];
				$form->description = $aRequest['description'];

				$form->save();

				$redirect = "list-form"; 
				return redirect($redirect);

			} catch (Exception $e) {

				
			}
		}
	}

	public function deleteForm(Request $request) {

		try{

			$data= $request->all();
			$id = $data['id'];
			$delete = Form::where('id', '=', $id )->update(['status' => 0]);
			if($delete) {
				return response()->json([
					'status' => true,
					'msg'    => 'Item Deleted succesfully'
				]); 
			}else {
				return response()->json([
					'status' => false,
					'msg'    => 'Something Went wrong. Please try again...!!'
				]);  
			}

		} catch(Exception $e) {

			return response()->json([
				'status' => false,
				'msg'    => 'Server Error'
			]);
		}
	}

	public function formElements($id) {

		if($id && Form::find($id)) {
			
			$oLoggedUser  = Auth::user();
			$userId       = $oLoggedUser->id;
			$elements = FormElements::where('form_id', $id)->where('status',1)->get();
			$form = Form::find($id);

			return view('forms.form-elements', ['userId'  => $userId, 'form' => $form, 'elements' => $elements]);
		
		}else{
			$redirect = "list-form"; 
			return redirect($redirect);
		}
	}

	public function saveFormElements(Request $request) {

		if ($request->getMethod() == 'POST') {

			try {
				$oLoggedUser  = Auth::user();
				$userId       = $oLoggedUser->id;

				$aRequest     = $request->data;
				$message = '';
				
				$aRules   = [
					'label' => 'required',
					'name' => 'required',
					'placeholder' => 'required',
					'element_type' => 'required|integer',
				];

				if($aRequest['element_type'] == 3){//drop down
					$aRules['options'] = 'required|array';
				}

				$validation = Validator::make($aRequest, $aRules);
				$aError     = [];
				if($validation->fails()) {
					$err = Common::prepareValidationErrorMsg($validation->errors());
					return response()->json([
						'status' => false,
						'msg'    => $err,
					]);
				}

				DB::beginTransaction();

				if(isset($aRequest['id']) && $aRequest['id'] != '' ) {
					$question = FormElements::find($aRequest['id']);
					$message = 'Element Updated succesfully';
				} else {
					$question = new FormElements();
					$message = 'Element Added succesfully';
				}

				$question->form_id = $aRequest['form_id'];
				$question->element_type = $aRequest['element_type'];
				$question->label = $aRequest['label'];
				$question->name = $aRequest['name'];
				$question->placeholder = $aRequest['placeholder'];
				$question->save();

				if($aRequest['element_type'] == 3) {

					if(isset($aRequest['id']) && $aRequest['id'] != '' ){
						OptionValues::where('element_id',$aRequest['id'])->delete();
					}
					foreach ($aRequest['options'] as $key => $value) {
						$options = new OptionValues();

						$options->element_id = $question->id;
						$options->value = $value['op_text'];
						$options->save();
					}
				}

				DB::commit();

				return response()->json([
					'status' => true, 
					'msg' => $message
				], 200);

			} catch (Exception $e) {

				return response()->json([
					'status' => false, 
					'msg' => 'Server error.Try again'
				], 500);
			}
		}
	}

	public function editFormElements(Request $request) {

		try {

			$aData= $request->all();
			$q_id = $aData['id'];

			if($q_id && FormElements::find($q_id)) {

				$data = FormElements::find($q_id);

				if($data['element_type'] == 3) {
					$data['options'] = OptionValues::where('element_id', $data['id'])->get()->toArray();
				}

				return response()->json([
					'status' => true,
					'msg'    => 'Data fetched succesfully',
					'data'	 =>	$data
				]);

			}else{
				return response()->json([
					'status' => false,
					'msg'    => 'Element not found'
				]);
			}

		} catch (Exception $e) {

			return response()->json([
				'status' => false,
				'msg'    => $e->getMessage()
			], 500);
		}
	}


	public function deleteFormElements($id) {

		try{

			
			$delete = FormElements::where('id', '=', $id )->delete();
			if($delete) {
				return response()->json([
					'status' => true,
					'msg'    => 'Item Deleted succesfully'
				]); 
			}else {
				return response()->json([
					'status' => false,
					'msg'    => 'Something Went wrong. Please try again...!!'
				]);  
			}

		} catch(Exception $e) {

			return response()->json([
				'status' => false,
				'msg'    => 'Server Error'
			]);
		}

	}
}
