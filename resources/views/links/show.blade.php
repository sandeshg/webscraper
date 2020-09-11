@extends('layouts.app')
@section('content')
<a href="{{ URL::previous() }}" class="btn btn-default">&lt; Back</a>
<h1>{{$link->url}} </h1>

@if($selector)
<div class="panel panel-default">
    <div class="table-responsive">
    <table class="table table-condensed table-striped">
        <tr>
            <th>#</td>
            <th>Name</th>
            <th>Selector</th>
            <th>Type</th>
            <th>Merge</th>
        </tr>
        @foreach($selector as $count => $s)
        <tr>
                <td>{{$count+1}}</td>
                <td>{{$s->name}}</td>
                <td>{{$s->selector}}</td>
                <td>{{$s->type}}</td>
                <td>{{$s->merge}}</td>
            </tr>
        @endforeach
    </table>
    </div>
</div>
@else
<p>Selector has not been defined</p>
@endif

<div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Extracted Data </h3>
        </div>
        <div class="panel-body">
                <dl class="dl-horizontal">
                        @if ($content)
                            @foreach($content as $key => $c)
                                <dt>{{$key}}</dt>
                                <dd>{{$c}}</dd>
                            @endforeach
                        @else
                            <p>Content has not been extracted.</p>
                        @endif
                        </dl>
        </div>
      </div>

@endsection