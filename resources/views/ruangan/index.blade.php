@extends('layouts.app')

@section('title', 'Data Ruangan')
@section('page-title', 'Data Ruangan')
@section('page-subtitle', 'Kelola data ruangan dan aset di dalamnya')

@push('styles')
<style>
.filter-bar {
    display: flex; gap: 12px; flex-wrap: wrap;
    padding: 16px 24px;
    background: var(--primary-ultra);
    border-bottom: 1px solid var(--primary-xlight);
}
.filter-bar .form-control { max-width: 220px; }
.ruangan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 18px;
    padding: 24px;
}
.ruangan-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    box-shadow: var(--shadow);
}
.ruangan-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.ruangan-card-img {
    width: 100%; height: 150px;
    object-fit: cover;
    display: block;
}
.ruangan-card-img-placeholder {
    width: 100%; height: 150px;
    background: linear-gradient(135deg, var(--primary-ultra), var(--primary-xlight));
    display: flex; align-items: center; justify-content: center;
    font-size: 40px; color: var(--primary); opacity: .5;
}
.ruangan-card-body { padding: 16px; }
.ruangan-card-title {
    font-size: 14px; font-weight: 700; margin-bottom: 8px;
    color: var(--text-dark);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ruangan-card-meta {
    display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px;
}
.ruangan-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px;
    border-top: 1px solid var(--border);
    background: var(--primary-ultra);
}
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-between align-items-center flex-wrap gap-2">
    <div>
        <h1>Data Ruangan</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
            <span>/</span> <span>Ruangan</span>
        </div>
    </div>
    <a href="{{ route('ruangan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Ruangan
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-door-open text-primary"></i> Daftar Ruangan</h5>
        <span class="badge badge-primary">{{ $ruangan->total() }} Ruangan</span>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('ruangan.index') }}" class="filter-bar">
        <select name="gedung_id" class="form-control">
            <option value="">Semua Gedung</option>
            @foreach($gedungs as $g)
            <option value="{{ $g->id }}" {{ request('gedung_id') == $g->id ? 'selected' : '' }}>
                {{ $g->nama_gedung }}
            </option>
            @endforeach
        </select>
        <input type="text" name="search" class="form-control"
            placeholder="Cari nama ruangan..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Filter
        </button>
        <a href="{{ route('ruangan.index') }}" class="btn btn-outline">
            <i class="fas fa-rotate-left"></i> Reset
        </a>
    </form>

    <!-- Ruangan Cards -->
    <div class="ruangan-grid">
        @forelse($ruangan as $r)
        <div class="ruangan-card">
            @if($r->foto_ruangan)
                <img src="{{ asset('storage/' . $r->foto_ruangan) }}" alt="{{ $r->nama_ruangan }}" class="ruangan-card-img">
            @else
                <div class="ruangan-card-img-placeholder"><i class="fas fa-door-open"></i></div>
            @endif
            <div class="ruangan-card-body">
                <div class="ruangan-card-title">{{ $r->nama_ruangan }}</div>
                <div class="ruangan-card-meta">
                    <span class="badge badge-primary">
                        <i class="fas fa-building"></i> {{ $r->gedung->nama_gedung ?? '-' }}
                    </span>
                    <span class="badge badge-gray">
                        <i class="fas fa-layer-group"></i> Lantai {{ $r->lantai }}
                    </span>
                    <span class="badge badge-warning">
                        <i class="fas fa-box"></i> {{ $r->barang_count }} Barang
                    </span>
                </div>
                @if($r->pic_ruangan)
                <div style="font-size:12px;color:var(--text-light);">
                    <i class="fas fa-user-tie"></i> PIC: {{ $r->pic_ruangan }}
                </div>
                @endif
                @if($r->luas_ruangan)
                <div style="font-size:12px;color:var(--text-light);margin-top:3px;">
                    <i class="fas fa-expand"></i> Luas: {{ $r->luas_ruangan }}
                </div>
                @endif
            </div>
            <div class="ruangan-card-footer">
                <a href="{{ route('ruangan.show', $r) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> Detail
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('ruangan.edit', $r) }}" class="btn btn-warning btn-sm btn-icon">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('ruangan.destroy', $r) }}"
                          onsubmit="return confirm('Hapus ruangan {{ addslashes($r->nama_ruangan) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm btn-icon">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;">
            <div class="empty-state">
                <i class="fas fa-door-open"></i>
                <p>Belum ada data ruangan. <a href="{{ route('ruangan.create') }}" style="color:var(--primary)">Tambah ruangan →</a></p>
            </div>
        </div>
        @endforelse
    </div>

    @if($ruangan->hasPages())
    <div class="pagination-wrapper">
        {{ $ruangan->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
