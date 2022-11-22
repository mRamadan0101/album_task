@extends('layouts.app')
@section('css')
        <link rel="stylesheet" href="{{ asset('album_assets/css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('album_assets/css/templatemo-style.css') }}">
        <script src="{{ asset('album_assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
@endsection
@section('content')

        <div class="main-posts">
            <div class="container">
                <div class="row">
        <form method="POST" action="{{route('albums_store')}}" enctype="multipart/form-data" style="color: black">
            @csrf
            <div class="form-group">
                <label class="required" for="title">Title</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="description">description</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', '') }}" required></textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="cover">Cover</label>
                <div class="needsclick dropzone {{ $errors->has('cover') ? 'is-invalid' : '' }}" id="cover-dropzone">
                </div>
            </div>
            <div class="form-group">
                <label class="required" for="images">Pictures</label>
                <div class="needsclick dropzone {{ $errors->has('images') ? 'is-invalid' : '' }}" id="images-dropzone">
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">
                    Save
                </button>
            </div>
        </form>

                </div>
            </div>
        </div>
@endsection
@section('js')
        <script src="{{ asset('album_assets/js/vendor/jquery-1.10.2.min.js') }}"></script>
        <script src="{{ asset('album_assets/js/min/plugins.min.js') }}"></script>
        <script src="{{ asset('album_assets/js/min/main.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
        <script type="text/javascript">
    Dropzone.options.coverDropzone = {
    url: "{{ route('albums_storeMedia') }}",
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="cover"]').remove()
      $('form').append('<input type="hidden" name="cover" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="cover"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
Dropzone.options.imagesDropzone = {
    url: "{{ route('albums_storeFiles') }}",
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles:100,
    uploadMultiple: true,
    parallelUploads: 100, // use it with uploadMultiple
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    successmultiple: function (files, response) {
      $('form').find('input[name="images"]').remove()
      for (let index = 0; index < response.length; index++) {
         $('form').append('<input type="hidden" multiple name="images['+index+']" value="' + response[index].name + '">')
      }

    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    errormultiple: function (files, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
        </script>
@endsection
