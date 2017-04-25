@extends('core')
@section('content')
    <div class="blog-post">
        <h2>Polidroid-AS</h2>

        <p>Polidroid-AS is an Android Studio plugin which helps developers to create and align privacy policies for
            their source code. The video below describes its usage and features. A simplified web-based version of
        this tool is available <a href="source-analyzer">here</a>.</p>
        <hr>
        <link href="{{ asset('/polidroidvideo/polidroid-tutorial_embed.css') }}" rel="stylesheet">

        <iframe class="tscplayer_inline" id="embeddedSmartPlayerInstance" src="/polidroidvideo/polidroid-tutorial_player.html?embedIFrameId=embeddedSmartPlayerInstance" scrolling="no" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

        <h3>Download</h3>
        <p>PoliDroid-AS and its source code are available for download at
            <a href="https://github.com/rslavin/PoliDroid-AS/releases">https://github.com/rslavin/PoliDroid-AS/releases</a>.
            Use the mapping and ontology files below.</p>
        <ul>
            <li><a href="/downloads/mappings.csv">Mappings File</a></li>
            <li><a href="/downloads/ontology.owl">Ontology File</a></li>
        </ul>

    </div>

@stop()

