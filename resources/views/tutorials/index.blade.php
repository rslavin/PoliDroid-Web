@extends('core')
@section('content')
    <div class="blog-post">
        <h2>Tutorial</h2>

        <p>
            The PoliDroid suite is made to help both developers and those who simply need to check that an app is
            consistent with its privacy policy. Select the user type that most describes you below for instructions on
            using these tools.
        </p>
        <hr>

        <div class="box-body">
            <ul>
                <li>
                    <a href="/tutorials/developer">Developer</a> - Application code writer.
                </li>
                <li>
                    <a href="/tutorials/auditor">Auditor</a> - Non-developer verifying app they did not write.
                </li>
            </ul>

        </div>

    </div><!-- /.blog-post -->
@stop()

