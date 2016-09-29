@extends('core')
@section('content')
    <div class="blog-post">
        <h2>Source Code Analyzer</h2>
        <p>
            Paste your source code into the source code box below, then paste your privacy policy
            into the privacy policy box at the bottom. PoliDroid will analyze your code and privacy policy
            and display potential inconsistencies at the bottom of the page.
        </p>

        <hr/>

        <form>
            <h4>Source Code</h4>
            <div id="class1" class="tab-pane fade in active" style="font-size: smaller">
                <textarea id="code1" name="code1">{{isset($sampleCode) ? $sampleCode : ""}}</textarea>
            </div>

            <br/>
            <h4>Privacy Policy</h4>
            <textarea id="policy" rows="10" cols="70" id="policy" name="policy"
                      placeholder="Paste your policy here."></textarea>
            <div id="violations" class="alert alert-danger" hidden>
                <strong>Potential Violations Found</strong>
                <ul id="violations-points">

                </ul>
            </div>
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

    <script>
        $(document).ready(function () {
            $("#policy").on("input", function () {
                var mappings = '{!! $mappings !!}';
                $.each(JSON.parse(mappings), function (key, data) {
                    if ($("#policy").val().indexOf(data.phrase) == -1) {
                        $.each(data.methods, function (key, method) {
                            if ($("#code1").val().indexOf(method) > -1) {
                                $("#violations").show();
                                if ($("#violations").text().indexOf(method) == -1)
                                    $("#violations-points").append("<li>" + method + "() used. Consider including the phrase <strong>\"" + data.phrase + "\"</strong> as a potentially collected datum to the policy.</li>");
                            }
                        })
                    }
                });
            })
        })

    </script>
    @endpush
@stop()

