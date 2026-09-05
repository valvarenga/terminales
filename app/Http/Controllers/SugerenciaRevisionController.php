<?php

namespace App\Http\Controllers;

use App\Models\SugerenciaTerminal;
use App\Models\Terminales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SugerenciaRevisionController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate(['estado' => ['nullable', Rule::in(['pendiente', 'aprobada', 'rechazada', 'todas'])]]);
        $estado = $data['estado'] ?? 'pendiente';

        return view('admin.sugerencias', [
            'estado' => $estado,
            'sugerencias' => SugerenciaTerminal::with('terminal')->when($estado !== 'todas', fn ($query) => $query->where('estado', $estado))->latest()->paginate(12)->withQueryString(),
            'terminales' => Terminales::with('municipios')->orderBy('nombre')->get(),
        ]);
    }

    public function revisar(Request $request, SugerenciaTerminal $sugerencia)
    {
        $data = $request->validate([
            'estado' => ['required', Rule::in(['aprobada', 'rechazada'])],
            'terminal_id' => ['required_if:estado,aprobada', 'nullable', 'integer', 'exists:terminales,id'],
            'motivo_revision' => ['required_if:estado,rechazada', 'nullable', 'string', 'max:2000'],
            'publicar_foto' => ['sometimes', 'boolean'],
            'reemplazar_foto' => ['sometimes', 'boolean'],
        ]);

        $publishedPath = null;
        try {
            DB::transaction(function () use ($request, $sugerencia, $data, &$publishedPath) {
                $revision = SugerenciaTerminal::whereKey($sugerencia->id)->lockForUpdate()->firstOrFail();
                if ($revision->estado !== 'pendiente') {
                    throw ValidationException::withMessages(['estado' => 'Esta sugerencia ya fue revisada. Actualiza la lista para consultar su estado.']);
                }
                $terminal = $data['estado'] === 'aprobada' ? Terminales::whereKey($data['terminal_id'])->lockForUpdate()->firstOrFail() : null;
                if ($request->boolean('publicar_foto')) {
                    if (! $terminal || ! $revision->foto) {
                        throw ValidationException::withMessages(['publicar_foto' => 'Solo puedes publicar la foto de una sugerencia aprobada con terminal vinculada.']);
                    }
                    if ($terminal->url_T && ! $request->boolean('reemplazar_foto')) {
                        throw ValidationException::withMessages(['reemplazar_foto' => 'La terminal ya tiene foto. Confirma expresamente su reemplazo o desmarca publicar foto.']);
                    }
                    $privatePath = 'sugerencias-terminales/'.basename($revision->foto);
                    $source = Storage::disk('local')->exists($privatePath) ? Storage::disk('local')->path($privatePath) : public_path($revision->foto);
                    $info = is_file($source) ? @getimagesize($source) : false;
                    $extension = $info ? (['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$info['mime']] ?? null) : null;
                    if (! $extension) {
                        throw ValidationException::withMessages(['publicar_foto' => 'No se encontró una foto válida para publicar.']);
                    }
                    $publishedPath = 'imagenes/terminal/'.Str::uuid().'.'.$extension;
                    if (! Storage::disk('public')->put($publishedPath, file_get_contents($source))) {
                        throw ValidationException::withMessages(['publicar_foto' => 'No se pudo guardar la foto. Intenta nuevamente.']);
                    }
                    $terminal->url_T = Storage::disk('public')->url($publishedPath);
                    $terminal->save();
                }
                $revision->estado = $data['estado'];
                $revision->terminal_id = $terminal?->id;
                $revision->motivo_revision = $data['motivo_revision'] ?? null;
                $revision->revisada_por = (string) $request->session()->get('admin_actor', config('admin.username') ?: 'administrador');
                $revision->revisada_at = now();
                $revision->save();
            });
        } catch (\Throwable $error) {
            if ($publishedPath) {
                Storage::disk('public')->delete($publishedPath);
            }
            throw $error;
        }

        return redirect()->route('admin.suggestions.index', ['estado' => $data['estado']])->with('success', 'Sugerencia '.$data['estado'].' correctamente.');
    }
}
