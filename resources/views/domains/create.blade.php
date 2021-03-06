@extends('layouts.app')
@section('content')
<h1>Create Domain <small>inside {{$project->name}}</small></h1>
<div class="row">
    <div class="col-md-7">
{!! Form::open(['action'=>'DomainsController@store','method'=>'POST']) !!}
    <div class="form-group">
        {{Form::label('name','Name')}}
        {{Form::text('name','',['class'=>'form-control','placeholder'=>'Name'])}}
    </div>
    <div class="form-group">
            {{Form::label('description','Description')}}
            {{Form::textarea('description','',['class'=>'form-control','placeholder'=>'Description of the Project'])}}
    </div>
    <div class="row">
            <div class="col-md-8">
                    <div class="form-group">
                            {{Form::label('url','Url')}}
                            {{Form::text('url','',['class'=>'form-control','placeholder'=>'Url of the Domain'])}}
                        </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                            {{Form::label('selector','Css Selector')}}
                            {{Form::text('selector','',['class'=>'form-control','placeholder'=>'Css Selector'])}}
                        </div>
            </div>
    </div>
    <div class="row">
            <h3>Pagination </h3>
            <div class="col-md-4">
                    <div class="form-group">
                            {{Form::label('pagination_link','Pagination Link')}}
                            {{Form::text('pagination_link','',['class'=>'form-control','placeholder'=>'Pagination Link'])}}
                        </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                            {{Form::label('pagination_increment_scheme','Increment Scheme')}}
                            {{Form::text('pagination_increment_scheme','',['class'=>'form-control','placeholder'=>'Increment Scheme'])}}
                        </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                            {{Form::label('pagination_end','End Value')}}
                            {{Form::text('pagination_end','',['class'=>'form-control','placeholder'=>'End Value'])}}
                        </div>
            </div>
    </div>
    <div class="form-group">
            {{Form::label('remarks','Remarks')}}
            {{Form::text('remarks','',['class'=>'form-control','placeholder'=>''])}}
    </div>
    {{Form::hidden('projectCode',$project->id)}}
    {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
{!! Form::close() !!}
    </div>
    <div class="col-md-5">
        <div class="panel panel-info">
                
            <div class="panel-heading">
                    <div class="btn-group pull-right">
                            <a href="#" id="preview_btn" class="btn btn-default btn-sm">Preview</a>
                    </div>
                <h4>Preview Pane</h4>
            </div>
            <div class="panel-body">
                <div id="preview-pane" class="pre-scrollable" max-height="350px"></div>
            </div>
        </div>
    </div>
</div>

@endsection