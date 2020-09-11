@extends('layouts.app')
@section('content')
<h1>{{$title}} <a href="/projects/create" class="pull-right btn btn-default btn-sm">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>New Project
</a></h1>

    <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th></th>
                    <th></th>
                    <th>Created On</th>
                    <th>Actions</th>
                </tr>
            @if(count($data)>0)
                @foreach($data as $d)
                    <tr>
                        <td></td>
                        <td><a href="/projects/{{$d->id}}">{{$d->name}} <br/></a><small>{{$d->description}}</small></td>
                        <td>{{$d->created_at}}</td>
                        <td><a href="/projects/{{$d->id}}">view</a></td>
                    </tr>
                @endforeach
                </table>
                {{$data->links()}}
           @else
                <p>No Projects has been registered.</p>
            @endif
            
    </div>








@endsection