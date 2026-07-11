<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_gedung'  => Gedung::count(),
            'total_ruangan' => Ruangan::count(),
            'total_barang'  => Barang::sum('jumlah'),
            'barang_rusak'  => Barang::where('kondisi', 'Rusak')->count(),
            'barang_aman'   => Barang::where('kondisi', 'Aman')->count(),
            'total_users'   => User::count(),
        ];

        $recentRuangan = Ruangan::with(['gedung', 'barang'])
            ->latest()
            ->take(5)
            ->get();

        $recentBarang = Barang::with('ruangan.gedung')
            ->latest()
            ->take(5)
            ->get();

        $gedungStats = Gedung::withCount('ruangan')
            ->with(['ruangan.barang'])
            ->get()
            ->map(function ($gedung) {
                $gedung->total_barang = $gedung->ruangan->sum(function ($r) {
                    return $r->barang->sum('jumlah');
                });
                return $gedung;
            });

        return view('dashboard', compact('stats', 'recentRuangan', 'recentBarang', 'gedungStats'));
    }
}
