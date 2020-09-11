@extends('layouts.app')
@section('content')
<h1>Create Link <small>inside {{$domain->name}}</small></h1>
<div class="row">
    <div class="col-md-8">
{!! Form::open(['action'=>'LinksController@store','method'=>'POST']) !!}
    <div class="form-group">
        {{Form::label('link','Link')}}
        {{Form::text('link','',['class'=>'form-control','placeholder'=>'Link'])}}
    </div>
    <div class="selectors">
        <hr/>
         <h4>Identifiers 
                
                <div class="btn-group">
                        <button class="btn btn-success btn-sm" id="addSelectors" type="button">+ Add</button>
                </div> 
        </h4>   
                <div class="row">
                        <div class="col-md-2">Name </div>
                        <div class="col-md-4">Css Selector</div>
                        <div class="col-md-2">Type</div>
                        <div class="col-md-2">Merge</div>
                        <div class="col-md-2"></div>
                </div>
                <div class="row fieldWrapper">
                        <div class="col-md-2">
                                <div class="form-group">
                                    {{Form::text('selector[0][name]','',['class'=>'form-control','placeholder'=>'Name'])}}
                                </div>
                            </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                {{Form::text('selector[0][selector]','',['class'=>'form-control','placeholder'=>'Css Selector'])}}
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::select('selector[0][type]',  ['_text'=>'text','href'=>'link'], null, ['class' => 'form-control']) }}
                                
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::select('selector[0][merge]',  ['N'=>'No','Y'=>'Yes'], null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                                <div class="form-group">
                                        <button class="remove_button btn btn-danger btn-sm" type="button">Remove</button>
                                </div>
                        </div>
                </div>
    </div>
    <div class="row">
            
        </div>
    

    <div class="form-group">
            {{Form::label('remarks','Remarks')}}
            {{Form::text('remarks','',['class'=>'form-control','placeholder'=>''])}}
    </div>


    {{Form::hidden('domainCode',$domain->id)}}
    {{Form::hidden('isManual','Y')}}
    {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
{!! Form::close() !!}
    </div>
    <div class="col-md-4">
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