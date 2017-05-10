@extends('core')
@section('content')
    <div class="blog-post">
        <h2>Research Studies</h2>

        <p>
            The work on this site is based on <a href="/publications">academic research</a>. Data from our studies is
            available below.
        </p>
        <hr>

        <div class="box-body">
            <h3>UILeak: Detecting Privacy-Policy Violations on User Input Data for Android Applications</h3>
        <p>
            As the most popular mobile platform, Android devices have millions of users around the world. As these
            devices are used everyday and collects various data from users, effective privacy protection has been a well
            known challenge in the Android world. Existing privacy-protection approaches focus on information accessed
            from Android platform API methods, such as location and device ID, while existing security-enhancement
            approaches are not fine-grained enough to map user input data to concepts in privacy policies. In this
            paper, we proposed a novel approach that automatically detects privacy leakage on user input data for a
            given Android app, and determines whether such leakage may violate privacy policies coming with the Android
            app.
        </p>
            <h4>Data:</h4>
            <ul>
                <li><a href="/downloads/data/uileak1.zip">uileak1.zip</a></li>
            </ul>

        </div>

    </div><!-- /.blog-post -->
@stop()

