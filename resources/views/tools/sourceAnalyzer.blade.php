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
            <textarea id="policy" rows="10" cols="66" id="policy" name="policy"
                      placeholder="Paste your policy here.">{{isset($samplePolicy) ? $samplePolicy : ""}}</textarea>
            <div id="violations" class="alert alert-danger" hidden>
                <strong>Potential Violations Detected</strong>
                <ul id="violations-points">
                </ul>
            </div>
            <div id="no-violations" class="alert alert-success" hidden>
                <strong>No Violations Detected</strong>
            </div>
            <div>
                <button type="button" id="analyze" class="btn btn-block btn-info">Analyze</button>
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
        function resetPage(){
            $("#no-violations").hide();
            $("#violations").hide();
            $("#violations-points").empty();
        }
        function analyzeCode() {
            resetPage();
            var mappings = '{!! $mappings !!}';
            $.each(JSON.parse(mappings), function (key, data) {
                // if it doesn't exist, look for violations
                if ($("#code1").val().indexOf(data.method) > -1) {
                    var violation = true;
                    $.each(data.phrases, function (key, phrase) {
                        if ($("#policy").val().indexOf(phrase) > -1) {
                            violation = false;
                            return false;
                        }
                    });
                    if (violation) {
                        // post it
                        $("#violations").show();
                        $("#violations-points").append("<li>" + data.method + "() used. Consider including one of the following phrases: <strong>\"" +
                                data.phrases + "\"</strong> as a potentially collected data to the policy.</li>");
                    }else
                        $("#no-violations").show();
                }
            });
        }

        $(document).ready(function () {
            $("#analyze").click(analyzeCode);
        });


    </script>
    @endpush
@stop()

