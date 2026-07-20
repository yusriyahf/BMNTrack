@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan inventaris aset BMN kampus')

@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 18px;
    margin-bottom: 28px;
}
.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 22px 24px;
    box-shadow: var(--shadow);
    display: flex; align-items: center; gap: 18px;
    position: relative; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.stat-card::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0;
    height: 3px;
}
.stat-card.c-blue::after   { background: linear-gradient(90deg, var(--primary-light), #60a5fa); }
.stat-card.c-green::after  { background: linear-gradient(90deg, var(--success), #34d399); }
.stat-card.c-red::after    { background: linear-gradient(90deg, var(--danger), #f87171); }
.stat-card.c-yellow::after { background: linear-gradient(90deg, var(--accent), #fbbf24); }
.stat-card.c-teal::after   { background: linear-gradient(90deg, #14b8a6, #5eead4); }
.stat-card.c-violet::after { background: linear-gradient(90deg, #8b5cf6, #c4b5fd); }

.stat-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; flex-shrink: 0;
}
.stat-card.c-blue   .stat-icon { background: var(--primary-xlight); color: var(--primary); }
.stat-card.c-green  .stat-icon { background: #d1fae5; color: #059669; }
.stat-card.c-red    .stat-icon { background: var(--danger-light); color: var(--danger); }
.stat-card.c-yellow .stat-icon { background: var(--warning-light); color: var(--accent-dark); }
.stat-card.c-teal   .stat-icon { background: #ccfbf1; color: #0f766e; }
.stat-card.c-violet .stat-icon { background: #ede9fe; color: #7c3aed; }

.stat-info label {
    display: block; font-size: 12px; font-weight: 600;
    color: var(--text-light); text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 4px;
}
.stat-info .stat-value {
    font-size: 28px; font-weight: 800; color: var(--text-dark); line-height: 1;
}
.stat-info .stat-sub {
    font-size: 12px; color: var(--text-light); margin-top: 4px;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 900px) { .content-grid { grid-template-columns: 1fr; } }

/* Gedung mini cards */
.gedung-list { display: flex; flex-direction: column; gap: 10px; padding: 20px; }
.gedung-mini {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    background: var(--primary-ultra);
    border: 1px solid var(--primary-xlight);
    border-radius: var(--radius-sm);
    transition: var(--transition);
}
.gedung-mini:hover { background: var(--primary-xlight); }
.gedung-mini-icon {
    width: 40px; height: 40px;
    background: var(--primary);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; flex-shrink: 0;
}
.gedung-mini-info { flex: 1; }
.gedung-mini-info strong { display: block; font-size: 13px; font-weight: 700; color: var(--text-dark); }
.gedung-mini-info span { font-size: 12px; color: var(--text-light); }
.gedung-mini-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 3px; }
.gedung-mini-badges small { font-size: 11px; color: var(--text-light); }

/* Recent table */
.recent-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid var(--border);
}
.recent-item:last-child { border-bottom: none; }
.recent-img {
    width: 44px; height: 44px; flex-shrink: 0;
    border-radius: 10px; overflow: hidden;
    border: 2px solid var(--border);
}
.recent-img img { width: 100%; height: 100%; object-fit: cover; }
.recent-img-placeholder {
    width: 44px; height: 44px;
    background: var(--primary-xlight);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: 17px;
    flex-shrink: 0;
}
.recent-info { flex: 1; min-width: 0; }
.recent-info strong { display: block; font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.recent-info span { font-size: 12px; color: var(--text-light); }
.recent-right { text-align: right; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <div class="breadcrumb">
        <i class="fas fa-house"></i>
        <span>Beranda</span>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card c-blue">
        <div class="stat-icon"><i class="fas fa-building"></i></div>
        <div class="stat-info">
            <label>Total Gedung</label>
            <div class="stat-value">{{ $stats['total_gedung'] }}</div>
            <div class="stat-sub">unit gedung terdaftar</div>
        </div>
    </div>
    <div class="stat-card c-teal">
        <div class="stat-icon"><i class="fas fa-door-open"></i></div>
        <div class="stat-info">
            <label>Total Ruangan</label>
            <div class="stat-value">{{ $stats['total_ruangan'] }}</div>
            <div class="stat-sub">ruangan terdata</div>
        </div>
    </div>
    <div class="stat-card c-violet">
        <div class="stat-icon"><i class="fas fa-box-open"></i></div>
        <div class="stat-info">
            <label>Total Aset (Unit)</label>
            <div class="stat-value">{{ number_format($stats['total_barang']) }}</div>
            <div class="stat-sub">unit barang terdaftar</div>
        </div>
    </div>
    <div class="stat-card c-red">
        <div class="stat-icon"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-info">
            <label>Kondisi Rusak</label>
            <div class="stat-value">{{ $stats['barang_rusak'] }}</div>
            <div class="stat-sub">jenis barang perlu penanganan</div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="content-grid">
    <!-- Gedung Summary -->
    <!-- <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-building text-primary"></i> Rekap per Gedung</h5>
            <a href="{{ route('gedung.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="gedung-list">
            @forelse($gedungStats as $g)
            <div class="gedung-mini">
                <div class="gedung-mini-icon"><i class="fas fa-building"></i></div>
                <div class="gedung-mini-info">
                    <strong>{{ $g->nama_gedung }}</strong>
                    <span>{{ $g->kode_gedung }}</span>
                </div>
                <div class="gedung-mini-badges">
                    <span class="badge badge-primary">{{ $g->ruangan_count }} Ruangan</span>
                    <small>{{ number_format($g->total_barang) }} unit</small>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-building"></i>
                <p>Belum ada data gedung</p>
            </div>
            @endforelse
        </div>
    </div> -->

    <!-- Recent Barang -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-clock text-primary"></i> Barang Terbaru</h5>
            <a href="{{ route('barang.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding-top:0">
            @forelse($recentBarang as $b)
            <div class="recent-item">
                @if($b->foto_barang)
                    <div class="recent-img"><img src="{{ asset('storage/' . $b->foto_barang) }}" alt="{{ $b->nama_barang }}"></div>
                @else
                    <div class="recent-img-placeholder"><i class="fas fa-box"></i></div>
                @endif
                <div class="recent-info">
                    <strong>{{ $b->nama_barang }}</strong>
                    <span>{{ $b->ruangan->nama_ruangan ?? '-' }} · {{ $b->ruangan->gedung->nama_gedung ?? '-' }}</span>
                </div>
                <div class="recent-right">
                    <span class="badge {{ $b->kondisi === 'Aman' ? 'badge-success' : 'badge-danger' }}">
                        {{ $b->kondisi }}
                    </span>
                    <div style="font-size:12px;color:var(--text-light);margin-top:4px;">{{ $b->jumlah }} unit</div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>Belum ada data barang</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Ruangan -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-door-open text-primary"></i> Ruangan Terbaru</h5>
            <a href="{{ route('ruangan.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding-top:0">
            @forelse($recentRuangan as $r)
            <div class="recent-item">
                @if($r->foto_ruangan)
                    <div class="recent-img"><img src="{{ asset('storage/' . $r->foto_ruangan) }}" alt="{{ $r->nama_ruangan }}"></div>
                @else
                    <div class="recent-img-placeholder"><i class="fas fa-door-open"></i></div>
                @endif
                <div class="recent-info">
                    <strong>{{ $r->nama_ruangan }}</strong>
                    <span>{{ $r->gedung->nama_gedung ?? '-' }} · Lantai {{ $r->lantai }}</span>
                </div>
                <div class="recent-right">
                    <span class="badge badge-primary">{{ $r->barang_count }} item</span>
                    <div style="font-size:12px;color:var(--text-light);margin-top:4px;">
                        <a href="{{ route('ruangan.show', $r) }}" style="color:var(--primary);text-decoration:none;">Lihat →</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-door-open"></i>
                <p>Belum ada data ruangan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
