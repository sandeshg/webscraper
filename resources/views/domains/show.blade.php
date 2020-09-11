@extends('layouts.app')
@section('content')
<a href="{{ URL::previous() }}" class="btn btn-default">&lt; Back</a>
<h1>{{$domain->name}} 

    <div class="pull-right">
            <a href="/domains/{{$domain->id}}/edit" class="btn btn-default btn-sm">Edit</a> &nbsp;
            {!!Form::open(['action'=>['DomainsController@destroy',$domain->id],'method'=>'POST','class'=>'pull-right'])!!}
                {{Form::hidden('_method','DELETE')}}
                {{Form::submit('Delete', ['class'=>'btn btn-danger btn-sm'])}}
            {!!Form::close()!!}
    </div>
</h1>
<small>Registered on {{$domain->created_at}}</small>
    <hr/>
        {{$domain->description}}
        <hr/>
        <h4>Other Parameters</h4>
        <dl class="dl-horizontal">
            <dt>Url</dt>
            <dd>{{$domain->url}}</dd>

            <dt>Css Selector</dt>
            <dd>{{$selector->selector->title->css}}</dd>

            <dt>Pagination Link</dt>
            <dd>{{$selector->pagination->link}}</dd>

            <dt>Pagination Increment</dt>
            <dd>{{$selector->pagination->incrementBy}}</dd>

            <dt>Pagination End</dt>
            <dd>{{$selector->pagination->end}}</dd>
            
        </dl>
    <hr/>

<div class="container-fluid">
    <h3>Links 
        <div class="pull-right">
                &nbsp;
                {!!Form::open(['action'=>'LinksController@extract','method'=>'POST','class'=>'pull-right'])!!}
                    {{Form::hidden('domainCode',$domain->id)}}
                    {{Form::submit('+ Extract Content', ['class'=>'btn btn-default btn-sm'])}}
                {!!Form::close()!!} 
                &nbsp;
                {!!Form::open(['action'=>'DomainsController@extract','method'=>'POST','class'=>'pull-right'])!!}
                    {{Form::hidden('domainCode',$domain->id)}}
                    {{Form::submit('+ Extract Links', ['class'=>'btn btn-default btn-sm'])}}
                {!!Form::close()!!}
                &nbsp;
                <a href="/links/create/{{$domain->id}}" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add Links
                </a>
                &nbsp;
                <a href="/links/download/{{$domain->id}}" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Download .csv
                </a>
                &nbsp;
            
        </div>
            
    </h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th></th>
                <th>Added On</th>
                <th>Actions</th>
            </tr>
            @if(count($links)>0)
                @foreach($links as $count => $l)
                    <tr>
                        <td>{{$count+1}}</td>
                        <td><a href="/links/{{$l->id}}">{{$l->url}} <br/></a><small>checksum :: {{$l->urlSignature}}</small></td>
                        <td>{{$l->created_at}}</td>
                        <td>
                            <div class="pull-left">
                            <a href="/links/{{$l->id}}/edit" class="btn btn-default btn-sm">Edit</a> &nbsp;
                                {!!Form::open(['action'=>['LinksController@destroy',$l->id],'method'=>'POST','class'=>'pull-right'])!!}
                                    {{Form::hidden('_method','DELETE')}}
                                    {{Form::submit('Delete', ['class'=>'btn btn-danger btn-sm'])}}
                                {!!Form::close()!!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </table>
                {{$links->links()}}
           @else
                <p>No Projects has been registered.</p>
            @endif
           
    </div>
</div>        
@endsection