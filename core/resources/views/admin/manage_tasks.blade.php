@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Manage Tasks
            </div>
            <div class="panel-body">
                <a class="btn btn-default" href="add_task">Create a Task</a>
                <a class="btn btn-default" href="task_settings">Edit Attributes</a> <br>

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
                        <th>Options</th>
                        <th></th>
                        <th>Description</th>
                        @foreach($attributes as $attribute)
                            <th>{{ $attribute->name }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>


                    @foreach($tasks as $task)
                        <tr>
                            <td>
                                <form action="/admin/{{ $task->id }}/delete_task" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <input type="hidden" name="_method" value="delete" />
                                    <button type="submit" onclick="return confirmChoice()" class="btn btn-danger btn btn-sm"> <span class="fa fa-times"></span> </button>
                                </form>
                                <script>
                                    function confirmChoice(){
                                        return confirm("Are you sure you want to delete this task?");
                                    }
                                </script>
                            </td>
                            <td>
                                <a class="btn btn-link btn btn-sm" href="/admin/{{ $task->id }}/edit_task">Edit</a>
                            </td>

                            <td>
                                {{ $task->description }}
                            </td>

                            @foreach($attributes as $attribute)
                                <td>
                                    {{ $assignments->where('task_id', $task->id)
                                    ->where('attribute_id', $attribute->id)->first()['value'] }}
                                </td>
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
