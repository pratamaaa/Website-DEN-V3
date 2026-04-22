@extends('layout.depan.app')

@section('content')
<div role="main" class="main">
    <section class="page-header_ page-header-modern bg-color-light-scale-1 page-header-md">
        <div class="container py-4">
            <section class="http-error">
                <div class="row justify-content-center py-3">
                    <div class="col-md-7 text-center">
                        <div class="http-error-main">
                            <h2>404!</h2>
                            <p>We're sorry, but the <b>{{ $namapage }}</b> page you were looking for doesn't exist.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    
});
</script>
@endsection