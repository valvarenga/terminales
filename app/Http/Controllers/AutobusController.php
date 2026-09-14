<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\Municipios;
use App\Models\Terminales;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\AuditLogger;
use Illuminate\Validation\ValidationException;

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
        $autobus->load(['origenMunicipio', 'destinoMunicipio', 'terminales', 'paradas.municipio']);

        return view('autobus.show', compact('autobus'));
    }

    public function edit(Autobuses $autobus)
    {
        return view('autobus.edit', [
            'autobus' => $autobus->load(['terminales', 'paradas.municipio']),
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
        if (! $request->has('paradas')) {
            $request->merge(['paradas' => [
                ['municipio_id' => $request->input('municipio_origen_id'), 'hora_paso' => $request->input('hora_salida'), 'tarifa_acumulada' => 0],
                ['municipio_id' => $request->input('municipio_destino_id'), 'hora_paso' => $request->input('hora_llegada'), 'tarifa_acumulada' => $request->input('tarifa')],
            ]]);
        }

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'placa' => ['nullable', 'string', 'max:255'],
            'terminal' => ['required', 'integer', 'exists:terminales,id'],
            'categoria' => ['required', 'in:Expreso,Ruteado'],
            'municipio_origen_id' => ['nullable', 'integer', 'exists:municipios,id'],
            'municipio_destino_id' => ['nullable', 'integer', 'different:municipio_origen_id', 'exists:municipios,id'],
            'hora_salida' => ['nullable', 'date_format:H:i'],
            'hora_llegada' => ['nullable', 'date_format:H:i'],
            'tarifa' => ['nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:999999.99'],
            'paradas' => ['required', 'array', 'min:2'],
            'paradas.*.municipio_id' => ['required', 'integer', 'distinct', 'exists:municipios,id'],
            'paradas.*.hora_paso' => ['required', 'date_format:H:i'],
            'paradas.*.tarifa_acumulada' => ['nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:999999.99'],
        ]);

        $paradas = array_values($data['paradas']);
        $previousMinutes = null;
        $previousFare = null;
        foreach ($paradas as $index => &$parada) {
            [$hours, $minutes] = array_map('intval', explode(':', $parada['hora_paso']));
            $currentMinutes = ($hours * 60) + $minutes;
            if ($previousMinutes !== null && $currentMinutes <= $previousMinutes) {
                throw ValidationException::withMessages(['paradas.'.$index.'.hora_paso' => 'La hora debe ser posterior a la parada anterior.']);
            }
            $fare = ($parada['tarifa_acumulada'] ?? '') === '' ? null : round((float) $parada['tarifa_acumulada'], 2);
            if ($index === 0 && $fare !== 0.0) {
                throw ValidationException::withMessages(['paradas.0.tarifa_acumulada' => 'La tarifa acumulada de la primera parada debe ser C$ 0.']);
            }
            if ($fare !== null && $previousFare !== null && $fare < $previousFare) {
                throw ValidationException::withMessages(['paradas.'.$index.'.tarifa_acumulada' => 'La tarifa acumulada no puede disminuir.']);
            }
            $parada['tarifa_acumulada'] = $fare;
            $previousMinutes = $currentMinutes;
            if ($fare !== null) $previousFare = $fare;
        }
        unset($parada);

        $origen = Municipios::findOrFail($paradas[0]['municipio_id']);
        $destino = Municipios::findOrFail($paradas[array_key_last($paradas)]['municipio_id']);
        $terminal = Terminales::findOrFail($data['terminal']);
        if ((int) $terminal->departamento_id !== (int) $origen->departamento_id) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'terminal' => 'La terminal y el municipio de origen deben pertenecer al mismo departamento.',
            ]);
        }
        $data['municipio_origen_id'] = $origen->id;
        $data['municipio_destino_id'] = $destino->id;
        $data['hora_salida'] = $paradas[0]['hora_paso'];
        $data['hora_llegada'] = $paradas[array_key_last($paradas)]['hora_paso'];
        $data['origen'] = $origen->nombre;
        $data['destino'] = $destino->nombre;
        $data['tarifa'] = $paradas[array_key_last($paradas)]['tarifa_acumulada'];
        $data['paradas'] = $paradas;
        return $data;
    }

    private function saveService(Autobuses $autobus, array $data): void
    {
        $oldTerminals = $autobus->exists ? $autobus->terminales()->pluck('terminales.id')->map(fn ($id) => (int) $id)->sort()->values()->all() : [];
        $oldStops = $autobus->exists ? $autobus->paradas()->get()->map(fn ($stop) => $stop->only(['municipio_id', 'posicion', 'hora_paso', 'tarifa_acumulada']))->all() : [];
        $autobus->fill(array_diff_key($data, ['terminal' => true, 'paradas' => true]));
        $autobus->save();
        $newTerminals = [(int) $data['terminal']];
        $autobus->terminales()->sync($newTerminals);
        if ($oldTerminals !== $newTerminals) {
            AuditLogger::record($autobus, 'terminales_actualizadas', ['terminales' => $oldTerminals], ['terminales' => $newTerminals]);
        }
        $autobus->paradas()->delete();
        foreach ($data['paradas'] as $position => $stop) {
            $autobus->paradas()->create([
                'municipio_id' => $stop['municipio_id'],
                'posicion' => $position,
                'hora_paso' => $stop['hora_paso'],
                'tarifa_acumulada' => $stop['tarifa_acumulada'],
            ]);
        }
        $newStops = $autobus->paradas()->get()->map(fn ($stop) => $stop->only(['municipio_id', 'posicion', 'hora_paso', 'tarifa_acumulada']))->all();
        if ($oldStops !== $newStops) {
            AuditLogger::record($autobus, 'recorrido_actualizado', ['paradas' => $oldStops], ['paradas' => $newStops]);
        }
    }

    public function destroy(Autobuses $autobus)
    {
        DB::transaction(fn () => $autobus->delete());

        return redirect()->route('autobuses.list')->with('success', 'Autobus eliminado correctamente.');
    }
}
