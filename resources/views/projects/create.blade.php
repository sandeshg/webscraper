@extends('layouts.app')
@section('content')
<h1>Create Project</h1>
{!! Form::open(['action'=>'ProjectsController@store','method'=>'POST']) !!}
    <div class="form-group">
        {{Form::label('name','Name')}}
        {{Form::text('name','',['class'=>'form-control','placeholder'=>'Name'])}}
    </div>
    <div class="form-group">
            {{Form::label('description','Description')}}
            {{Form::textarea('description','',['class'=>'form-control','placeholder'=>'Description of the Project'])}}
    </div>
    <div class="form-group">
            {{Form::label('remarks','Remarks')}}
            {{Form::text('remarks','',['class'=>'form-control','placeholder'=>''])}}
    </div>
    {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
{!! Form::close() !!}
   
@endsection