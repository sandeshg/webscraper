@extends('layouts.app')
@section('content')

<h1>{{$project->name}}</h1>
<small>Registered on {{$project->created_at}}</small>
<hr/>

        {{$project->description}}
        <hr/>
<a href="/projects/{{$project->id}}/edit" class="btn btn-default btn-sm">Edit</a>
{!!Form::open(['action'=>['ProjectsController@destroy',$project->id],'method'=>'POST','class'=>'pull-right'])!!}
    {{Form::hidden('_method','DELETE')}}
    {{Form::submit('Delete', ['class'=>'btn btn-danger btn-sm'])}}
{!!Form::close()!!}
<div class="container-fluid">
    <h3>Domains 
        <a href="/domains/create/{{$project->id}}" class="pull-right btn btn-default btn-sm">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>New domain
        </a>
    </h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th></th>
                <th>Added On</th>
                <th>Actions</th>
            </tr>
            @if(count($domains)>0)
                @foreach($domains as $d)
                    <tr>
                        <td></td>
                        <td><a href="/domains/{{$d->id}}">{{$d->name}} <br/></a><small>{{$d->description}}</small></td>
                        <td>{{$d->created_at}}</td>
                        <td><a href="/domains/{{$d->id}}">view</a></td>
                    </tr>
                @endforeach
                </table>
                {{$domains->links()}}
           @else
                <p>No Projects has been registered.</p>
            @endif
    </div>
</div>        
@endsection