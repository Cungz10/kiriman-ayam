<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatInput;
use Illuminate\Http\Request;

class RiwayatInputController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatInput::query();

        if ($request->filled('nama_kiriman')) {
            $query->where('nama_kiriman', $request->string('nama_kiriman'));
        }

        if ($request->filled('nomer_po')) {
            $query->where('nomer_po', 'like', '%' . $request->string('nomer_po') . '%');
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->date('tanggal_dari'));
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->date('tanggal_sampai'));
        }

        return $query->orderByDesc('created_at')->paginate(15);
    }

    public function show(RiwayatInput $riwayat_input)
    {
        return $riwayat_input;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kiriman' => ['required', 'string', 'max:100'],
            'nomer_po' => ['required', 'string', 'max:50'],
            'nilai' => ['required', 'array', 'min:1'],
            'nilai.*' => ['numeric', 'min:0'],
        ]);

        $nilai = array_map(fn ($v) => round((float) $v, 2), $validated['nilai']);

        $riwayat = RiwayatInput::create([
            'nama_kiriman' => $validated['nama_kiriman'],
            'nomer_po' => $validated['nomer_po'],
            'data_input' => $nilai,
            'total_data' => count($nilai),
            'rata_rata' => round(array_sum($nilai), 2),
            'nilai_max' => max($nilai),
            'nilai_min' => min($nilai),
        ]);

        return response()->json($riwayat, 201);
    }

    public function destroy(RiwayatInput $riwayat_input)
    {
        $riwayat_input->delete();

        return response()->json(['message' => 'Riwayat dihapus']);
    }
}
