@extends('layouts.app')

@section('title', 'Tambah Gedung')
@section('page-title', 'Tambah Gedung')

@section('content')
<div class="page-header">
    <h1>Tambah Gedung</h1>
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
        <span>/</span> <a href="{{ route('gedung.index') }}">Gedung</a>
        <span>/</span> <span>Tambah</span>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <h5><i class="fas fa-plus-circle text-primary"></i> Form Tambah Gedung</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('gedung.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="kode_gedung">
                    Kode Gedung <span class="required">*</span>
                </label>
                <input type="text" id="kode_gedung" name="kode_gedung"
                    class="form-control {{ $errors->has('kode_gedung') ? 'is-invalid' : '' }}"
                    value="{{ old('kode_gedung') }}"
                    placeholder="Contoh: GDA, GDB, dll."
                    style="text-transform:uppercase"
                    maxlength="20">
                @error('kode_gedung')
                <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="nama_gedung">
                    Nama Gedung <span class="required">*</span>
                </label>
                <input type="text" id="nama_gedung" name="nama_gedung"
                    class="form-control {{ $errors->has('nama_gedung') ? 'is-invalid' : '' }}"
                    value="{{ old('nama_gedung') }}"
                    placeholder="Contoh: Gedung A - Rektorat"
                    maxlength="100">
                @error('nama_gedung')
                <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Gedung
                </button>
                <a href="{{ route('gedung.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
