@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">


@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">

            <div class="panel-heading">
                Manage Stages
            </div>

            <div class="panel-body">
                <ul class="list-group" id="sortable">
                    @foreach($stages as $stage)
                        <li id="{{$stage->id}}" class="list-group-item sortable-element">
                            <form action="/admin/{{ $stage->id }}/delete_stage" method="post" style="display: inline-block">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="hidden" name="_method" value="delete" />
                                <button type="submit" onclick="return confirmChoice()" class="btn btn-danger btn btn-sm fa fa-times"></button>
                            </form>

                            <a class="btn btn-link btn-sm" href="/admin/{{ $stage->id }}/edit_stage"  style="display: inline-block;">Edit</a>
                            <a class="btn btn-link btn-sm" href="/admin/{{ $stage->id }}/preview_stage"  style="display: inline-block;">Preview</a>
                            <p style="display: inline-block">{{ $stage->title }}</p>
                        </li>
                    @endforeach
                </ul>
                Query String: <span></span><br>
                <a href="/admin/create_stage" class="btn btn-success">Create a Stage</a>
            </div>
        </div>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            function confirmChoice(){
                return confirm("Are you sure you want to delete this stage?");
            }
            //Handle Drag and Drop
            $(document).ready(function () {
                $('ul').sortable({
                    axis:'y',
                    stop:function(event, ui){
                        var data= $(this).sortable('toArray');
                        console.log(data);
                        $('span').text(data);
                        $.ajax({
                            data: data,
                            type: "POST",
                            url:"/admin/manage_stages",
                            success: function(result) {
                                console.log('it worked');
                                console.log(result);
                            },
                            error: function(textStatus, errorThrown) {
                                console.log('it didnt work');
                                console.log(textStatus);
                                console.log(errorThrown);
                            }
                        });

                    }
                });
            });
        </script>
    </div>
@stop