@extends('layouts.app')

@section('title', 'Semua Barang')
@section('page-title', 'Data Barang')
@section('page-subtitle', 'Seluruh inventaris barang dari semua ruangan')

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
        <select name="kondisi" class="form-control" style="max-width:180px;">
            <option value="">Semua Kondisi</option>
            <option value="Aman"  {{ request('kondisi') === 'Aman'  ? 'selected' : '' }}>Kondisi Aman</option>
            <option value="Rusak" {{ request('kondisi') === 'Rusak' ? 'selected' : '' }}>Kondisi Rusak</option>
        </select>
        <input type="text" name="search" class="form-control" style="max-width:240px;"
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
                    <th>Gedung</th>
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
                            <img src="{{ asset('storage/' . $b->foto_barang) }}" class="img-thumbnail" alt="{{ $b->nama_barang }}">
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
                    </td>
                    <td style="font-size:12.5px;color:var(--text-mid);">
                        {{ $b->ruangan->gedung->nama_gedung ?? '-' }}
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
@endsection
