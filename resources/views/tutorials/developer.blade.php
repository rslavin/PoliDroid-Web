@extends('core')
@section('content')
    <div class="blog-post">
        <h2>Developer Tutorial</h2>

        <p>
            As a developer, you should have access to both the the source code and the compiled bytecode in the form
            of an APK file. In order to use these tools, you must also obtain your app's privacy policy.
        </p>
        <p>It is important to note
            that the tools provided on this site <strong>do not guarantee accuracy</strong>. Violations are ultimately
            declared by lawmakers. PoliDroid serves to check for <i>potential</i> violations in order to improve developer
            and end-user confidence.</p>
        <hr />

        <h3>What is a Privacy Policy?</h3>
        <p>
            A privacy policy is a natural language legal document disclosing the private information that is accessed, used, and/or
            shared. This document is used to fulfil legal obligations of the app publisher to its clients. Often, it is
            created by legal experts.
        </p>
        <p>
            The tools on this site use natural language processing to scan your uploaded privacy policy and generate a list
            of permissible Android API methods. The <strong><a href="/pvdetector">PVDetector</a></strong> tool takes the policy
            along with your app's APK file and runs a static analysis of the app to look for potential "leaks" from methods
            not represented in your app's policy to outbound network-sharing methods. Such information flows are considered
            as potential violations and are included in PVDetector's output.
        </p>
        <p>
            If you do not have a compiled APK, the <strong><a href="source-analyzer">Source Code Analyzer</a></strong> takes source
            code as an input and automatically cans, in your browser, for potential violations. This method does <i>not</i>
            analyze the code to see where the data flows. Instead, it is only able to annotate method invocations that
            are not represented within your code. The Source Code Analyzer suggests phrases to include in your policy to
            cover these potential violations.
        </p>

    </div><!-- /.blog-post -->
@stop()

