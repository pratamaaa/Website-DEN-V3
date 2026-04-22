@if (File::exists(public_path('uploads/'.$namafolder.'/'.$data->berkas)))
    <div class="modal-body" style="padding: 0px !important; background-color: #525659;">
        <object data="{{ asset('/uploads/'.$namafolder.'/'.$data->berkas) }}#view=fitH" type="application/pdf" style="width: 100%; height: 800px; border: none;margin-bottom:0px;"></object>
    </div>
@else
    {{-- MODAL HEADER --}}
    <div class="modal-header bgwarna-hijau">
        <h6 class="modal-title warna-putih" id="largeModalLabel">{{ $judulhalaman }}</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">File tidak tersedia</div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
</div>
@endif

