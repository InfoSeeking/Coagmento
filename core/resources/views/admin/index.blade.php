@extends('admin.layout')

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Admin Page</div>
            <div class="panel-body">

                <h3>Welcome to Coagmento!</h3>
                <h4>Version 3.0</h4>
                <p>Coagmento allows the swift prototyping of web search studies within a few hours.</p>
                <p>Continue reading for
                     instructions and tips for creating studies!</p>
                <hr>
                <h4>Creating a Study</h4>
                <ul>
                    <li>Stages are what's displayed to your study participants. By dragging and dropping created stages,
                     you can change the order of the stages.</li>
                    <li>Stages are comprized of smaller components consisting of questionnaires, tasks, text, confirm
                        functions and more!</li>
                    <li>Create questionnaires and tasks in their respective tabs.</li>
                    <li>Generate a randomized user accounts to provide access for user participants.</li>
                    <li>Create email content to send to users when a study is upcoming. (Feature currently in development) </li>
                </ul>

            </div>
        </div>
    </div>
@stop