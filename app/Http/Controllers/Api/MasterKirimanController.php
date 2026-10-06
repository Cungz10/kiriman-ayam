<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterKiriman;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterKirimanController extends Controller
{
    public function index()
    {
        return MasterKiriman::orderBy('nama_kiriman')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kiriman' => ['required', 'string', 'max:100', 'unique:master_kiriman,nama_kiriman'],
        ]);

        $kiriman = MasterKiriman::create($data);

        return response()->json($kiriman, 201);
    }

    public function update(Request $request, MasterKiriman $master_kiriman)
    {
        $data = $request->validate([
            'nama_kiriman' => [
                'required', 'string', 'max:100',
                Rule::unique('master_kiriman', 'nama_kiriman')->ignore($master_kiriman->id),
            ],
        ]);

        $master_kiriman->update($data);

        return response()->json($master_kiriman);
    }

    public function destroy(MasterKiriman $master_kiriman)
    {
        $master_kiriman->delete();

        return response()->json(['message' => 'Kiriman dihapus']);
    }
}
