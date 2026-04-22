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
        <div class="sparatorputih-20">&nbsp;</div>
        <div class="col-md-12">
            <form class="form-horizontal" method="POST" action="{{ url('/formsaveupdate') }}">
                @csrf
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Grouping</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" name="id_profil" value="{{ $profil->id_profil }}">
                  </div>
                  <div class="col-sm-2">
                    <select name="grouping" class="form-control">
                        @foreach ($group as $group)
                            @if ($group->id_profil_kategori == $profil->id_profil_kategori)
                            <option value="{{ $group->id_profil_kategori }}" selected>{{ $group->nama_kategori }}</option>
                            @else
                            <option value="{{ $group->id_profil_kategori }}">{{ $group->nama_kategori }}</option>
                            @endif
                        @endforeach
                      </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Judul</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="judul" id="inputEmail3" value="{{ $profil->judul }}">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Konten</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="konten" id="myeditorinstance">{{ $profil->konten; }}</textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Urutan</label>
                  <div class="col-sm-1">
                    <input type="text" class="form-control" name="urutan" id="inputEmail3" value="{{ $profil->urutan }}">
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">SIMPAN</button>
                  </div>
                </div>
            </form>
        </div>

        <div class="col-md-12" style="text-align:center !important;">
          <style>
            .borderless tr td {
                border: none !important;
            }
          </style>
          <p style="text-align: center;"><strong><span style="font-size: 18pt;">Struktur Organisasi DEN</span></strong></p>
          <p style="text-align: justify;">&nbsp;</p>
          <center>
          <table  class="table borderless" style="width:77%;border-radius:7px !important;">
            <tbody>
              <tr>
                <td class="warnabg-hijau warna-putih rata-tengah cetak-tebal" colspan="2">PIMPINAN</td>
              </tr>
              <tr class="warnabg-grey2">
                <td colspan="2" style="text-align: center;">
                  Ketua: Presiden RI<br>
                  Wakil Ketua: Wakil Presiden RI<br>
                  Ketua Harian: Menteri Energi dan Sumber Daya Mineral<br>
                </td>
              </tr>
              <tr>
                <td class="warnabg-hijau warna-putih rata-tengah cetak-tebal" colspan="2">ANGGOTA</td>
              </tr>
              <tr class="warnabg-grey4 warna-grey5 rata-tengah cetak-tebal">
                <td style="width: 50%;">DARI PEMERINTAH</td>
                <td style="width: 50%;border-left: solid 1px #9A9A9A !important;">DARI PEMANGKU KEPENTINGAN</td>
              </tr>
              <tr class="warnabg-grey2">
                <td>Menteri Keuangan</td>
                <td style="border-left: solid 1px #c1c1c1 !important;">Dr. Ir. Agus Puji Prasetyono M.Eng. IPU. ASEAN Eng. (Akademisi)</td>
              </tr>
              <tr class="warnabg-grey2">
                <td>Menteri Perencanaan Pembangunan/Bappenas</td>
                <td style="border-left: solid 1px #c1c1c1 !important;">Dr. Ir. Musri, M.T. (Akademisi)</td>
              </tr>
              <tr class="warnabg-grey2">
                <td>Menteri Perhubungan</td>
                <td style="border-left: solid 1px #c1c1c1 !important;">Dr. Ir. Satya Widya Yudha MSc.,PhD (Industri)</td>
              </tr>
              <tr class="warnabg-grey2">
                <td>Menteri Perindustrian</td>
                <td style="border-left: solid 1px #c1c1c1 !important;">Dr. Ir. Herman Darnel Ibrahim M.Sc. IPU. (Industri)</td>
              </tr>
              <tr class="warnabg-grey2">
                <td>Menteri Pertanian</td>
                <td style="border-left: solid 1px #c1c1c1 !important;">Ir. H. Daryatmo Mardiyanto (Konsumen)</td>
              </tr>
              <tr class="warnabg-grey2">
                <td>Menteri Riset Teknologi dan Pendidikan Tinggi</td>
                <td style="border-left: solid 1px #c1c1c1 !important;">Dr. Ir. Eri Purnomohadi, M.M. (Konsumen)</td>
              </tr>
              <tr class="warnabg-grey2">
                <td>Menteri Lingkungan Hidup dan Kehutanan</td>
                <td style="border-left: solid 1px #c1c1c1 !important;">Dr. Ir. As Natio Lasman (Teknologi)</td>
              </tr>
              <tr class="warnabg-grey2">
                <td>&nbsp;</td>
                <td style="border-left: solid 1px #c1c1c1 !important;">Dr. (HC) Yusra Khan, S.H. (Lingkungan Hidup)</td>
              </tr>
            </tbody>
          </table>
          </center>
          <p>&nbsp;</p>
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
        toolbar: 'blocks fontsize bold italic underline strikethrough link image media table align lineheight numlist bullist indent outdent emoticons charmap removeformat code',
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

