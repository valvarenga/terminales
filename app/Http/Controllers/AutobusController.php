<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\Municipios;
use App\Models\Terminales;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AutobusController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'placa' => ['nullable', 'string', 'max:255'],
            'municipio_origen_id' => ['required', 'integer', 'exists:municipios,id'],
            'hora_salida' => ['required', 'date_format:H:i'],
            'municipio_destino_id' => ['required', 'integer', 'different:municipio_origen_id', 'exists:municipios,id'],
            'hora_llegada' => ['required', 'date_format:H:i'],
            'terminal' => ['required', 'exists:terminales,id'],
            'categoria' => ['required', 'in:Expreso,Ruteado'],
        ]);

        $origen = Municipios::findOrFail($data['municipio_origen_id']);
        $terminal = Terminales::findOrFail($data['terminal']);
        if ((int) $terminal->departamento_id !== (int) $origen->departamento_id) {
            return back()->withInput()->withErrors(['terminal' => 'La terminal y el municipio de origen deben pertenecer al mismo departamento.']);
        }

        $destino = Municipios::findOrFail($data['municipio_destino_id']);
        $autobus = new Autobuses();
        $autobus->fill(array_diff_key($data, ['terminal' => true]));
        // Preserve the legacy text fields while the data is transitioned to municipality IDs.
        $autobus->origen = $origen->nombre;
        $autobus->destino = $destino->nombre;
        $autobus->slug = Str::slug($data['nombre'] . '-' . $data['placa']);
        $autobus->save();
        $autobus->terminales()->sync([$data['terminal']]);

        return redirect()->route('departamento.autobuses', $data['terminal'])->with('success', 'Autobús creado correctamente.');
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
            'autobus' => $autobus,
            'municipios' => Municipios::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Autobuses $autobus)
    {
        $data = $request->validate([
            'municipio_origen_id' => ['required', 'integer', 'exists:municipios,id'],
            'municipio_destino_id' => ['required', 'integer', 'different:municipio_origen_id', 'exists:municipios,id'],
        ]);

        $autobus->fill($data);
        $autobus->origen = Municipios::findOrFail($data['municipio_origen_id'])->nombre;
        $autobus->destino = Municipios::findOrFail($data['municipio_destino_id'])->nombre;
        $autobus->save();

        return redirect()->route('newbus')->with('success', 'Servicio vinculado a sus municipios correctamente.');
    }

    public function destroy(Autobuses $autobus)
    {
        $autobus->delete();

        return redirect()->route('autobuses.list')->with('success', 'Autobus eliminado correctamente.');
    }
}
