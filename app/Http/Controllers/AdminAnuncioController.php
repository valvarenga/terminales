<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminAnuncioController extends Controller
{
    public function index()
    {
        return view('admin.anuncios', [
            'anuncios' => Anuncio::orderBy('orden')->orderByDesc('created_at')->paginate(12),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateAnuncio($request);
        $data['imagen'] = $this->guardarImagen($request);
        $data['activo'] = $request->boolean('activo');

        Anuncio::create($data);

        return redirect()->route('admin.anuncios.index')->with('success', 'Anuncio creado correctamente.');
    }

    public function edit(Anuncio $anuncio)
    {
        return view('admin.anuncio-edit', compact('anuncio'));
    }

    public function update(Request $request, Anuncio $anuncio)
    {
        $data = $this->validateAnuncio($request, $anuncio);
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $this->guardarImagen($request);
            Storage::disk('public')->delete($anuncio->imagen);
        }
        $data['activo'] = $request->boolean('activo');

        $anuncio->update($data);

        return redirect()->route('admin.anuncios.index')->with('success', 'Anuncio actualizado correctamente.');
    }

    public function destroy(Anuncio $anuncio)
    {
        Storage::disk('public')->delete($anuncio->imagen);
        $anuncio->delete();

        return redirect()->route('admin.anuncios.index')->with('success', 'Anuncio eliminado correctamente.');
    }

    private function validateAnuncio(Request $request, ?Anuncio $anuncio = null): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'negocio' => ['required', 'string', 'max:150'],
            'imagen' => [$anuncio ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'enlace' => ['nullable', 'url', 'max:255'],
            'orden' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'activo' => ['sometimes', 'boolean'],
        ], [
            'imagen.required' => 'Selecciona una imagen para el anuncio.',
            'imagen.mimes' => 'La imagen debe ser JPG, PNG o WEBP.',
            'imagen.max' => 'La imagen no debe superar 4 MB.',
            'enlace.url' => 'El enlace debe ser una URL válida (ej. https://ejemplo.com).',
        ]);
    }

    private function guardarImagen(Request $request): string
    {
        return $request->file('imagen')->store('anuncios', 'public');
    }
}
