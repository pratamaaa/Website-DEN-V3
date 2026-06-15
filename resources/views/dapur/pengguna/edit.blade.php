<style>
    .margin1 { margin-bottom: 5px; }
    .margin2 { margin-bottom: -8px; }
    .margin3 { margin-bottom: 7px; }
    .warnafont { color: #000000 !important; }
    .labelku {
        margin-top: 5px !important;
        margin-bottom: 2px !important;
        font-weight: bold;
        font-size: 13px;
    }
    .warnamerah { color: red; }
</style>

<div class="modal-header" style="background-color:#128D93;">
    <h5 class="modal-title h5" id="formProdukTambah_judul" style="color:#ffffff;">
        {{ $judulmodal }}
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body" id="formtambah_body">
    <form id="formulir" name="formulir" method="post" enctype="multipart/form-data">
        @csrf

        {{-- BADGE tipe user --}}
        <div class="mb-2">
            @if($data->nip)
                <span class="badge badge-warning text-dark">
                    <i class="feather icon-shield"></i> User SSO ESDM
                </span>
            @else
                <span class="badge badge-secondary">
                    <i class="feather icon-user"></i> User Manual
                </span>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">

                {{-- LEVEL --}}
                <div class="form-group mb-1">
                    <label class="labelku" style="color:#626365;font-size:13px;"><b>Level pengguna:</b></label>
                    <select class="form-control" id="id_user_level" name="id_user_level" style="width:50%;">
                        @if($kategori->count() != 0)
                            @foreach($kategori->get() as $kat)
                                <option value="{{ $kat->id_user_level }}"
                                    {{ $kat->id_user_level == $data->id_user_level ? 'selected' : '' }}>
                                    {{ strtoupper($kat->level) }}
                                </option>
                            @endforeach
                        @else
                            <option>Kategori not found</option>
                        @endif
                    </select>
                </div>

                {{-- USERNAME --}}
                <div class="form-group mb-1">
                    <label class="labelku" style="color:#626365;font-size:13px;"><b>Username:</b></label>
                    <input type="text" class="form-control form-control-sm"
                        value="{{ $data->username }}" id="username" name="username"
                        style="width:100%;" {{ $data->nip ? 'readonly' : '' }}>
                    <input type="hidden" value="{{ $data->id }}" id="id_pengguna" name="id_pengguna">
                </div>

                {{-- NAMA --}}
                <div class="form-group mb-1">
                    <label class="labelku" style="color:#626365;font-size:13px;"><b>Nama lengkap:</b></label>
                    <input type="text" class="form-control form-control-sm"
                        value="{{ $data->name }}" id="name" name="name"
                        style="width:100%;" {{ $data->nip ? 'readonly' : '' }}>
                </div>

                {{-- EMAIL --}}
                <div class="form-group mb-1">
                    <label class="labelku" style="color:#626365;font-size:13px;"><b>Email:</b></label>
                    <input type="text" class="form-control form-control-sm"
                        value="{{ $data->email }}" id="email" name="email"
                        style="width:100%;" {{ $data->nip ? 'readonly' : '' }}>
                </div>

                {{-- NIP — hanya tampil kalau SSO --}}
                @if($data->nip)
                <div class="form-group mb-1">
                    <label class="labelku" style="color:#626365;font-size:13px;"><b>NIP:</b></label>
                    <input type="text" class="form-control form-control-sm"
                        value="{{ $data->nip }}" readonly style="width:60%; background:#f5f5f5;">
                    <small class="text-muted">Data dari SSO, tidak dapat diubah.</small>
                </div>
                @endif

                {{-- IS ACTIVE --}}
                <div class="form-group mb-1">
                    <label class="labelku" style="color:#626365;font-size:13px;"><b>Status Akun:</b></label><br>
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ $data->is_active ? 'checked' : '' }}>
                    <label for="is_active"> Aktif</label>
                </div>

                {{-- PASSWORD — hanya untuk user manual --}}
                @if(!$data->nip)
                <div class="form-group mb-1">
                    <label class="labelku"><b>Password (opsional):</b></label>
                    <input type="password" class="form-control form-control-sm"
                        id="password" name="password" style="width:60%;">
                    <div class="progress mt-1" style="height:6px; width:60%;">
                        <div id="password-strength-bar" class="progress-bar"></div>
                    </div>
                    <small id="password-rules" class="form-text text-muted">
                        ❌ Minimal 12 karakter <br>
                        ❌ Huruf besar & kecil <br>
                        ❌ Angka <br>
                        ❌ Simbol
                    </small>
                </div>

                <div class="form-group mb-1">
                    <label class="labelku"><b>Konfirmasi Password:</b></label>
                    <input type="password" class="form-control form-control-sm"
                        id="password_confirmation" name="password_confirmation" style="width:60%;">
                </div>

                <div class="form-group mb-1">
                    <input type="checkbox" id="showPass">
                    <label for="showPass"> Tampilkan Password</label>
                </div>
                @endif

            </div>

            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-info btn-sm">
                    <i class="feather icon-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="feather icon-x"></i> Batal
                </button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $.validator.setDefaults({
        submitHandler: function() {
            event.preventDefault();

            $.ajax({
                url: "{{ url('/dap/pengguna/saveupdate') }}",
                type: 'POST',
                data: new FormData($('#formulir')[0]),
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(resp) {
                    if (resp.result == "success") {
                        swal({ title: "", text: resp.message, icon: "success" })
                        .then(function() {
                            $('#modalku').modal('toggle');
                            reloadTable();
                        });
                    } else {
                        var span = document.createElement("span");
                        span.innerHTML = resp.message + '<br><br>' + resp.errors;
                        swal({ html: true, title: "", content: span, icon: "error" })
                        .then(function() {
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
                                '<span class="jquery-validation-error small text-danger">' + value[0] + '</span>'
                            );
                        });
                    }
                }
            });
        },
        errorPlacement: function(error, element) {
            var $parent = $(element).parents('.form-group');
            if ($parent.find('.jquery-validation-error').length) return;
            $parent.append(error.addClass('jquery-validation-error small form-text invalid-feedback'));
        },
        highlight: function(element) {
            $(element).parents('.form-group').find('[name="' + element.name + '"]').addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
        },
    });

    $(document).ready(function() {
        @if(!$data->nip)
        // Validasi hanya untuk user manual
        $('#formulir').validate({
            rules: {
                username: { required: true },
                name: { required: true },
                email: { required: true, email: true },
                password: { minlength: 12 },
                password_confirmation: { equalTo: '#password' },
            }
        });

        $('#password').on('keyup', function() {
            let val = $(this).val();
            if (val.length === 0) {
                $('#password-strength-bar').css('width', '0%');
                $('#password-rules').html(`❌ Minimal 12 karakter <br>❌ Huruf besar & kecil <br>❌ Angka <br>❌ Simbol`);
                return;
            }
            let checkLength = val.length >= 12;
            let checkCase   = /[a-z]/.test(val) && /[A-Z]/.test(val);
            let checkNumber = /[0-9]/.test(val);
            let checkSymbol = /[^A-Za-z0-9]/.test(val);
            let strength    = [checkLength, checkCase, checkNumber, checkSymbol].filter(Boolean).length;

            $('#password-rules').html(
                (checkLength ? '✅' : '❌') + ' Minimal 12 karakter <br>' +
                (checkCase   ? '✅' : '❌') + ' Huruf besar & kecil <br>' +
                (checkNumber ? '✅' : '❌') + ' Angka <br>' +
                (checkSymbol ? '✅' : '❌') + ' Simbol'
            );

            let bar = $('#password-strength-bar');
            if (strength <= 1)     bar.css('width','25%').removeClass().addClass('progress-bar bg-danger');
            else if (strength == 2) bar.css('width','50%').removeClass().addClass('progress-bar bg-warning');
            else if (strength == 3) bar.css('width','75%').removeClass().addClass('progress-bar bg-info');
            else                    bar.css('width','100%').removeClass().addClass('progress-bar bg-success');
        });

        $('#showPass').on('change', function() {
            let type = this.checked ? 'text' : 'password';
            $('#password, #password_confirmation').attr('type', type);
        });
        @else
        // User SSO: validasi minimal
        $('#formulir').validate({
            rules: {
                id_user_level: { required: true },
            }
        });
        @endif
    });
</script>