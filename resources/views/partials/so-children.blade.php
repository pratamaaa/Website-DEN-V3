@php $count = $children->count(); @endphp

{{-- Render semua card di level ini secara sejajar --}}
<div class="so-level">
    @foreach ($children as $child)
        <div class="so-group">
            <div class="so-card so-card--level{{ $level }}">
                <div class="so-card__photo">
                    <img src="{{ $child->foto ? asset('uploads/strukturorganisasi/' . $child->foto) : asset('uploads/default-image/default-avatar.png') }}"
                        alt="{{ $child->nama_lengkap }}">
                </div>
                <div class="so-card__info">
                    <div class="so-card__jabatan">{{ $child->jabatan }}</div>
                    <div class="so-card__nama">{{ $child->nama_lengkap }}</div>
                </div>
            </div>

            {{-- Connector + children milik card ini --}}
            @if ($child->allChildren->count() > 0)
                <div class="so-connector-v"></div>
                <div class="so-level">
                    @foreach ($child->allChildren as $grandchild)
                        <div class="so-group">
                            <div class="so-card so-card--level{{ $level + 1 }}">
                                <div class="so-card__photo">
                                    <img src="{{ $grandchild->foto ? asset('uploads/strukturorganisasi/' . $grandchild->foto) : asset('uploads/default-image/default-avatar.png') }}"
                                        alt="{{ $grandchild->nama_lengkap }}">
                                </div>
                                <div class="so-card__info">
                                    <div class="so-card__jabatan">{{ $grandchild->jabatan }}</div>
                                    <div class="so-card__nama">{{ $grandchild->nama_lengkap }}</div>
                                </div>
                            </div>

                            {{-- Level lebih dalam lagi jika ada --}}
                            @if ($grandchild->allChildren->count() > 0)
                                <div class="so-connector-v"></div>
                                @include('partials.so-children', [
                                    'children' => $grandchild->allChildren,
                                    'level' => $level + 2,
                                ])
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
