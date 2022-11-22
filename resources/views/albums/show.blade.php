@extends('layouts.app')
@section('css')
        <link rel="stylesheet" href="{{ asset('album_assets/css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/templatemo-style.css') }}">
        <script src="{{ asset('album_assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>

@endsection
@section('content')

        <div class="main-posts" >
            <div class="container">
                {{-- <a href="{{route('create_album')}}" class="btn btn-success">New</a> --}}
                <h1 style="color: black; text-align: center">{{$album->title}}</h1>
                <div class="row">
                    <div class="blog-masonry masonry-true">
                        {{-- start foreach --}}
                        @foreach ($album->images as $image)
                            <div class="post-masonry col-md-4 col-sm-6">
                                <div class="post-thumb">
                                    <img src="{{ $image->getUrl() }}" alt="">
                                    <div class="title-over">
                                        <h4>{{ $image->name }}</h4>
                                    </div>
                                </div>
                            </div> <!-- /.post-masonry -->
                        @endforeach

                        {{-- end foreach --}}
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('js')
        <script src="{{ asset('album_assets/js/vendor/jquery-1.10.2.min.js') }}"></script>
        <script src="{{ asset('album_assets/js/min/plugins.min.js') }}"></script>
        <script src="{{ asset('album_assets/js/min/main.min.js') }}"></script>
        <script type="text/javascript">
            //<![CDATA[
            $(window).load(function() { // makes sure the whole site is loaded
                $('#loader').fadeOut(); // will first fade out the loading animation
                    $('#loader-wrapper').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
                $('body').delay(350).css({'overflow-y':'visible'});
            })
            //]]>
        </script>
@endsection
