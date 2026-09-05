<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\Municipios;
use App\Models\Terminales;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\AuditLogger;

class AutobusController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        DB::transaction(function () use ($data) {
            $autobus = new Autobuses();
            $autobus->slug = substr(Str::slug($data['nombre'].'-'.($data['placa'] ?? '')), 0, 240).'-'.Str::lower(Str::random(8));
            $this->saveService($autobus, $data);
        });
        return redirect()->route('newbus')->with('success', 'El servicio de autobús se guardó correctamente.');
    }

    public function index()
    {
        return view('autobus.index', [
            'terminales' => Terminales::with('municipios')->orderBy('nombre')->get(),
            'municipios' => Municipios::orderBy('nombre')->get(),
            'autobusesPendientes' => Autobuses::query()
                ->whereNull('municipio_origen_id')
                ->orWhereNull('municipio_destino_id')
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function list()
    {
        return view('autobus.listar', [
            'autobuses' => Autobuses::with(['origenMunicipio', 'destinoMunicipio', 'terminales'])->orderBy('nombre')->get(),
        ]);
    }

    public function show(Autobuses $autobus)
    {
        $autobus->load(['origenMunicipio', 'destinoMunicipio', 'terminales']);

        return view('autobus.show', compact('autobus'));
    }

    public function edit(Autobuses $autobus)
    {
        return view('autobus.edit', [
            'autobus' => $autobus->load('terminales'),
            'terminales' => Terminales::with('municipios')->orderBy('nombre')->get(),
            'municipios' => Municipios::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Autobuses $autobus)
    {
        $data = $this->validatedData($request);
        DB::transaction(fn () => $this->saveService($autobus, $data));
        return redirect()->route('autobuses.list')->with('success', 'Servicio actualizado correctamente.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'placa' => ['nullable', 'string', 'max:255'],
            'municipio_origen_id' => ['required', 'integer', 'exists:municipios,id'],
            'municipio_destino_id' => ['required', 'integer', 'different:municipio_origen_id', 'exists:municipios,id'],
            'hora_salida' => ['required', 'date_format:H:i'],
            'hora_llegada' => ['required', 'date_format:H:i'],
            'terminal' => ['required', 'integer', 'exists:terminales,id'],
            'categoria' => ['required', 'in:Expreso,Ruteado'],
            'tarifa' => ['nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:999999.99'],
        ]);
        $origen = Municipios::findOrFail($data['municipio_origen_id']);
        $terminal = Terminales::findOrFail($data['terminal']);
        if ((int) $terminal->departamento_id !== (int) $origen->departamento_id) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'terminal' => 'La terminal y el municipio de origen deben pertenecer al mismo departamento.',
            ]);
        }
        $data['origen'] = $origen->nombre;
        $data['destino'] = Municipios::findOrFail($data['municipio_destino_id'])->nombre;
        $data['tarifa'] = $data['tarifa'] ?? null;
        return $data;
    }

    private function saveService(Autobuses $autobus, array $data): void
    {
        $oldTerminals = $autobus->exists ? $autobus->terminales()->pluck('terminales.id')->map(fn ($id) => (int) $id)->sort()->values()->all() : [];
        $autobus->fill(array_diff_key($data, ['terminal' => true]));
        $autobus->save();
        $newTerminals = [(int) $data['terminal']];
        $autobus->terminales()->sync($newTerminals);
        if ($oldTerminals !== $newTerminals) {
            AuditLogger::record($autobus, 'terminales_actualizadas', ['terminales' => $oldTerminals], ['terminales' => $newTerminals]);
        }
    }

    public function destroy(Autobuses $autobus)
    {
        DB::transaction(fn () => $autobus->delete());

        return redirect()->route('autobuses.list')->with('success', 'Autobus eliminado correctamente.');
    }
}
