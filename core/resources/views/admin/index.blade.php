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

                You may follow the below steps in order to construct and run a study:

                <ol>
                    <li>Define the <a href="{{ url('/admin/task_settings') }}">attributes of your tasks</a></li>
                    <li>Define your <a href="{{ url('/admin/manage_tasks') }}">task descriptions and set the attributes of each task</a></li>
                    <li>Create <a href="{{ url('/admin/manage_questionnaires') }}">questionnaires</a></li>
                    <li>Create and order your <a href="{{ url('/admin/manage_stages') }}">stages</a></li>
                        <ul>
                            <li>For each stage, insert the respective tasks and/or questionnaires, as well as additional text.</li>
                            <li>Stages are displayed to your study participants. By dragging and dropping created stages,
                                you can change the order of the stages.</li>
                            <li>Stages are comprised of smaller components consisting of questionnaires, tasks, text, confirm
                                functions and more!</li>
                        </ul>
                    <li>Edit your registration forms (register.blade.php, consent.blade.php, studywelcome.blade.php),</li>
                    <li>Create <a href="{{ url('/admin/manage_users') }}">test users</a> or have users register for your study</li>
                        <ul>
                            <li>Enable registered users to participate in your study <a href="{{ url('/admin/manage_users') }}">(set Active=True)</a></li>
                        </ul>
                    <li>Have users take part in your study!</li>
                    <li>After conducting your study, use the feature extractor to extract query segment features (features/feature_extractor.py).</li>
                </ol>
            </div>
        </div>
    </div>
@stop