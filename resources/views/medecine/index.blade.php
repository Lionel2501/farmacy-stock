@extends('layouts.app')

@section('title', 'Stock Management')

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

        <div class="w-100 d-flex justify-content-end mb-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createModal">Agregar item</button>
        </div>             
        
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
                    <th>Acción</th>
                    <th>Actualizar</th>
                    <th>Editar</th>
                    <th>Borrar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $medecine)
                    <tr class="{{ 
                        $medecine->reserva < 0 ? 'bg-danger-light' : 
                        ($medecine->reserva <= 10 ? 'bg-warning-light' : 'bg-success-light') 
                    }}">
                        <td>{{ $medecine->name }}</td>
                        <td>{{ $medecine->vendor }}</td>
                        <td>{{ $medecine->description }}</td>
                        <td>{{ $medecine->quantity }}</td>
                        <td>{{ $medecine->quantity_min }}</td>
                        <td>{{ $medecine->reserva }}</td>
                        <form action="{{ route('handleStock') }}" method="post">
                            @csrf
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" onclick="decrementStock({{ $medecine->id }})">−</button>
                        
                                <input type="hidden" name="id" value="{{ $medecine->id }}">
                        
                                <input type="number" name="stock_value" 
                                    id="stock_value_{{ $medecine->id }}" 
                                    value="{{ $medecine->quantity }}" 
                                    class="form-control d-inline-block" 
                                    style="width: 70px; text-align: center;">
                        
                                <button type="button" class="btn btn-sm btn-success" onclick="incrementStock({{ $medecine->id }})">+</button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    Actualizar
                                </button>
                            </td>
                        </form>
                        <td>
                            <!-- Bouton pour ouvrir le modal -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-{{ $medecine->id }}">
                                Editar
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#borrarModal-{{ $medecine->id }}">
                                Borrar
                            </button>
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
                                        <h5 class="modal-title" id="editModalLabel-{{ $medecine->id }}">Modificar Remedio</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" name="name" class="form-control" value="{{ $medecine->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Provedor</label>
                                            <input type="text" name="vendor" class="form-control" value="{{ $medecine->vendor }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Descripción</label>
                                            <input type="text" name="description" class="form-control" value="{{ $medecine->description }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stock actual</label>
                                            <input type="number" name="quantity" class="form-control" value="{{ $medecine->quantity }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stock minimo</label>
                                            <input type="number" name="quantity_min" class="form-control" value="{{ $medecine->quantity_min }}">
                                        </div>
                                        <!-- On n'affiche pas la réserve car elle est calculée -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal fade" id="borrarModal-{{ $medecine->id }}" tabindex="-1" aria-labelledby="borrarModalLabel-{{ $medecine->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('deleteItem', $medecine->id) }}">
                                @csrf
                                @method('DELETE')
            
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="borrarModalLabel-{{ $medecine->id }}">Confirmar eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que deseas eliminar el remedio <strong>{{ $medecine->name }}</strong>?
                                        Esta acción no se puede deshacer.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('storeItem') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Agregar Remedio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Proveedor</label>
                                <input type="text" name="vendor" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <input type="text" name="description" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock actual</label>
                                <input type="number" name="quantity" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock mínimo</label>
                                <input type="number" name="quantity_min" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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


<script>
    function incrementStock(id) {
        const input = document.getElementById('stock_value_' + id);
        input.value = parseInt(input.value) + 1;
    }

    function decrementStock(id) {
        const input = document.getElementById('stock_value_' + id);
        input.value = parseInt(input.value) - 1;
    }
</script>
