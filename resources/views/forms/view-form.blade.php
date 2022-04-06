@extends('layouts.layout-new')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 card" style="margin-top: 50px;">
        <div class="card-header">
            {{$form['name']}}
        </div>
        <div class="card-body">
            <code>{{$form['description']}}</code>
            <div class="row" style="margin-top: 25px;">
                @foreach($form['form_elements'] as $element) 

                <div class="form-group">
                    <label for="exampleInputEmail1">{{$element['label']}}</label>
                    @if($element['element_type'] == 1)
                    <input type="text" name="{{$element['name']}}" class="form-control" id="{{$element['name']}}"  placeholder="{{$element['placeholder']}}" required="">
                    @elseif($element['element_type'] == 2)
                    <input type="number" name="{{$element['name']}}" class="form-control" id="{{$element['name']}}"  placeholder="{{$element['placeholder']}}" required="">

                    @elseif($element['element_type'] == 3)

                    <select name="{{$element['name']}}" id="{{$element['name']}}" class="form-control">
                        <option value="">{{$element['placeholder']}}</option>
                        @foreach($element['options'] as $option)
                        <option value="{{$option['value']}}">{{$option['value']}}</option>
                        @endforeach
                    </select>
                    @endif

                </div>
                @endforeach
            </div>
            <div class="div" style="margin-top: 50px; ">
                <button type="submit" name="submit" value="Submit" class="btn btn-primary">Submit</button>
                <a href="{{url('/')}}" class="btn bt-default" style="background-color: #444040a3;">Back</a>
            </div>
        </div>
    </div>
</div>




@endsection
