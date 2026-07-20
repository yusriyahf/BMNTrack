@extends('layouts.app')

@section('title', 'Detail Ruangan – ' . $ruangan->nama_ruangan)
@section('page-title', 'Detail Ruangan')
@section('page-subtitle', 'Detail ruangan dan daftar aset di dalamnya')

@push('styles')
<style>
/* ──────────────────────────────────────────────
   RUANGAN DETAIL HEADER  –  White Card Theme
────────────────────────────────────────────── */
.ruangan-detail-header {
    background: #ffffff;
    border-radius: var(--radius);
    padding: 28px;
    color: var(--text);
    margin-bottom: 24px;
    display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap;
    box-shadow: 0 2px 16px rgba(0,0,0,.08), 0 0 0 1px rgba(0,0,0,.06);
    position: relative; overflow: hidden;
}
.ruangan-foto {
    width: 140px; height: 110px;
    border-radius: 12px; overflow: hidden;
    border: 2px solid var(--border);
    flex-shrink: 0;
    background: var(--bg-body);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: transform .2s, box-shadow .2s;
}
.ruangan-foto:hover { transform: scale(1.03); box-shadow: 0 4px 16px rgba(0,0,0,.15); }
.ruangan-foto img { width: 100%; height: 100%; object-fit: cover; }
.ruangan-foto-placeholder { font-size: 42px; color: var(--primary); opacity: .35; }

.ruangan-info { flex: 1; min-width: 0; }
.ruangan-info h2 {
    font-size: 22px; font-weight: 800;
    margin-bottom: 4px;
    color: var(--text);
}
.ruangan-date-sub {
    font-size: 12px;
    color: var(--text-light);
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 6px;
}
.ruangan-date-sub i { color: var(--primary); }

/* Info Grid */
.info-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 10px;
}
.info-item {
    background: var(--bg-body);
    border: 1px solid var(--border);
    border-radius: 8px; padding: 10px 12px;
}
.info-item label {
    display: block; font-size: 10px; color: var(--text-light);
    margin-bottom: 3px; text-transform: uppercase; letter-spacing: .6px;
    font-weight: 600;
}
.info-item label i { color: var(--primary); margin-right: 4px; }
.info-item span { font-size: 14px; font-weight: 700; color: var(--text); }

/* Action buttons */
.ruangan-actions {
    display: flex; flex-direction: column; gap: 8px; flex-shrink: 0;
}
.ruangan-actions .btn { justify-content: center; }

/* ──────────────────────────────────────────────
   MOBILE COLLAPSIBLE DETAIL
────────────────────────────────────────────── */
@media (max-width: 640px) {
    .ruangan-detail-header { flex-direction: column; gap: 16px; padding: 20px; }

    /* Hide full info-grid on mobile by default */
    .mobile-collapse-content {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height .35s ease, opacity .25s ease, margin .25s ease;
        margin-top: 0;
    }
    .mobile-collapse-content.open {
        max-height: 600px;
        opacity: 1;
        margin-top: 12px;
    }

    /* Mobile summary bar */
    .mobile-detail-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--primary-ultra, #eef2ff);
        border: 1px solid var(--primary-xlight, #c7d2fe);
        border-radius: 8px;
        padding: 10px 14px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
        user-select: none;
        transition: background .2s;
    }
    .mobile-detail-toggle:hover { background: var(--primary-xlight, #c7d2fe); }
    .mobile-detail-toggle .toggle-icon {
        transition: transform .3s;
    }
    .mobile-detail-toggle.open .toggle-icon { transform: rotate(180deg); }

    .ruangan-actions { flex-direction: row; }
    .barang-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
}

/* Desktop: collapse elements hidden */
@media (min-width: 641px) {
    .mobile-detail-toggle { display: none; }
    .mobile-collapse-content {
        /* always visible on desktop */
        max-height: none !important;
        opacity: 1 !important;
        margin-top: 0 !important;
        overflow: visible !important;
    }
}

@media (max-width: 380px) {
    .barang-grid { grid-template-columns: 1fr; }
}

/* ──────────────────────────────────────────────
   BARANG GRID
────────────────────────────────────────────── */
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
    cursor: pointer;
}
.barang-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.barang-card-img {
    width: 100%; height: 140px; overflow: hidden;
    position: relative;
}
.barang-card-img img {
    width: 100%; height: 100%; object-fit: cover; display: block;
    transition: transform .3s;
}
.barang-card:hover .barang-card-img img { transform: scale(1.05); }
.barang-card-img-placeholder {
    width: 100%; height: 140px;
    background: linear-gradient(135deg, var(--primary-ultra, #eef2ff), var(--primary-xlight, #c7d2fe));
    display: flex; align-items: center; justify-content: center;
    font-size: 38px; color: var(--primary); opacity: .5;
}
/* clickable image icon */
.img-zoom-icon {
    position: absolute; bottom: 8px; right: 8px;
    width: 28px; height: 28px;
    background: rgba(0,0,0,.45); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 11px;
    opacity: 0; transition: opacity .2s;
    cursor: pointer;
}
.barang-card:hover .img-zoom-icon { opacity: 1; }
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

/* Number badge on card */
.card-number-badge {
    position: absolute; top: 8px; left: 8px;
    width: 26px; height: 26px;
    background: rgba(0,0,0,.55);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 11px; font-weight: 700;
    z-index: 2;
    line-height: 1;
}

/* Search bar */
.barang-search-wrap {
    padding: 16px 24px 0;
    display: flex; align-items: center; gap: 10px;
}
.barang-search-input {
    flex: 1;
    height: 40px;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0 14px 0 38px;
    font-size: 13px;
    background: var(--bg-body);
    color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.barang-search-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 99,102,241),.12);
}
.barang-search-icon {
    position: relative; margin-right: -34px; z-index: 1;
    color: var(--text-light); font-size: 13px; padding-left: 12px;
    pointer-events: none;
}
.barang-search-count {
    font-size: 12px; color: var(--text-light); white-space: nowrap;
}
.barang-empty-search {
    padding: 40px 24px; text-align: center;
    color: var(--text-light); font-size: 14px;
    display: none;
}
.barang-empty-search i { font-size: 32px; display: block; margin-bottom: 10px; opacity: .4; }

/* Sort select */
.sort-select {
    height: 40px;
    padding: 0 32px 0 10px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--bg-card);
    color: var(--text);
    font-size: 12px; font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: border-color .2s, box-shadow .2s;
    flex-shrink: 0;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236366f1' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    outline: none;
}
.sort-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }

/* Lightbox caption */
.bmnmodal-img-caption {
    text-align: center;
    color: #fff;
    font-size: 14px; font-weight: 600;
    margin-top: 10px;
    text-shadow: 0 1px 4px rgba(0,0,0,.6);
    letter-spacing: .2px;
}

/* ──────────────────────────────────────────────
   MODAL / POPUP
────────────────────────────────────────────── */
.bmnmodal-backdrop {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(4px);
    z-index: 9998;
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    opacity: 0; pointer-events: none;
    transition: opacity .25s;
}
.bmnmodal-backdrop.show { opacity: 1; pointer-events: all; }

/* Image lightbox */
.bmnmodal-img-wrap {
    max-width: 92vw; max-height: 88vh;
    border-radius: 14px; overflow: hidden;
    box-shadow: 0 24px 60px rgba(0,0,0,.6);
    transform: scale(.9);
    transition: transform .25s;
}
.bmnmodal-backdrop.show .bmnmodal-img-wrap { transform: scale(1); }
.bmnmodal-img-wrap img { display: block; max-width: 100%; max-height: 85vh; object-fit: contain; }

/* Detail popup */
.bmnmodal-detail {
    background: var(--bg-card, #fff);
    border-radius: 16px;
    width: 100%; max-width: 480px;
    box-shadow: 0 24px 60px rgba(0,0,0,.4);
    overflow: hidden;
    transform: translateY(30px) scale(.97);
    transition: transform .28s;
}
.bmnmodal-backdrop.show .bmnmodal-detail { transform: translateY(0) scale(1); }
.bmnmodal-detail-img {
    width: 100%; height: 200px; overflow: hidden; cursor: zoom-in;
    background: var(--bg-body);
    display: flex; align-items: center; justify-content: center;
}
.bmnmodal-detail-img img { width: 100%; height: 100%; object-fit: cover; }
.bmnmodal-detail-img-placeholder { font-size: 56px; color: var(--primary); opacity: .3; }
.bmnmodal-detail-body { padding: 20px; }
.bmnmodal-detail-title { font-size: 18px; font-weight: 800; margin-bottom: 6px; }
.bmnmodal-detail-rows { margin-top: 14px; display: flex; flex-direction: column; gap: 8px; }
.bmnmodal-detail-row {
    display: flex; gap: 10px;
    font-size: 13px; padding: 8px 0; border-bottom: 1px solid var(--border);
}
.bmnmodal-detail-row:last-child { border-bottom: none; }
.bmnmodal-detail-row .dr-label {
    width: 110px; flex-shrink: 0;
    color: var(--text-light); font-weight: 600; font-size: 11px;
    text-transform: uppercase; letter-spacing: .4px; padding-top: 1px;
}
.bmnmodal-detail-row .dr-val { font-weight: 600; color: var(--text); flex: 1; }
.bmnmodal-close {
    position: absolute; top: 14px; right: 14px;
    width: 34px; height: 34px;
    background: rgba(0,0,0,.35); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; cursor: pointer;
    z-index: 10; transition: background .2s;
}
.bmnmodal-close:hover { background: rgba(0,0,0,.6); }
.bmnmodal-detail-header-area { position: relative; }

/* PIC clickable info-item */
.info-item-pic {
    cursor: pointer;
    transition: background .2s, border-color .2s;
}
.info-item-pic:hover {
    background: var(--primary-ultra);
    border-color: var(--primary-xlight);
}
.info-item-pic .pic-avatar {
    display: flex; align-items: center; gap: 8px;
    margin-top: 2px;
}
.info-item-pic .pic-thumb {
    width: 28px; height: 28px;
    border-radius: 50%; object-fit: cover;
    border: 2px solid var(--primary-xlight);
    flex-shrink: 0;
}
.info-item-pic .pic-thumb-placeholder {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--primary-ultra);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; color: var(--primary);
    border: 2px solid var(--primary-xlight);
    flex-shrink: 0;
}
/* PIC photo modal */
.bmnmodal-pic-card {
    background: var(--bg-card, #fff);
    border-radius: 16px;
    overflow: hidden;
    width: 260px;
    box-shadow: 0 24px 60px rgba(0,0,0,.4);
    transform: scale(.92); transition: transform .28s;
}
.bmnmodal-backdrop.show .bmnmodal-pic-card { transform: scale(1); }
.bmnmodal-pic-card img {
    width: 100%; aspect-ratio: 1;
    object-fit: cover; display: block;
}
.bmnmodal-pic-card-info {
    padding: 14px 16px;
    text-align: center;
}
.bmnmodal-pic-card-name {
    font-size: 15px; font-weight: 700;
}
.bmnmodal-pic-card-label {
    font-size: 11px; color: var(--text-light); margin-top: 2px;
    text-transform: uppercase; letter-spacing: .5px;
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
    <div class="ruangan-foto" onclick="openImgModal('{{ $ruangan->foto_ruangan ? asset('storage/'.$ruangan->foto_ruangan) : '' }}', '{{ $ruangan->nama_ruangan }}')">
        @if($ruangan->foto_ruangan)
            <img src="{{ asset('storage/' . $ruangan->foto_ruangan) }}"
                 alt="{{ $ruangan->nama_ruangan }}"
                 loading="lazy" decoding="async">
        @else
            <i class="fas fa-door-open ruangan-foto-placeholder"></i>
        @endif
    </div>

    <div class="ruangan-info">
        <h2>{{ $ruangan->nama_ruangan }}</h2>
        <div class="ruangan-date-sub">
            <i class="fas fa-calendar-alt"></i>
            Tanggal Pendataan:
            <strong>{{ optional($ruangan->tanggal_pendataan)->locale('id')->translatedFormat('d F Y') ?? '-' }}</strong>
        </div>

        {{-- Mobile: toggle button --}}
        <button class="mobile-detail-toggle" id="detailToggle" onclick="toggleMobileDetail()" aria-expanded="false">
            <span><i class="fas fa-info-circle" style="margin-right:6px;"></i> Detail Ruangan</span>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </button>

        {{-- Info grid: hidden on mobile until toggled --}}
        <div class="mobile-collapse-content" id="collapseDetail">
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
                <div class="info-item info-item-pic"
                     onclick="openPicModal('{{ $ruangan->foto_pic ? asset('storage/'.$ruangan->foto_pic) : '' }}', '{{ addslashes($ruangan->pic_ruangan ?? '-') }}')"
                     title="{{ $ruangan->foto_pic ? 'Klik untuk lihat foto PIC' : 'Belum ada foto PIC' }}">
                    <label><i class="fas fa-user-tie"></i> PIC</label>
                    <div class="pic-avatar">
                        @if($ruangan->foto_pic)
                            <img src="{{ asset('storage/'.$ruangan->foto_pic) }}"
                                 class="pic-thumb" alt="PIC"
                                 loading="lazy" decoding="async">
                        @else
                            <span class="pic-thumb-placeholder"><i class="fas fa-user"></i></span>
                        @endif
                        <span>{{ $ruangan->pic_ruangan ?? '-' }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-box"></i> Total Barang</label>
                    <span>{{ $ruangan->barang->count() }} jenis ({{ $ruangan->barang->sum('jumlah') }} unit)</span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-user-plus"></i> Dibuat Oleh</label>
                    <span>{{ $ruangan->createdBy->nama ?? '-' }}
                        @if($ruangan->created_at)<small style="display:block;color:var(--text-light);font-size:11px;">{{ $ruangan->created_at->locale('id')->translatedFormat('d F Y, H:i') }}</small>@endif
                    </span>
                </div>
                @if($ruangan->updated_by)
                <div class="info-item">
                    <label><i class="fas fa-user-pen"></i> Diedit Oleh</label>
                    <span>{{ $ruangan->updatedBy->nama ?? '-' }}
                        @if($ruangan->updated_at)<small style="display:block;color:var(--text-light);font-size:11px;">{{ $ruangan->updated_at->locale('id')->translatedFormat('d F Y, H:i') }}</small>@endif
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="ruangan-actions">
        <a href="{{ route('ruangan.barang.create', $ruangan) }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
        <a href="{{ route('ruangan.edit', $ruangan) }}" class="btn btn-outline" style="border-color:var(--border);color:var(--text);">
            <i class="fas fa-pen"></i> Edit Ruangan
        </a>
        <a href="{{ route('ruangan.cetak-pdf', $ruangan) }}" class="btn btn-outline" target="_blank"
           style="border-color:#e56900;color:#e56900;"
           title="Cetak Rekap Daftar Barang Ruangan sebagai PDF">
            <i class="fas fa-file-pdf"></i> Cetak Rekap PDF
        </a>
    </div>
</div>

<!-- Barang List -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-boxes-stacked text-primary"></i> Daftar Inventaris Barang</h5>
        <div class="d-flex gap-2 align-items-center">
            @php
                $totalBaik  = $ruangan->barang->where('kondisi', 'Baik')->count();
                $totalRusak = $ruangan->barang->where('kondisi', 'Rusak berat')->count();
            @endphp
            <span class="badge badge-success"><i class="fas fa-check-circle"></i> {{ $totalBaik }} Baik</span>
            <span class="badge badge-danger"><i class="fas fa-triangle-exclamation"></i> {{ $totalRusak }} Rusak berat</span>
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
    {{-- Search bar + Sort --}}
    <div class="barang-search-wrap">
        <i class="fas fa-search barang-search-icon"></i>
        <input type="text" id="barangSearch" class="barang-search-input"
               placeholder="Cari nama barang, kategori, kondisi…"
               oninput="filterBarang(this.value)" autocomplete="off">
        <span class="barang-search-count" id="barangSearchCount">
            {{ $ruangan->barang->count() }} barang
        </span>
        <select class="sort-select" id="sortSelect" onchange="sortBarang(this.value)" title="Urutkan">
            <option value="newest">↓ Terbaru</option>
            <option value="oldest">↑ Terlama</option>
            <option value="unit_desc">↓ Unit Terbanyak</option>
            <option value="unit_asc">↑ Unit Tersedikit</option>
        </select>
    </div>
    <div class="barang-empty-search" id="barangEmptySearch">
        <i class="fas fa-search"></i>
        Tidak ada barang yang cocok.
    </div>
    @php $barangSorted = $ruangan->barang->sortByDesc('id'); $barangTotal = $barangSorted->count(); @endphp
    <div class="barang-grid" id="barangGrid">
        @foreach($barangSorted as $b)
        @php $chronoNo = $barangTotal - $loop->index; @endphp
        <div class="barang-card"
             data-order="{{ $chronoNo }}"
             data-jumlah="{{ $b->jumlah }}"
             data-search="{{ strtolower($b->nama_barang . ' ' . ($b->kategori ?? '') . ' ' . ($b->kondisi ?? '') . ' ' . ($b->keterangan ?? ''))}}"
             onclick="openBarangDetail(
                '{{ addslashes($b->nama_barang) }}',
                '{{ $b->foto_barang ? asset('storage/'.$b->foto_barang) : '' }}',
                '{{ addslashes($b->kategori ?? '-') }}',
                '{{ addslashes($b->kondisi ?? '-') }}',
                {{ $b->jumlah }},
                '{{ addslashes($b->keterangan ?? '') }}',
                '{{ addslashes($b->kode_barang ?? '-') }}',
                {{ $chronoNo }},
                '{{ addslashes($b->createdBy->nama ?? '-') }}',
                '{{ $b->created_at ? $b->created_at->locale('id')->translatedFormat('d F Y, H:i') : '-' }}',
                '{{ addslashes($b->updatedBy->nama ?? '') }}',
                '{{ $b->updated_at && $b->updated_by ? $b->updated_at->locale('id')->translatedFormat('d F Y, H:i') : '' }}'
             )">
            <div class="barang-card-img">
                <span class="card-number-badge">{{ $chronoNo }}</span>
                @if($b->foto_barang)
                    <img src="{{ asset('storage/' . $b->foto_barang) }}"
                         alt="{{ $b->nama_barang }}"
                         loading="lazy" decoding="async">
                    <span class="img-zoom-icon" onclick="event.stopPropagation(); openImgModal('{{ asset('storage/'.$b->foto_barang) }}', '{{ addslashes($b->nama_barang) }}')">
                        <i class="fas fa-search-plus"></i>
                    </span>
                @else
                    <div class="barang-card-img-placeholder"><i class="fas fa-box"></i></div>
                @endif
                <div class="kondisi-badge-overlay">
                    <span class="badge {{ $b->kondisi === 'Baik' ? 'badge-success' : 'badge-danger' }}">
                        {{ $b->kondisi === 'Baik' ? '✓' : '!' }} {{ $b->kondisi }}
                    </span>
                </div>
            </div>
            <div class="barang-card-body">
                <div class="barang-card-title">{{ $b->nama_barang }}</div>
                <!-- <div class="barang-card-meta">
                    @if($b->kategori)
                    <span class="badge badge-primary"><i class="fas fa-tag"></i> {{ $b->kategori }}</span>
                    @endif
                </div> -->
                @if($b->keterangan)
                <div class="barang-card-desc">{{ Str::limit($b->keterangan, 70) }}</div>
                @endif
            </div>
            <div class="barang-card-footer" onclick="event.stopPropagation()">
                <div class="jumlah-badge">
                    <i class="fas fa-cubes"></i>
                    {{ number_format($b->jumlah) }} Unit
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('barang.edit', $b) }}" class="btn btn-warning btn-sm btn-icon" title="Edit" onclick="event.stopPropagation()">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('barang.destroy', $b) }}"
                          onsubmit="return confirm('Hapus barang {{ addslashes($b->nama_barang) }}?')" onclick="event.stopPropagation()">
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

<!-- ════════════════════════════════
     MODAL: Image Lightbox
════════════════════════════════ -->
<div class="bmnmodal-backdrop" id="imgModal" onclick="closeImgModal()">
    <span class="bmnmodal-close" onclick="closeImgModal()"><i class="fas fa-times"></i></span>
    <div style="display:flex;flex-direction:column;align-items:center;" onclick="event.stopPropagation()">
        <div class="bmnmodal-img-wrap">
            <img id="imgModalSrc" src="" alt="">
        </div>
        <div class="bmnmodal-img-caption" id="imgModalCaption"></div>
    </div>
</div>

<!-- ════════════════════════════════
     MODAL: Foto PIC
════════════════════════════════ -->
<div class="bmnmodal-backdrop" id="picModal" onclick="closePicModal()">
    <span class="bmnmodal-close" onclick="closePicModal()"><i class="fas fa-times"></i></span>
    <div class="bmnmodal-pic-card" onclick="event.stopPropagation()">
        <img id="picModalImg" src="" alt="">
        <div class="bmnmodal-pic-card-info">
            <div class="bmnmodal-pic-card-name" id="picModalName"></div>
            <div class="bmnmodal-pic-card-label"><i class="fas fa-user-tie"></i> Penanggung Jawab</div>
        </div>
    </div>
</div>

<!-- ════════════════════════════════
     MODAL: Barang Detail
════════════════════════════════ -->
<div class="bmnmodal-backdrop" id="barangModal" onclick="closeBarangModal()">
    <div class="bmnmodal-detail" onclick="event.stopPropagation()">
        <div class="bmnmodal-detail-header-area">
            <div class="bmnmodal-detail-img" id="barangModalImgWrap">
                <img id="barangModalImg" src="" alt="" style="display:none;">
                <div id="barangModalImgPlaceholder" class="bmnmodal-detail-img-placeholder">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <span class="bmnmodal-close" onclick="closeBarangModal()"><i class="fas fa-times"></i></span>
        </div>
        <div class="bmnmodal-detail-body">
            <div class="bmnmodal-detail-title" id="barangModalName"></div>
            <div class="bmnmodal-detail-rows">
                <div class="bmnmodal-detail-row" id="barangModalKetRow">
                    <span class="dr-label"><i class="fas fa-info-circle"></i> Keterangan</span>
                    <span class="dr-val" id="barangModalKet"></span>
                </div>
                <div class="bmnmodal-detail-row">
                    <span class="dr-label"><i class="fas fa-barcode"></i> Kode</span>
                    <span class="dr-val" id="barangModalKode"></span>
                </div>
                <div class="bmnmodal-detail-row">
                    <span class="dr-label"><i class="fas fa-tag"></i> Kategori</span>
                    <span class="dr-val" id="barangModalKategori"></span>
                </div>
                <div class="bmnmodal-detail-row">
                    <span class="dr-label"><i class="fas fa-shield-check"></i> Kondisi</span>
                    <span class="dr-val" id="barangModalKondisi"></span>
                </div>
                <div class="bmnmodal-detail-row">
                    <span class="dr-label"><i class="fas fa-cubes"></i> Jumlah</span>
                    <span class="dr-val" id="barangModalJumlah"></span>
                </div>
                <div class="bmnmodal-detail-row" id="barangModalAuditCreate">
                    <span class="dr-label"><i class="fas fa-user-plus"></i> Ditambahkan</span>
                    <span class="dr-val" id="barangModalCreated"></span>
                </div>
                <div class="bmnmodal-detail-row" id="barangModalAuditUpdate" style="display:none">
                    <span class="dr-label"><i class="fas fa-user-pen"></i> Diedit</span>
                    <span class="dr-val" id="barangModalUpdated"></span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ─── Mobile collapse toggle ───────────────────
function toggleMobileDetail() {
    const content = document.getElementById('collapseDetail');
    const btn     = document.getElementById('detailToggle');
    const isOpen  = content.classList.contains('open');
    content.classList.toggle('open', !isOpen);
    btn.classList.toggle('open', !isOpen);
    btn.setAttribute('aria-expanded', String(!isOpen));
}

// ─── Image lightbox ───────────────────────────
function openImgModal(src, caption) {
    if (!src) return;
    document.getElementById('imgModalSrc').src = src;
    document.getElementById('imgModalSrc').alt = caption || '';
    document.getElementById('imgModalCaption').textContent = caption || '';
    document.getElementById('imgModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeImgModal() {
    document.getElementById('imgModal').classList.remove('show');
    document.body.style.overflow = '';
}

// ─── Barang detail popup ──────────────────────
function openBarangDetail(name, imgSrc, kategori, kondisi, jumlah, keterangan, kode, nomor,
                          createdByName, createdAt, updatedByName, updatedAt) {
    document.getElementById('barangModalName').textContent     = name;
    document.getElementById('barangModalKode').textContent     = kode || '-';
    document.getElementById('barangModalKategori').textContent = kategori || '-';
    document.getElementById('barangModalJumlah').textContent   = jumlah + ' Unit';

    // Audit: dibuat oleh
    const createdText = (createdByName && createdByName !== '-')
        ? createdByName + (createdAt ? ' · ' + createdAt : '')
        : '-';
    document.getElementById('barangModalCreated').textContent = createdText;

    // Audit: diedit oleh
    const updateRow = document.getElementById('barangModalAuditUpdate');
    if (updatedByName && updatedByName.trim()) {
        document.getElementById('barangModalUpdated').textContent =
            updatedByName + (updatedAt ? ' · ' + updatedAt : '');
        updateRow.style.display = 'flex';
    } else {
        updateRow.style.display = 'none';
    }

    // kondisi badge color
    const kondisiEl = document.getElementById('barangModalKondisi');
    kondisiEl.textContent = kondisi;
    kondisiEl.style.color = kondisi === 'Baik' ? '#16a34a' : '#dc2626';

    // keterangan
    const ketRow = document.getElementById('barangModalKetRow');
    const ketEl  = document.getElementById('barangModalKet');
    if (keterangan && keterangan.trim()) {
        ketEl.textContent = keterangan;
        ketRow.style.display = 'flex';
    } else {
        ketRow.style.display = 'none';
    }

    // image — klik gambar: tutup detail dulu, lalu buka lightbox
    const img   = document.getElementById('barangModalImg');
    const ph    = document.getElementById('barangModalImgPlaceholder');
    const wrap  = document.getElementById('barangModalImgWrap');
    if (imgSrc) {
        img.src = imgSrc;
        img.style.display = 'block';
        ph.style.display  = 'none';
        wrap.onclick = () => {
            closeBarangModal();
            setTimeout(() => openImgModal(imgSrc, name), 220);
        };
        wrap.style.cursor = 'zoom-in';
    } else {
        img.style.display = 'none';
        ph.style.display  = 'flex';
        wrap.onclick = null;
        wrap.style.cursor = 'default';
    }

    document.getElementById('barangModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeBarangModal() {
    document.getElementById('barangModal').classList.remove('show');
    document.body.style.overflow = '';
}

// ─── Live search filter ───────────────────────
function filterBarang(q) {
    q = q.toLowerCase().trim();
    const cards   = document.querySelectorAll('#barangGrid .barang-card');
    const emptyEl = document.getElementById('barangEmptySearch');
    const countEl = document.getElementById('barangSearchCount');
    let visible   = 0;
    cards.forEach(card => {
        const haystack = card.dataset.search || '';
        const show = !q || haystack.includes(q);
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    countEl.textContent = visible + ' barang' + (q ? ' ditemukan' : '');
    emptyEl.style.display = (visible === 0 && q) ? 'block' : 'none';
}

// ─── PIC photo modal ──────────────────────────
function openPicModal(src, name) {
    if (!src) return; // no foto, do nothing
    document.getElementById('picModalImg').src = src;
    document.getElementById('picModalImg').alt = name || '';
    document.getElementById('picModalName').textContent = name || '';
    document.getElementById('picModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closePicModal() {
    document.getElementById('picModal').classList.remove('show');
    document.body.style.overflow = '';
}

// ─── Sort ─────────────────────────────────────
function sortBarang(mode) {
    const grid  = document.getElementById('barangGrid');
    const cards = Array.from(grid.querySelectorAll('.barang-card'));
    cards.sort((a, b) => {
        const aOrder  = parseInt(a.dataset.order)  || 0;
        const bOrder  = parseInt(b.dataset.order)  || 0;
        const aJumlah = parseInt(a.dataset.jumlah) || 0;
        const bJumlah = parseInt(b.dataset.jumlah) || 0;
        if (mode === 'newest')    return bOrder  - aOrder;   // terbaru = id besar dulu
        if (mode === 'oldest')    return aOrder  - bOrder;   // terlama = id kecil dulu
        if (mode === 'unit_desc') return bJumlah - aJumlah;  // terbanyak
        if (mode === 'unit_asc')  return aJumlah - bJumlah;  // tersedikit
        return 0;
    });
    cards.forEach(c => grid.appendChild(c));
    }

// close modals on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeImgModal(); closeBarangModal(); closePicModal(); }
});
</script>
@endpush
@endsection
