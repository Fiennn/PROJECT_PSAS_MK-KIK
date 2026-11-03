<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function index(): View
    {
        $fasilitas = Fasilitas::query()
            ->withCount('homestay')
            ->orderBy('nama_fasilitas')
            ->paginate(15);

        return view('admin.fasilitas.index', compact('fasilitas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:100', Rule::unique('fasilitas', 'nama_fasilitas')],
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.unique' => 'Fasilitas dengan nama tersebut sudah ada.',
            'nama_fasilitas.max' => 'Nama fasilitas maksimal 100 karakter.',
        ]);

        Fasilitas::create($validated);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    public function update(Request $request, Fasilitas $fasilitas): RedirectResponse
    {
        $validated = $request->validate([
            'nama_fasilitas' => [
                'required',
                'string',
                'max:100',
                Rule::unique('fasilitas', 'nama_fasilitas')->ignore($fasilitas->id_fasilitas, 'id_fasilitas'),
            ],
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.unique' => 'Fasilitas dengan nama tersebut sudah ada.',
            'nama_fasilitas.max' => 'Nama fasilitas maksimal 100 karakter.',
        ]);

        $fasilitas->update($validated);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilitas): RedirectResponse
    {
        if ($fasilitas->homestay()->exists()) {
            return redirect()
                ->route('admin.fasilitas.index')
                ->with('error', 'Fasilitas ini masih digunakan oleh homestay sehingga tidak dapat dihapus.');
        }

        $fasilitas->delete();

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}
