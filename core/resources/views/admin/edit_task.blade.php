@extends('admin.layout')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit Task
            </div>
            <div class="panel-body">
                <form method="POST" action="/admin/{{ $task->id }}/edit_task">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="form-group">
                        <div>
                            Description: <br>
                        <!-- make this form bigger
                            {!! Form::text('description') !!}-->
                            <textarea rows="4" name="description" class="form-control">{{ $task->description }}</textarea>
                        </div>
                    </div> <hr>
                    @foreach($attributes as $attribute) {{--$request->attribute as $key--}}
                    <div class="form-group">
                        <div>
                            {{ $attribute->name }}: <br>
                            <input type="hidden" name="attribute_ids[]" value="{{$attribute->id}}">
                            @if($attribute->type === "text")
                                <input type="text" name="option_values[{{$attribute->id}}]" value="{{ $assignments->where('task_id', $task->id)
                                    ->where('attribute_id', $attribute->id)->first()['value'] }}">
                            @elseif($attribute->type === "select")
                                <select name="option_values[{{$attribute->id}}]">

                                    @foreach($attribute->option_name as $key=>$value)
                                        @if($assignments->where('task_id', $task->id)
                                            ->where('attribute_id', $attribute->id)->first()['value'] === $value)
                                            <option {{--value="{{$value}}" --}}selected="selected">
                                                {{$value}}
                                            </option>
                                        @else
                                            <option {{--value="{{$value}}"--}}>
                                                {{$value}}
                                            </option>
                                        @endif
                                    @endforeach

                                </select><br>
                            @endif
                        </div>
                    </div><hr>
                    @endforeach
                    <span>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="/admin/manage_tasks" class="btn btn-default">Cancel</a>
                    </span>
                    <br>
                </form>

            </div>
        </div>
    </div>
@stop