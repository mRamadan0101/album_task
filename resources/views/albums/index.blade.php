@extends('layouts.app')
@section('css')
        <link rel="stylesheet" href="{{ asset('album_assets/css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/templatemo-style.css') }}">
        <script src="{{ asset('album_assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>

@endsection
@section('content')

        <div class="main-posts">
            <div class="container">
                <a href="{{route('create_album')}}" class="btn btn-success">New</a>
                <div class="row">
                    <div class="blog-masonry masonry-true">
                        {{-- start foreach --}}
                        @foreach ($albums as $album)
                            <div class="post-masonry col-md-4 col-sm-6">
                                <div class="post-thumb">
                                    <img src="{{ asset('album_assets/1.jpg')}}" alt="">
                                    <div class="title-over">
                                        <h4><a href="{{route('show_album', ['slug' => $album->slug])}}">{{ $album->title }}</a></h4>
                                    </div>
                                    <div class="post-hover text-center">
                                        <div class="inside">
                                            <i class="fa fa-plus"></i>
                                            <span class="date">{{ $album->created_at->format('d-M-Y') }}</span>
                                            <h4><a href="{{route('show_album', ['slug' => $album->slug])}}">{{ $album->title }}</a></h4>
                                            <p>{{ $album->description }}</p>
                                            <a href="{{route('edit_album', ['slug' => $album->slug])}}" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="btn btn-danger remove_album" data-slug='{{$album->slug}}'><i class="fa fa-remove"></i></a>
                                        </div>
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
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript">
    $('.remove_album').off('click').on('click', function (e) {
    e.preventDefault();
    var album_slug = $(this).data('slug')
    $.ajax({
        type: "GET",
        url: "/delete_album/"+album_slug,
        success: function (data) {
            if (data.status == 2) {
                    Swal.fire(
                    'Deleted!',
                    data.message,
                    'success'
                    )
            }else if(data.status == 1){
                Swal.fire({
                title: data.message,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Delete All',
                denyButtonText: `Move To another album`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: "/confirm_delete_album/"+album_slug,
                        success: function (data) {
                            Swal.fire('Deleted!', data.message, 'success')
                        }
                    })
                } else if (result.isDenied) {
                    (async () => {

                    const { value: album } = await Swal.fire({
                    title: 'Select field validation',
                    input: 'select',
                    inputOptions: {
                            @foreach ($albums as $album)
                            "{{$album->id}}" : "{{$album->title}}",
                            @endforeach
                    },
                    inputPlaceholder: 'Select a fruit',
                    showCancelButton: true,
                    })

                    if (album) {
                        $.ajax({
                            type: "GET",
                            url: "/move_images/"+album_slug+"?new_album="+album,
                            success: function (data) {
                                if (data.status == 1) {
                                     Swal.fire('moved!', data.message, 'success')
                                }else{
                                    Swal.fire('Error!', data.message, 'error')
                                }

                            }
                        })
                    }

                    })()
                }
                })
            }

            // location.reload();
        }
    });
})
        </script>
@endsection
