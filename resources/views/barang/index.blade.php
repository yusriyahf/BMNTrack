@extends('layouts.app')

@section('title', 'Semua Barang')
@section('page-title', 'Data Barang')
@section('page-subtitle', 'Seluruh inventaris barang dari semua ruangan')

@push('styles')
<style>
/* Clickable thumbnail */
.img-thumbnail {
    width: 52px; height: 52px;
    object-fit: cover; border-radius: 8px;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: transform .2s, box-shadow .2s;
    display: block;
}
.img-thumbnail:hover { transform: scale(1.08); box-shadow: 0 4px 14px rgba(0,0,0,.18); }
.img-placeholder {
    width: 52px; height: 52px;
    border-radius: 8px;
    background: var(--primary-ultra);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: var(--primary); opacity: .5;
    border: 1px solid var(--border);
}

/* Lightbox */
.bmnmodal-backdrop {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    opacity: 0; pointer-events: none;
    transition: opacity .25s;
}
.bmnmodal-backdrop.show { opacity: 1; pointer-events: all; }
.bmnmodal-inner {
    display: flex; flex-direction: column; align-items: center;
}
.bmnmodal-img-wrap {
    max-width: 92vw; max-height: 86vh;
    border-radius: 14px; overflow: hidden;
    box-shadow: 0 24px 60px rgba(0,0,0,.6);
    transform: scale(.9); transition: transform .25s;
}
.bmnmodal-backdrop.show .bmnmodal-img-wrap { transform: scale(1); }
.bmnmodal-img-wrap img { display: block; max-width: 100%; max-height: 82vh; object-fit: contain; }
.bmnmodal-caption {
    color: #fff; font-size: 14px; font-weight: 600;
    margin-top: 10px; text-shadow: 0 1px 4px rgba(0,0,0,.6);
    text-align: center;
}
.bmnmodal-close {
    position: absolute; top: 14px; right: 14px;
    width: 34px; height: 34px;
    background: rgba(0,0,0,.4); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; cursor: pointer; z-index: 10;
    transition: background .2s;
}
.bmnmodal-close:hover { background: rgba(0,0,0,.65); }

/* Ruangan filter cascade hint */
#ruangan_id optgroup { font-weight: 700; }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-between align-items-center flex-wrap gap-2">
    <div>
        <h1>Data Barang</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
            <span>/</span> <span>Barang</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="flex-wrap:wrap;gap:12px;">
        <h5><i class="fas fa-boxes-stacked text-primary"></i> Daftar Seluruh Barang</h5>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <span class="badge badge-primary">{{ $barang->total() }} Item</span>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" action="{{ route('barang.index') }}"
          style="display:flex;gap:12px;flex-wrap:wrap;padding:16px 24px;background:var(--primary-ultra);border-bottom:1px solid var(--primary-xlight);">

        {{-- Kondisi --}}
        <select name="kondisi" class="form-control" style="max-width:160px;">
            <option value="">Semua Kondisi</option>
            <option value="Aman"  {{ request('kondisi') === 'Aman'  ? 'selected' : '' }}>Kondisi Aman</option>
            <option value="Rusak" {{ request('kondisi') === 'Rusak' ? 'selected' : '' }}>Kondisi Rusak</option>
        </select>

        {{-- Gedung --}}
        <select name="gedung_id" id="filterGedung" class="form-control" style="max-width:180px;"
                onchange="cascadeRuangan(this.value)">
            <option value="">Semua Gedung</option>
            @foreach($gedungs as $g)
            <option value="{{ $g->id }}" {{ request('gedung_id') == $g->id ? 'selected' : '' }}>
                {{ $g->nama_gedung }}
            </option>
            @endforeach
        </select>

        {{-- Ruangan (cascade) --}}
        <select name="ruangan_id" id="filterRuangan" class="form-control" style="max-width:200px;">
            <option value="">Semua Ruangan</option>
            @foreach($ruangans as $r)
            <option value="{{ $r->id }}"
                    data-gedung="{{ $r->gedung_id }}"
                    {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                {{ $r->nama_ruangan }}
            </option>
            @endforeach
        </select>

        {{-- Search --}}
        <input type="text" name="search" class="form-control" style="max-width:220px;flex:1;"
            placeholder="Cari nama barang..." value="{{ request('search') }}">

        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('barang.index') }}" class="btn btn-outline"><i class="fas fa-rotate-left"></i> Reset</a>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Ruangan</th>
                    <th>Lantai</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $i => $b)
                <tr>
                    <td>{{ $barang->firstItem() + $i }}</td>
                    <td>
                        @if($b->foto_barang)
                            <img src="{{ asset('storage/' . $b->foto_barang) }}"
                                 class="img-thumbnail"
                                 alt="{{ $b->nama_barang }}"
                                 onclick="openImgModal('{{ asset('storage/'.$b->foto_barang) }}', '{{ addslashes($b->nama_barang) }}')">
                        @else
                            <div class="img-placeholder"><i class="fas fa-box"></i></div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $b->nama_barang }}</div>
                        @if($b->keterangan)
                        <div style="font-size:11.5px;color:var(--text-light);">{{ Str::limit($b->keterangan, 50) }}</div>
                        @endif
                    </td>
                    <td>
                        @if($b->kategori)
                        <span class="badge badge-gray">{{ $b->kategori }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ruangan.show', $b->ruangan) }}" style="color:var(--primary);text-decoration:none;font-weight:600;">
                            {{ $b->ruangan->nama_ruangan ?? '-' }}
                        </a>
                        @if($b->ruangan?->gedung)
                        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">
                            <i class="fas fa-building" style="font-size:10px;"></i> {{ $b->ruangan->gedung->nama_gedung }}
                        </div>
                        @endif
                    </td>
                    <td style="font-size:13px;">
                        @if($b->ruangan?->lantai)
                            <span style="font-weight:600;">Lantai {{ $b->ruangan->lantai }}</span>
                        @else
                            <span style="color:var(--text-light);">-</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight:700;color:var(--primary);">{{ number_format($b->jumlah) }}</span>
                        <span style="font-size:11.5px;color:var(--text-light);"> unit</span>
                    </td>
                    <td>
                        <span class="badge {{ $b->kondisi === 'Aman' ? 'badge-success' : 'badge-danger' }}">
                            <i class="fas {{ $b->kondisi === 'Aman' ? 'fa-shield-check' : 'fa-triangle-exclamation' }}"></i>
                            {{ $b->kondisi }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('barang.edit', $b) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('barang.destroy', $b) }}"
                                  onsubmit="return confirm('Hapus barang {{ addslashes($b->nama_barang) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-boxes-stacked"></i>
                            <p>Belum ada data barang ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($barang->hasPages())
    <div class="pagination-wrapper">
        {{ $barang->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

<!-- Lightbox Modal -->
<div class="bmnmodal-backdrop" id="imgModal" onclick="closeImgModal()">
    <span class="bmnmodal-close" onclick="closeImgModal()"><i class="fas fa-times"></i></span>
    <div class="bmnmodal-inner" onclick="event.stopPropagation()">
        <div class="bmnmodal-img-wrap">
            <img id="imgModalSrc" src="" alt="">
        </div>
        <div class="bmnmodal-caption" id="imgModalCaption"></div>
    </div>
</div>

@push('scripts')
<script>
// ── Image lightbox ──────────────────────────────
function openImgModal(src, caption) {
    document.getElementById('imgModalSrc').src     = src;
    document.getElementById('imgModalSrc').alt     = caption || '';
    document.getElementById('imgModalCaption').textContent = caption || '';
    document.getElementById('imgModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeImgModal() {
    document.getElementById('imgModal').classList.remove('show');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeImgModal();
});

// ── Cascade Ruangan filter by Gedung ───────────
const allOptions = Array.from(document.querySelectorAll('#filterRuangan option[data-gedung]'));

function cascadeRuangan(gedungId) {
    const sel = document.getElementById('filterRuangan');
    // save current selected
    const cur = sel.value;
    // remove all non-default options
    allOptions.forEach(o => o.remove());
    // re-add filtered
    const toAdd = gedungId ? allOptions.filter(o => o.dataset.gedung == gedungId) : allOptions;
    toAdd.forEach(o => sel.appendChild(o));
    // reset if previously selected not in new list
    if (!toAdd.find(o => o.value === cur)) sel.value = '';
    else sel.value = cur;
}

// run cascade on page load to apply current ?gedung_id= filter
(function() {
    const gedungId = document.getElementById('filterGedung').value;
    if (gedungId) cascadeRuangan(gedungId);
})();
</script>
@endpush
@endsection
