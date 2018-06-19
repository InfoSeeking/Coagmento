@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add a Task
            </div>
            <div class="panel-body">
                <form method="POST" action="/admin/manage_tasks">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}

                    <div class="form-group">
                        <div>
                            Description: <br>
                            <!-- make this form bigger
                            {!! Form::text('description') !!}-->
                            <textarea rows="4" name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    @foreach($attributes as $attribute) {{--$request->attribute as $key--}}
                    <div class="form-group">
                        <div>
                            {{ $attribute->name }}: <br>
                            <input type="hidden" name="attribute_ids[]" value="{{$attribute->id}}">
                            @if($attribute->type === "text")
                                <input type="text" name="option_values[{{$attribute->id}}]">
                            @elseif($attribute->type === "select")
                                <select name="option_values[{{$attribute->id}}]">

                                    @foreach($attribute->option_name as $key=>$value)
                                        <option value="{{$value}}">
                                            {{$value}}
                                        </option>
                                    @endforeach

                                </select><br>
                                <!--<i class="fa fa-btn fa-plus-circle">Add Option</i>-->
                            @endif
                        </div>
                    </div><hr>
                    @endforeach
                    <span>
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="/admin/manage_tasks" class="btn btn-default">Cancel</a>
                    </span>
                </form>

            </div>
        </div>
    </div>
@stop