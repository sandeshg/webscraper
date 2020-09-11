@extends('layouts.app')
@section('content')
<h1>Edit Project</h1>
{!! Form::open(['action'=>['ProjectsController@update',$project->id],'method'=>'POST']) !!}
    <div class="form-group">
        {{Form::label('name','Name')}}
        {{Form::text('name',$project->name,['class'=>'form-control','placeholder'=>'Name'])}}
    </div>
    <div class="form-group">
            {{Form::label('description','Description')}}
            {{Form::textarea('description',$project->description,['class'=>'form-control','placeholder'=>'Description of the Project'])}}
    </div>
    <div class="form-group">
            {{Form::label('remarks','Remarks')}}
            {{Form::text('remarks',$project->remarks,['class'=>'form-control','placeholder'=>''])}}
    </div>
    {{Form::hidden('_method','PUT')}}
    {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
{!! Form::close() !!}
   
@endsection