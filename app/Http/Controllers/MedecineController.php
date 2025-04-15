<?php

namespace App\Http\Controllers;

use App\Models\Medecine;
use Illuminate\Http\Request;

class MedecineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $data = Medecine::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })->get();

        foreach ($data as $medecine) {
            $medecine->reserva = $medecine->quantity - $medecine->quantity_min;
        }

        $data = $data->sortBy('reserva')->values();

        return view('medecine.index', compact('data', 'search'));
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
                return redirect()->back()->with('success', 'Médicament mis à jour avec succès.');
            } else {
                return redirect()->back()->with('error', 'Aucun médicament trouvé avec cet ID.');
            }
    
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

}
