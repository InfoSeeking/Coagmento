@extends('admin.layout')

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Admin Page</div>
            <div class="panel-body">

                <h3>Welcome to Coagmento!</h3>
                <h5>Version 3.0</h5>
                <p>Coagmento allows the swift prototyping of web search studies at the click of a button.</p>
                <p>Continue reading below for instructions and tips for creating studies.</p>
                <hr>
                <h4>Creating a Study</h4>
                <ul>
                    <li>Stages are displayed to your study participants. By dragging and dropping created stages,
                     you can change the order of the stages.</li>
                    <li>Stages are comprised of smaller components consisting of questionnaires, tasks, text, confirm
                        functions and more!</li>
                    <li>Create questionnaires and tasks in their respective tabs.</li>
                    <li>Generate randomized user accounts to provide access for participants.</li>
                    <li>Create email templates to send to participants when a study is about to start.</li>
                </ul>

            </div>
        </div>
    </div>
@stop