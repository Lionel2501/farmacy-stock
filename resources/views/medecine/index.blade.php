@extends('layouts.app')

@section('title', 'Liste des Médicaments')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Stock Farmacia</h1>

        <form method="GET" class="mb-4 d-flex align-items-center gap-2">
            <input type="text" name="search" class="form-control" placeholder="Buscar" value="{{ $search ?? '' }}">
            
            <!-- Bouton pour réinitialiser la recherche -->
            <a href="{{ route('index') }}" class="btn btn-sm btn-outline-secondary">Resetear</a>
            
            <!-- Bouton pour lancer la recherche -->
            <button type="submit" class="btn btn-sm btn-success">Buscar</button>
        </form>
        
        <!-- Table pour afficher les médicaments -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Provedor</th>
                    <th>Descripción</th>
                    <th>Stock Actual</th>
                    <th>Stock Minimo</th>
                    <th>Stock Parcial</th>
                    <th>Editar</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $medecine)
                @php
                    $reserva = $medecine->quantity - $medecine->quantity_min;
                @endphp
                <tr class="{{ 
                    $reserva < 0 ? 'bg-danger-light' : 
                    ($reserva <= 10 ? 'bg-warning-light' : 'bg-success-light') 
                }}">
                    <td>{{ $medecine->name }}</td>
                    <td>{{ $medecine->vendor }}</td>
                    <td>{{ $medecine->description }}</td>
                    <td>{{ $medecine->quantity }}</td>
                    <td>{{ $medecine->quantity_min }}</td>
                    <td>{{ $reserva }}
                    </td>
                    <td>
                        <!-- Bouton pour ouvrir le modal -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-{{ $medecine->id }}">
                            Editar
                        </button>
                    </td>
                    <td>
                        <input type="number" name="less" id="">-</input>
                        <input type="number" name="add" id="">+</input>
                    </td>
                </tr>
            
                <!-- Modal -->
                <div class="modal fade" id="editModal-{{ $medecine->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $medecine->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('updateMedecine', $medecine->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-{{ $medecine->id }}">Modifier Médicament</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="name" class="form-control" value="{{ $medecine->name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Provedor</label>
                                        <input type="text" name="vendor" class="form-control" value="{{ $medecine->vendor }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" name="description" class="form-control" value="{{ $medecine->description }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stock actuel</label>
                                        <input type="number" name="quantity" class="form-control" value="{{ $medecine->quantity }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stock minimum</label>
                                        <input type="number" name="quantity_min" class="form-control" value="{{ $medecine->quantity_min }}">
                                    </div>
                                    <!-- On n'affiche pas la réserve car elle est calculée -->
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
            
            </tbody>
        </table>
    </div>
@endsection

@section('styles')
    <style>
        /* Définition des couleurs de fond avec opacité moins forte */
        .bg-danger-light {
            background-color: rgba(248, 171, 177, 0.5); /* Rouge clair avec opacité 50% */
        }
        .bg-warning-light {
            background-color: rgba(255, 229, 142, 0.5); /* Jaune clair avec opacité 50% */
        }
        .bg-success-light {
            background-color: rgba(159, 255, 181, 0.5); /* Vert clair avec opacité 50% */
        }
    </style>
@endsection
