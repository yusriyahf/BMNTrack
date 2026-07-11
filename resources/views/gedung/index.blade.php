@extends('layouts.app')

@section('title', 'Data Gedung')
@section('page-title', 'Data Gedung')
@section('page-subtitle', 'Daftar gedung yang terdaftar dalam sistem')

@section('content')
<div class="page-header d-flex justify-between align-items-center flex-wrap gap-2">
    <div>
        <h1>Data Gedung</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
            <span>/</span> <span>Gedung</span>
        </div>
    </div>
    <a href="{{ route('gedung.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Gedung
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-building text-primary"></i> Daftar Gedung</h5>
        <span class="badge badge-primary">{{ $gedung->total() }} Gedung</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Gedung</th>
                    <th>Nama Gedung</th>
                    <th>Jumlah Ruangan</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gedung as $i => $g)
                <tr>
                    <td>{{ $gedung->firstItem() + $i }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $g->kode_gedung }}</span>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $g->nama_gedung }}</div>
                    </td>
                    <td>
                        <span class="badge badge-gray">
                            <i class="fas fa-door-open"></i>
                            {{ $g->ruangan_count }} Ruangan
                        </span>
                    </td>
                    <td style="color:var(--text-light);font-size:12.5px;">
                        {{ $g->created_at->format('d/m/Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('gedung.edit', $g) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('gedung.destroy', $g) }}"
                                  onsubmit="return confirm('Hapus gedung {{ addslashes($g->nama_gedung) }}? Semua ruangan dan barang di dalamnya juga akan dihapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-building"></i>
                            <p>Belum ada data gedung. <a href="{{ route('gedung.create') }}" style="color:var(--primary)">Tambah sekarang →</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($gedung->hasPages())
    <div class="pagination-wrapper">
        {{ $gedung->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
