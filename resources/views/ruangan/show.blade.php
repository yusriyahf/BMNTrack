@extends('layouts.app')

@section('title', 'Detail Ruangan – ' . $ruangan->nama_ruangan)
@section('page-title', $ruangan->nama_ruangan)
@section('page-subtitle', 'Detail ruangan dan daftar aset di dalamnya')

@push('styles')
<style>
.ruangan-detail-header {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
    border-radius: var(--radius);
    padding: 28px;
    color: #fff;
    margin-bottom: 24px;
    display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap;
    box-shadow: 0 4px 20px rgba(26,79,186,.25);
    position: relative; overflow: hidden;
}
.ruangan-detail-header::before {
    content: '';
    position: absolute; top: -60px; right: -60px;
    width: 220px; height: 220px; border-radius: 50%;
    border: 50px solid rgba(255,255,255,.06);
}
.ruangan-foto {
    width: 140px; height: 110px;
    border-radius: 12px; overflow: hidden;
    border: 3px solid rgba(255,255,255,.3);
    flex-shrink: 0;
    background: rgba(255,255,255,.1);
    display: flex; align-items: center; justify-content: center;
}
.ruangan-foto img { width: 100%; height: 100%; object-fit: cover; }
.ruangan-foto-placeholder { font-size: 42px; opacity: .5; }
.ruangan-info { flex: 1; position: relative; z-index: 1; }
.ruangan-info h2 { font-size: 22px; font-weight: 800; margin-bottom: 12px; }
.info-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
}
.info-item {
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px; padding: 10px 12px;
}
.info-item label { display: block; font-size: 11px; opacity: .7; margin-bottom: 3px; text-transform: uppercase; letter-spacing: .5px; }
.info-item span { font-size: 14px; font-weight: 600; }

.ruangan-actions {
    display: flex; flex-direction: column; gap: 8px; flex-shrink: 0;
    position: relative; z-index: 1;
}
.ruangan-actions .btn { justify-content: center; }

/* Barang Grid */
.barang-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
    padding: 24px;
}
.barang-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    box-shadow: var(--shadow);
}
.barang-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.barang-card-img {
    width: 100%; height: 140px; overflow: hidden;
    position: relative;
}
.barang-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
.barang-card-img-placeholder {
    width: 100%; height: 140px;
    background: linear-gradient(135deg, var(--primary-ultra), var(--primary-xlight));
    display: flex; align-items: center; justify-content: center;
    font-size: 38px; color: var(--primary); opacity: .4;
}
.kondisi-badge-overlay {
    position: absolute; top: 8px; right: 8px;
}
.barang-card-body { padding: 14px; }
.barang-card-title { font-size: 14px; font-weight: 700; margin-bottom: 8px; }
.barang-card-meta { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 10px; }
.barang-card-desc { font-size: 12px; color: var(--text-light); margin-bottom: 10px; }
.barang-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-top: 1px solid var(--border);
    background: var(--bg-body);
}
.jumlah-badge {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 700; color: var(--primary);
}

@media (max-width: 640px) {
    .ruangan-detail-header { flex-direction: column; }
    .ruangan-actions { flex-direction: row; }
    .barang-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
}
@media (max-width: 380px) {
    .barang-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
        <span>/</span> <a href="{{ route('ruangan.index') }}">Ruangan</a>
        <span>/</span> <span>{{ $ruangan->nama_ruangan }}</span>
    </div>
</div>

<!-- Ruangan Header Card -->
<div class="ruangan-detail-header">
    <div class="ruangan-foto">
        @if($ruangan->foto_ruangan)
            <img src="{{ asset('storage/' . $ruangan->foto_ruangan) }}" alt="{{ $ruangan->nama_ruangan }}">
        @else
            <i class="fas fa-door-open ruangan-foto-placeholder"></i>
        @endif
    </div>

    <div class="ruangan-info">
        <h2>{{ $ruangan->nama_ruangan }}</h2>
        <div class="info-grid">
            <div class="info-item">
                <label><i class="fas fa-building"></i> Gedung</label>
                <span>{{ $ruangan->gedung->nama_gedung ?? '-' }}</span>
            </div>
            <div class="info-item">
                <label><i class="fas fa-layer-group"></i> Lantai</label>
                <span>Lantai {{ $ruangan->lantai }}</span>
            </div>
            <div class="info-item">
                <label><i class="fas fa-expand"></i> Luas</label>
                <span>{{ $ruangan->luas_ruangan ?? '-' }}</span>
            </div>
            <div class="info-item">
                <label><i class="fas fa-user-tie"></i> PIC</label>
                <span>{{ $ruangan->pic_ruangan ?? '-' }}</span>
            </div>
            <div class="info-item">
                <label><i class="fas fa-calendar"></i> Tanggal Pendataan</label>
                <span>{{ optional($ruangan->tanggal_pendataan)->format('d/m/Y') ?? '-' }}</span>
            </div>
            <div class="info-item">
                <label><i class="fas fa-box"></i> Total Barang</label>
                <span>{{ $ruangan->barang->count() }} jenis ({{ $ruangan->barang->sum('jumlah') }} unit)</span>
            </div>
        </div>
    </div>

    <div class="ruangan-actions">
        <a href="{{ route('ruangan.barang.create', $ruangan) }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
        <a href="{{ route('ruangan.edit', $ruangan) }}" class="btn btn-outline" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.3);color:#fff;">
            <i class="fas fa-pen"></i> Edit Ruangan
        </a>
    </div>
</div>

<!-- Barang List -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-boxes-stacked text-primary"></i> Daftar Inventaris Barang</h5>
        <div class="d-flex gap-2 align-items-center">
            @php
                $totalAman  = $ruangan->barang->where('kondisi', 'Aman')->count();
                $totalRusak = $ruangan->barang->where('kondisi', 'Rusak')->count();
            @endphp
            <span class="badge badge-success"><i class="fas fa-shield-check"></i> {{ $totalAman }} Aman</span>
            <span class="badge badge-danger"><i class="fas fa-triangle-exclamation"></i> {{ $totalRusak }} Rusak</span>
        </div>
    </div>

    @if($ruangan->barang->isEmpty())
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <p>Belum ada barang di ruangan ini.</p>
        <a href="{{ route('ruangan.barang.create', $ruangan) }}" class="btn btn-primary" style="margin-top:12px;">
            <i class="fas fa-plus"></i> Tambah Barang Pertama
        </a>
    </div>
    @else
    <div class="barang-grid">
        @foreach($ruangan->barang as $b)
        <div class="barang-card">
            <div class="barang-card-img">
                @if($b->foto_barang)
                    <img src="{{ asset('storage/' . $b->foto_barang) }}" alt="{{ $b->nama_barang }}">
                @else
                    <div class="barang-card-img-placeholder"><i class="fas fa-box"></i></div>
                @endif
                <div class="kondisi-badge-overlay">
                    <span class="badge {{ $b->kondisi === 'Aman' ? 'badge-success' : 'badge-danger' }}">
                        {{ $b->kondisi === 'Aman' ? '✓' : '!' }} {{ $b->kondisi }}
                    </span>
                </div>
            </div>
            <div class="barang-card-body">
                <div class="barang-card-title">{{ $b->nama_barang }}</div>
                <div class="barang-card-meta">
                    @if($b->kategori)
                    <span class="badge badge-primary"><i class="fas fa-tag"></i> {{ $b->kategori }}</span>
                    @endif
                </div>
                @if($b->keterangan)
                <div class="barang-card-desc">{{ Str::limit($b->keterangan, 70) }}</div>
                @endif
            </div>
            <div class="barang-card-footer">
                <div class="jumlah-badge">
                    <i class="fas fa-cubes"></i>
                    {{ number_format($b->jumlah) }} Unit
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('barang.edit', $b) }}" class="btn btn-warning btn-sm btn-icon" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('barang.destroy', $b) }}"
                          onsubmit="return confirm('Hapus barang {{ addslashes($b->nama_barang) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
