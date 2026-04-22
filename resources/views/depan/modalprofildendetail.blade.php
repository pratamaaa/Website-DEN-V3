{{-- <div class="modal-header bgwarna-hijau">
    <h6 class="modal-title warna-putih" id="largeModalLabel">{{ $judulhalaman }}</h6>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
</div> --}}

<div class="modal-body">
    <div class="row">
        <div class="col-lg-3">
            <img src="{{ asset('uploads/profilden/'.$den->foto) }}" class="img-fluid" alt="">
        </div>
        
        <div class="col-lg-9">
            <span class="badge badge-primary badge-md warna-putih fontsize-15" style="padding:10px !important;">
                {{ $den->namalengkap }}
            </span>
            
            <p class="text-2 mb-0 lineheight-15 fontsize-13 ratakiri mt-2">
                <i class="icons icon-check warna-hijau"></i> <strong class="fontsize-11">{{ $den->jabatan }}</strong>
            </p>
            <hr class="gradient">
            @php
                echo $den->profil;
            @endphp
        </div>
    </div>
</div>

{{-- <div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
</div> --}}