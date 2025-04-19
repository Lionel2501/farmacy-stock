<?php

namespace App\Http\Controllers;

use App\Models\Medecine;
use Illuminate\Http\Request;

class MedecineController extends Controller
{
    public function index(Request $request)
    {
        try { 
            $search = $request->input('search');

            $data = Medecine::when($search, function ($query, $search) {
                return $query->where('name', 'ILIKE', "%{$search}%");
            })->get();
    
            foreach ($data as $medecine) {
                $medecine->reserva = $medecine->quantity - $medecine->quantity_min;
            }
    
            $data = $data->sortBy('reserva')->values();
    
            return view('medecine.index', compact('data', 'search'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
       
    }

    public function updateMedecine(Request $request, $id)
    {
        try {
            $updated = Medecine::where('id', $id)->update([
                'name'          => $request->name,
                'vendor'        => $request->vendor,
                'description'   => $request->description,
                'quantity'      => $request->quantity,
                'quantity_min'  => $request->quantity_min
            ]);
    
            if ($updated) {
                return redirect()->back()->with('success', 'Medicamento actualizado con éxito.');
            } else {
                return redirect()->back()->with('error', 'No se encontró ningún medicamento con ese ID.');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function handleStock(Request $request)
    {
        try {
            $id = $request->id;
            $stock_value = $request->stock_value;

            $updated = Medecine::where('id', $id)->update([
                'quantity'      => $stock_value
            ]);
    
            if ($updated) {
                return redirect()->back()->with('success', 'Medicamento actualizado con éxito.');
            } else {
                return redirect()->back()->with('error', 'No se encontró ningún medicamento con ese ID.');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function storeItem(Request $request)
    {
        try {
            $request->validate([
                'name'          => 'required|string|max:255|unique:medecines,name',
                'vendor'        => 'required|string|max:255',
                'description'   => 'nullable|string|max:500',
                'quantity'      => 'required|integer|min:0',
                'quantity_min'  => 'required|integer|min:0',
            ]);

            $created = Medecine::create([
                'name'          => $request->name,
                'vendor'        => $request->vendor,
                'description'   => $request->description,
                'quantity'      => $request->quantity,
                'quantity_min'  => $request->quantity_min
            ]);

            if ($created) {
                return redirect()->back()->with('success', 'Item agregado con éxito!');
            } else {
                return redirect()->back()->with('error', 'Error');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function deleteItem($id)
    {
        try {
            $medecine = Medecine::findOrFail($id);
            $medecine->delete();
        
            return redirect()->back()->with('success', 'Item eliminado con éxito.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

}
