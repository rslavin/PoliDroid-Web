@extends('core')
@section('content')
    <div class="blog-post">
        <h2>PVDetector</h2>

        <p>Use the form below to upload your Android application along with the text in the corresponding privacy policy
            to check for consistency. Once the analysis is complete, you will receive an email with the results.</p>
        <hr>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <br/>
                <br/>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <!-- form start -->

        <div class="box-body">
            @if(isset($comment))
                <div class="alert alert-warning">
                    <strong>The following must be addressed:</strong>

                    <p>
                        {!! $comment->comment!!}
                    </p>
                </div>
            @endif
            {!! Form::open(array('url' => '/pvdetector', 'autocomplete' => "off", 'files' => true)) !!}

            <div class="form-group {!! Session::has('errors') && Session::get('errors')->has('email') ? "has-error" : "" !!}">
                <label class="col-md-4 control-label">Email Address:</label>

                <div class="input-group">
                    <input type="email" class="form-control" name="email"
                           id="email"
                           value="">
                </div>
            </div>

            <div class="form-group {!! Session::has('errors') && Session::get('errors')->has('file') ? "has-error" : "" !!}">
                <label class="col-md-4 control-label">APK File:</label>
                <div class="input-group">
                    <input type="file" class="form-control" name="apk_file"
                           id="file">
                </div>
            </div>

            <div class="form-group {!! Session::has('errors') && Session::get('errors')->has('policy') ? "has-error" : "" !!}">
                <label class="col-md-4 control-label">Privacy Policy:</label>
                <div class="input-group">
                    <textarea rows="10" cols="50" class="form-control" name="policy"
                              id="policy"></textarea>
                </div>
            </div>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        {!! Form::close() !!}

    </div><!-- /.blog-post -->

    @if(session('success'))
        <div id="successModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Success!</h4>
                    </div>
                    <div class="modal-body">
                        <p>Your files have been submitted for analysis. You will receive an email when it is complete.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    <script type="text/javascript">
        $(window).load(function(){
            $('#successModal').modal('show');
        });
    </script>
    @endif

@stop()

