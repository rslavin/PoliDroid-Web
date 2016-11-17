@extends('core')
@section('content')
    <div class="blog-post">
        <h2>Polidroid-AS</h2>

        <p>Polidroid-AS is an Android Studio plugin which helps developers to create and align privacy policies for
            their source code.</p>
        <hr>
        <link href="{{ asset('/polidroidvideo/polidroid-tutorial_embed.css') }}" rel="stylesheet">

        <iframe class="tscplayer_inline" id="embeddedSmartPlayerInstance" src="/polidroidvideo/polidroid-tutorial_player.html?embedIFrameId=embeddedSmartPlayerInstance" scrolling="no" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

        <h2>Download</h2>
        <ul>
            <li><a href="/downloads/PoliDroid-AS.zip">Plugin</a></li>
            <li><a href="/downloads/mappings.csv">Mappings File</a></li>
            <li><a href="/downloads/ontology.owl">Ontology File</a></li>
        </ul>
    </div>

@stop()

