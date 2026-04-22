@extends('layout.dapur.app')

@section('content')
    <style>
        .note-editor {
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>

    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <nav class="navbar" style="margin-left:-10px !important;">
                                <span class="m-r-15">
                                    <h5>{{ $judulhalaman }}</h5>
                                </span>
                            </nav>
                            <hr>

                            {{-- Tab Indonesia / English --}}
                            <ul class="nav nav-tabs mb-3" id="tabKonten">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabID">
                                        🇮🇩 Indonesia
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabEN">
                                        🇺🇸 English
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tabID">
                                    <textarea id="konten" name="konten">{{ $data->konten ?? '' }}</textarea>
                                </div>
                                <div class="tab-pane" id="tabEN">
                                    <textarea id="konten_en" name="konten_en">{{ $data->konten_en ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="button" onclick="simpan()" class="btn btn-primary waves-effect waves-light">
                                    <i class="feather icon-save"></i> Simpan
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Init Summernote
        $(function() {
            $('#konten').summernote({
                height: 500,
                lang: 'id-ID',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']],
                ]
            });

            $('#konten_en').summernote({
                height: 500,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']],
                ]
            });
        });

        function simpan() {
            $.ajax({
                url: "{{ url('/dap/tugasfungsi/save') }}",
                type: 'POST',
                data: {
                    konten: $('#konten').summernote('code'),
                    konten_en: $('#konten_en').summernote('code'),
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.result == 'success') {
                        swal({
                            title: "",
                            text: resp.message,
                            icon: "success"
                        });
                    } else {
                        swal({
                            title: "Gagal",
                            text: resp.message,
                            icon: "error"
                        });
                    }
                },
                error: function(jqXHR) {
                    console.log(jqXHR.responseText);
                }
            });
        }
    </script>
@endsection
