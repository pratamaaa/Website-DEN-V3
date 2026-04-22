@extends('layout.depan.app')

@section('content')
<div class="page-header">
    {{-- <div class="container">
        <h1 class="title">Blog Grid 2 Column - Right Sidebar</h1>
    </div> --}}
    <div class="breadcrumb-box">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="active">
                    <a href="{{ url('/berita') }}">Berita</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<section class="page-section">
    <div class="container">
        {{-- MAIN CONTENT --}}
        @php
            echo $profil->konten;
        @endphp
        {{ $profil->konten; }}
        <div class="sparatorputih-20">&nbsp;</div>
        <div class="col-md-12">
            <form class="form-horizontal" method="POST" action="{{ url('/formsave') }}">
                @csrf
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Grouping</label>
                  <div class="col-sm-2">
                    <select name="grouping" class="form-control">
                        @foreach ($group as $group)
                            <option value="{{ $group->id_profil_kategori }}">{{ $group->nama_kategori }}</option>
                        @endforeach
                      </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Judul</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="judul" id="inputEmail3">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Konten</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="konten" id="myeditorinstance"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Urutan</label>
                  <div class="col-sm-1">
                    <input type="text" class="form-control" name="urutan" id="inputEmail3">
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">SIMPAN</button>
                  </div>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'myeditorinstance', {
        filebrowserBrowseUrl: '/browser/browse.php',
        filebrowserUploadUrl: '/uploader/upload.php'
    }); --}}
</script>

{{-- <script src="{{ url('/themes/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script> --}}
<script src="https://cdn.tiny.cloud/1/6zqny3stbxo7o3aw2apj05q8vhqjcdxbikj9eynhw6ir1dsc/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount advcode',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        /* enable title field in the Image dialog*/
  image_title: true,
  /* enable automatic uploads of images represented by blob or data URIs*/
  automatic_uploads: true,
  /*
    URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
    images_upload_url: 'postAcceptor.php',
    here we add custom filepicker only to Image dialog
  */
  file_picker_types: 'image',
  /* and here's our custom image picker*/
  file_picker_callback: function (cb, value, meta) {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');

    /*
      Note: In modern browsers input[type="file"] is functional without
      even adding it to the DOM, but that might not be the case in some older
      or quirky browsers like IE, so you might want to add it to the DOM
      just in case, and visually hide it. And do not forget do remove it
      once you do not need it anymore.
    */

    input.onchange = function () {
      var file = this.files[0];

      var reader = new FileReader();
      reader.onload = function () {
        /*
          Note: Now we need to register the blob in TinyMCEs image blob
          registry. In the next release this part hopefully won't be
          necessary, as we are looking to handle it internally.
        */
        var id = 'blobid' + (new Date()).getTime();
        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
        var base64 = reader.result.split(',')[1];
        var blobInfo = blobCache.create(id, file, base64);
        blobCache.add(blobInfo);

        /* call the callback and populate the Title field with the file name */
        cb(blobInfo.blobUri(), { title: file.name });
      };
      reader.readAsDataURL(file);
    };

    input.click();
  },
    });
</script>
<script>
$(document).ready(function() {
    
});
</script>
@endsection