@extends('layout.dapur.app')

@section('content')
    <style>
        .table th {
            text-align: center;
        }

        .table td,
        .table th {
            padding: 7px 7px;
        }

        .ratakanan {
            text-align: right;
        }

        .ratatengah {
            text-align: center !important;
        }
    </style>

    <section class="pcoded-main-container">
        <div class="pcoded-content">

            <div class="row">
                <div class="col-sm-6">
                    <div class="card">

                        <div class="card-body">
                            <nav class="navbar" style="margin-left:-10px !important;">
                                <span class="m-r-15">
                                    <h5>{{ $judulhalaman }}</h5>
                                </span>
                                <div class="nav-item nav-grid f-view">
                                    {{-- <button type="button" id="btnFormTambah2" onclick="showFormadd()" class="btn waves-effect waves-light btn-primary btn-sm btn-icon m-0" data-toggle="tooltip" data-placement="top" title="Tambah">
                                    <i class="feather icon-plus-circle"></i> Tambah
                                </button>
                                <button type="button" id="btnFormTambah2" onclick="reloadTable()" class="btn waves-effect waves-light btn-primary btn-icon m-0" data-toggle="tooltip" data-placement="top" title="Tambah">
									<i class="feather icon-refresh-cw"></i> Muat Ulang
								</button> --}}
                                </div>
                            </nav>

                            <hr>
                            <form id="formulir" name="formulir" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-1">
                                            <label class="labelku"><b>Password sekarang:</b></label>
                                            <input type="password" class="form-control form-control-sm"
                                                id="password_sekarang" name="password_sekarang">
                                        </div>

                                        <div class="form-group mb-1">
                                            <label class="labelku"><b>Password baru:</b></label>
                                            <input type="password" class="form-control form-control-sm" id="password_baru"
                                                name="password_baru">

                                            <!-- Strength -->
                                            <div class="progress mt-1" style="height:6px;">
                                                <div id="strength-bar" class="progress-bar"></div>
                                            </div>

                                            <!-- Checklist -->
                                            <small id="password-rules" class="form-text text-muted">
                                                ❌ Minimal 12 karakter <br>
                                                ❌ Huruf besar & kecil <br>
                                                ❌ Angka <br>
                                                ❌ Simbol
                                            </small>
                                        </div>

                                        <div class="form-group mb-1">
                                            <label class="labelku"><b>Konfirmasi password baru:</b></label>
                                            <input type="password" class="form-control form-control-sm"
                                                id="password_baru_confirmation" name="password_baru_confirmation">
                                        </div>

                                        <div class="form-group mb-1">
                                            <input type="checkbox" id="showPass">
                                            <label for="showPass"> Tampilkan Password</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-right mt-3">
                                        <button type="submit" class="btn btn-info btn-sm" value="Simpan"><i
                                                class="feather icon-save"></i> Simpan</button>
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                                                class="feather icon-x"></i> Batal</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.validator.setDefaults({
            submitHandler: function() {
                event.preventDefault();
                proceed = true;

                $.ajax({
                    url: "{{ url('/dap/pengguna/passwordupdate') }}",
                    type: 'POST',
                    data: new FormData($('#formulir')[0]),
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        if (resp.result == "success") {
                            swal({
                                title: "",
                                text: resp.message,
                                icon: "success",
                            }).then(function() {
                                $('#modalku').modal('toggle');
                                reloadTable();
                            });
                        } else {
                            var span = document.createElement("span");
                            span.innerHTML = resp.message + '<br><br>' + resp.errors;
                            swal({
                                html: true,
                                title: "",
                                content: span,
                                icon: "error",
                            }).then(function() {
                                $('#modalku').modal('toggle');
                                reloadTable();
                            });
                        }
                    },
                    error: function(jqXHR) {
                        if (jqXHR.status === 422) {
                            let errors = jqXHR.responseJSON.errors;

                            $('.is-invalid').removeClass('is-invalid');
                            $('.jquery-validation-error').remove();

                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]');

                                input.addClass('is-invalid');

                                input.closest('.form-group').append(
                                    '<span class="text-danger small jquery-validation-error">' +
                                    value[0] + '</span>'
                                );
                            });
                        } else {
                            console.log(jqXHR);
                        }
                    }
                });
            },
            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }
                $parent.append(error.addClass('jquery-validation-error small form-text invalid-feedback'));
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');
                $el.addClass('is-invalid');
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            },
        });

        $(document).ready(function(e) {
            $('#nama_page').prop("disabled", true);
            $('.datepicker').datepicker({
                language: "en",
                autoclose: true,
                format: "yyyy/mm/dd",
            });

            $('#username').focus();
            $('.summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 200,
            });

            $('#formulir').validate({
                rules: {
                    password_sekarang: {
                        required: true
                    },
                    password_baru: {
                        required: true,
                        minlength: 12
                    },
                    password_baru_confirmation: {
                        required: true,
                        equalTo: '#password_baru'
                    }
                }
            });
        });

        $('#password_baru').on('keyup', function() {
            let val = $(this).val();

            let strength = 0;

            let checkLength = val.length >= 12;
            let checkCase = /[a-z]/.test(val) && /[A-Z]/.test(val);
            let checkNumber = /[0-9]/.test(val);
            let checkSymbol = /[^A-Za-z0-9]/.test(val);

            if (checkLength) strength++;
            if (checkCase) strength++;
            if (checkNumber) strength++;
            if (checkSymbol) strength++;

            let html = '';
            html += (checkLength ? '✅' : '❌') + ' Minimal 12 karakter <br>';
            html += (checkCase ? '✅' : '❌') + ' Huruf besar & kecil <br>';
            html += (checkNumber ? '✅' : '❌') + ' Angka <br>';
            html += (checkSymbol ? '✅' : '❌') + ' Simbol';

            $('#password-rules').html(html);

            let bar = $('#strength-bar');

            if (strength <= 1) {
                bar.css('width', '25%').removeClass().addClass('progress-bar bg-danger');
            } else if (strength == 2) {
                bar.css('width', '50%').removeClass().addClass('progress-bar bg-warning');
            } else if (strength == 3) {
                bar.css('width', '75%').removeClass().addClass('progress-bar bg-info');
            } else {
                bar.css('width', '100%').removeClass().addClass('progress-bar bg-success');
            }
        });
        $('#showPass').on('change', function() {
            let type = this.checked ? 'text' : 'password';
            $('#password_sekarang, #password_baru, #password_baru_confirmation').attr('type', type);
        });
    </script>
@endsection
