<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Daftar Barang Ruangan – {{ $ruangan->nama_ruangan }}</title>
    <style>
        /*
         * Strategi layout multi-halaman DomPDF:
         *  - position:fixed  → muncul di SETIAP halaman (dipakai untuk header)
         *  - Normal flow     → ikut paginasi (tabel + TTD muncul satu kali / berurutan)
         *
         * Ukuran kertas A4: 21 × 29.7 cm
         * Padding body (margin konten dari tepi fisik kertas):
         *   kiri 2.5cm | kanan 2cm | bawah 2cm
         *   ATAS: disesuaikan agar tabel tidak tertimpa fixed header
         */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            margin: 0;
            /*
             * padding-top harus >= tinggi fixed header + top-offset header
             * Header dimulai 1.5cm dari atas fisik kertas.
             * Tinggi header (KOP 3 baris + judul + meta 4 baris) ≈ 5.2cm
             * Jadi konten dimulai ≈ 1.5 + 5.2 = 6.7cm → bulatkan 7cm
             */
            padding-top:    7cm;
            padding-right:  2cm;
            padding-bottom: 2cm;
            padding-left:   2.5cm;
        }

        /* ══════════════════════════════════════════════════════════
           FIXED HEADER — muncul di SETIAP halaman
           Menggunakan tabel 2-kolom:
             kiri  = KOP + judul + meta
             kanan = Tgl Cetak / Halaman
        ══════════════════════════════════════════════════════════ */
        .page-header {
            position: fixed;
            top:   1.5cm;   /* jarak dari tepi atas fisik kertas */
            left:  2.5cm;   /* = body padding-left  */
            right: 2cm;     /* = body padding-right */
        }

        /* Baris atas: KOP kiri, Tgl Cetak kanan */
        .header-top-row {
            width: 100%;
            border: none;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .header-top-row td {
            border: none;
            vertical-align: top;
            padding: 0;
        }
        .kop-instansi {
            font-weight: bold;
            font-size: 11pt;
            line-height: 1.55;
        }
        .tgl-cetak-box {
            text-align: right;
            font-size: 9.5pt;
            color: #999;
            line-height: 1.35;
            white-space: nowrap;
        }

        /* Judul */
        .judul {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 28px 0 10px;   /* margin-top 14px agar tidak mepet ke KOP */
            letter-spacing: 1px;
        }

        /* Nomor halaman dinamis via CSS counter — didukung DomPDF */
        .page-num-current { font-size: 9.5pt; color: #999; }
        .page-num-current::after { content: counter(page); }

        /* Meta info */
        .meta-info table { border: none; border-collapse: collapse; }
        .meta-info td {
            border: none;
            padding: 1px 4px 1px 0;
            font-size: 10.5pt;
            vertical-align: top;
        }
        .meta-info .label-col  { width: 130px; font-weight: bold; }
        .meta-info .colon-col  { width: 12px; text-align: center; }
        .meta-info .value-col  { font-weight: bold; }

        /* ══════════════════════════════════════════════════════════
           TABEL BARANG — normal flow, ikut paginasi
        ══════════════════════════════════════════════════════════ */
        .tabel-outer-wrap {
            border: 2pt solid #000;
        }
        .tabel-barang {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
        }
        .tabel-barang thead th {
            border: 1.5pt solid #000;
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            padding: 5px 6px;
            vertical-align: middle;
        }
        .tabel-barang tbody td {
            border: 0.4pt solid #888;
            padding: 5px 6px;
            vertical-align: middle;
        }
        /* Lebar kolom */
        .col-no     { text-align: center; width: 28px; }
        .col-nama   { text-align: left;   width: 120px; }
        .col-jumlah { text-align: right;  width: 52px; }
        .col-satuan { text-align: center; width: 52px; }
        .col-ket    { text-align: left; }

        /* ══════════════════════════════════════════════════════════
           TANDA TANGAN — normal flow, muncul sekali di akhir
        ══════════════════════════════════════════════════════════ */
        .ttd-section {
            margin-top: 1.5cm;
            width: 100%;
        }
        .ttd-section table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }
        .ttd-section td {
            border: none;
            padding: 2px 4px;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            display: block;
            margin-top: 52px;
        }
        .ttd-nip {
            display: block;
            font-size: 10pt;
        }
    </style>
</head>
<body>

    {{-- ══════════════════════════════════════════════════════════
         FIXED HEADER — tampil di setiap halaman
    ══════════════════════════════════════════════════════════ --}}
    <div class="page-header">

        {{-- Baris atas: KOP kiri | Tgl Cetak kanan --}}
        <table class="header-top-row">
            <tr>
                <td>
                    <div class="kop-instansi">
                        KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN<br>
                        DITJEND PENDIDIKAN VOKASI<br>
                        JAWA TIMUR
                    </div>
                </td>
                <td style="width:160px;">
                    <div class="tgl-cetak-box">
                        Tgl Cetak &nbsp;: &nbsp;{{ $tglCetak }}<br>
                        Halaman &nbsp;&nbsp;&nbsp;: &nbsp;<span class="page-num-current"></span>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Judul --}}
        <div class="judul">REKAP DAFTAR BARANG RUANGAN</div>

        {{-- Meta Info --}}
        <div class="meta-info">
            <table>
                <tr>
                    <td class="label-col">NAMA UAKPB</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">KANTOR PUSAT</td>
                </tr>
                <tr>
                    <td class="label-col">KODE UAKPB</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $kodeUAKPB }}</td>
                </tr>
                <tr>
                    <td class="label-col">NAMA RUANGAN</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ strtoupper($ruangan->nama_ruangan) }}</td>
                </tr>
                <tr>
                    <td class="label-col">KODE RUANGAN</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $kodeRuangan }}</td>
                </tr>
            </table>
        </div>

    </div>{{-- /page-header --}}


    {{-- ══════════════════════════════════════════════════════════
         TABEL BARANG — normal flow
    ══════════════════════════════════════════════════════════ --}}
    <div class="tabel-outer-wrap">
        <table class="tabel-barang">
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-nama">Nama Barang</th>
                    <th class="col-jumlah">Jumlah<br>Barang</th>
                    <th class="col-satuan">Satuan</th>
                    <th class="col-ket">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangList as $i => $b)
                <tr>
                    <td class="col-no">{{ $i + 1 }}</td>
                    <td class="col-nama">{{ $b->nama_barang }}</td>
                    <td class="col-jumlah">{{ $b->jumlah }}</td>
                    <td class="col-satuan">Buah</td>
                    <td class="col-ket">{{ $b->keterangan ?? '' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; font-style:italic; padding:12px; border:0.4pt solid #888;">
                        Tidak ada barang di ruangan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    {{-- ══════════════════════════════════════════════════════════
         TANDA TANGAN — normal flow, hanya sekali di akhir dokumen
    ══════════════════════════════════════════════════════════ --}}
    <div class="ttd-section">
        <table>
            <tr>
                <td style="width:50%; text-align:center;">
                    Penanggung Jawab UAKPB,<br>
                    Direktur
                </td>
                <td style="width:50%; text-align:center;">
                    Malang,{{ $tglCetak }}<br>
                    Penanggung Jawab Ruangan,
                </td>
            </tr>
            <tr>
                <td style="text-align:center;">
                    <span class="ttd-nama">{{ $namaDirektur }}</span>
                    <span class="ttd-nip">{{ $nipDirektur }}</span>
                </td>
                <td style="text-align:center;">
                    <span class="ttd-nama">{{ $ruangan->pic_ruangan ?? '-' }}</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
