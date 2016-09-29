@extends('core')
@section('content')
    <div class="blog-post">
        <h2>Source Code Analyzer</h2>
        <p>
            Paste your source code into the source code box below, then paste your privacy policy
            into the privacy policy box at the bottom. Our PoliDroid will analyze your code and privacy
            and highlight potential inconsistencies in your code.
        </p>

        <hr/>

        <form>
            <h4>Source Code</h4>
            <div id="class1" class="tab-pane fade in active" style="font-size: smaller">
                <textarea id="code1" name="code1">{{isset($sampleCode) ? $sampleCode : ""}}</textarea>
            </div>


            <br/>
            <h4>Privacy Policy</h4>
            <textarea rows="10" cols="70" id="policy" name="policy" placeholder="Paste your policy here."></textarea><
        </form>


    </div><!-- /.blog-post -->

    @push('scripts')
    <script src="/js/codemirror/codemirror.js"></script>
    <link rel="stylesheet" href="/js/codemirror/codemirror.css">
    <link rel="stylesheet" href="/js/codemirror/theme/mbo.css">
    <script src="/js/codemirror/mode/clike/clike.js"></script>

    <script>
        window.onload = function () {
            var te1 = document.getElementById("code1");
            window.editor = CodeMirror.fromTextArea(te1, {
                mode: "text/x-java",
                lineNumbers: true,
                lineWrapping: true,
                matchBrackets: true,
                theme: "mbo",
            });
        }
    </script>
    @endpush
@stop()

