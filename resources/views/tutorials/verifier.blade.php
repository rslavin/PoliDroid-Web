@extends('core')
@section('content')
    <div class="blog-post">
        <h2>Policy Verifier Tutorial</h2>
        <p>
            The PoliDroid suite can be used by those who want to verify an existing app with an existing policy. This means
            that end-users, policy makers, and law makers can use the tools to check for consistency. It is important, however, to note
            that the tools provided on this site <strong>do not guarantee accuracy</strong>. Violations are ultimately
            declared by lawmakers. PoliDroid serves to check for <i>potential</i> violations in order to improve developer
            and end-user confidence.
        </p>
        <hr />

        <h3>Application Code</h3>
        <p>
            As a non-developer, you can use an app downloaded to your phone from app repositories such as the
            <a href="http://play.google.com">Google Play store</a>. The file itself can either be directly downloaded
            from the app repository or extracted from the phone itself. Apps on the Google Play Store such as
            <a href="https://play.google.com/store/apps/details?id=com.ext.ui&hl=en">Apk Extractor</a> can be used to
            copy an installed app's file (APK) to an SD card. From there, the file can be directly uploaded to the PoliDroid tool
            suite.
        </p>
        <hr />

        <h3>Privacy Policy</h3>
        <p>
            A privacy policy is a natural language legal document disclosing the private information that is accessed, used, and/or
            shared. This document is used to fulfil legal obligations of the app publisher to its clients. Often, it is
            created by legal experts.
        </p>
        <p>
            Privacy policies can be found on the publisher's website or where the app is downloaded. At the
            <a href="http://play.google.com">Google Play Store</a>, an app's privacy policy can be accessed from the
            app's download page.
        </p>
        <hr />

        <h3>Tool Use</h3>
        <p>
            As a non-developer, you will use <strong><a href="/pvdetector">PVDetector</a></strong> to upload the app's
            APK file and its privacy policy to the PoliDroid tool suite. PVDetector will then analyze the app and policy
            and look for instances where the app collects private information that is not disclosed in the policy and sends it
            away from the device out to the network. Such instances are assumed to be potential violations.
        </p>

    </div><!-- /.blog-post -->
@stop()

