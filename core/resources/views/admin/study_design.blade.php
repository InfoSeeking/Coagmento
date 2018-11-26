@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            Participant Assignments
        </div>
        <div class="panel-body">
            @if($tasks->isEmpty())
                <div class="container">
                    <h3>
                        No tasks here!
                        <i class="fa fa-frown-o"></i>
                    </h3>
                </div>
            @else
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Participant Number</th>
                        <!--{{$count=0}}-->
                        @foreach($tasks as $task)
                            <th>Task {{++$count}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>

                        @foreach($users as $user)
                            <tr>
                            <th> {{ $user->participant_number }} : {{ $user->email }}</th>
                            @foreach($tasks as $task)
                                <th>{{ $task->description }}</th>
                            @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@stop