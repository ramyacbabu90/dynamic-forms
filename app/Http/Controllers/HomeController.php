<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormElements;
use App\Models\OptionValues;
use Illuminate\Http\Request;

use Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $forms = Form::where('status',1)->get();
        return view('home',[
            'forms' => $forms,
        ]);
    }

    public function viewForm($id) {

        try {
            
            $form = Form::where('id', $id)->with('formElements')->get()->toArray();
            foreach ($form[0]['form_elements'] as $key => $element) {
               if($element['element_type'] == 3) {
                 $form[0]['form_elements'][$key]['options'] = OptionValues::where('element_id', $element['id'])->get();//->toArray();
               }  
            }

            return view('forms.view-form', [
                'form' => $form[0],
            ]);
        } catch (\Exception $ex) {
            $redirect = "/"; 
            return redirect($redirect);
        }

    }

}
