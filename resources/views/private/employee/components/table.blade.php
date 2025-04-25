<style>
    thead th {
        position: sticky;
        top: 0;
        background-color: #000;
        color: white;
        z-index: 2;
    }
</style>

<!-- Bootstrap CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div>@include('import_exel')</div>
<br><br>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th>Doti</th>
                <th>Nom & Prénom</th>
                <th>Nom Arabe</th>
                <th>Prénom Arabe</th>
                <th>CIN</th>
                <th>Cadre</th>
                <th>Sexe</th>
                <th>Date Naissance</th>
                <th>Date Retraite</th>
                <th>Spécialité</th>
                <th>Actions</th>
            </tr>
        </thead>
    
        <tbody>
            @foreach ($data as $employee)
                <tr>
                    <td>{{ $employee->tpr }}</td>
    
                    <!-- Nom & Prénom avec lien pour afficher la modale -->
                    <td>
                        <a href="#" class="text-dark text-decoration-none" data-bs-toggle="modal" data-bs-target="#employeeModal{{ $employee->id }}">
                            <strong>{{ $employee->NOM_ET_PRENOM ?? $employee->first_name . ' ' . $employee->last_name }}</strong>
                        </a>
    
                        <!-- Modal de détails -->
                        <div class="modal fade" id="employeeModal{{ $employee->id }}" tabindex="-1" aria-labelledby="employeeModalLabel{{ $employee->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="employeeModalLabel{{ $employee->id }}">
                                            {{ $employee->NOM_ET_PRENOM ?? $employee->first_name . ' ' . $employee->last_name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <div class="card-body">
    
                                                <p><strong>Nom Ar :</strong> {{ $employee->nomar }}</p>
                                                <p><strong>Prénom Ar :</strong> {{ $employee->prenomar }}</p>
                                                <p><strong>Spécialité :</strong> {{ $employee->specialite }}</p>
                                                <p><strong>D, R, M, C :</strong> {{ $employee->drmc }}</p>
                                                <p><strong>D, R, M, ATT, S :</strong> {{ $employee->drm_att_s }}</p>
                                                <p><strong>Date Effet 1 :</strong> {{ $employee->date_effet1 }}</p>
                                                <p><strong>Date Effet 2 :</strong> {{ $employee->date_effet2 }}</p>
    
                                                <hr>
                                                <a href="{{ route('employee.show', ['employee' => $employee->id]) }}" class="btn btn-primary">
                                                    Voir les détails complets
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
    
                    <td>{{ $employee->nomar }}</td>
                    <td>{{ $employee->prenomar }}</td>
                    <td>{{ $employee->national_id }}</td>
                    <td>{{ $employee->cadre }}</td>
                    <td>{{ $employee->sex ?? $employee->gender }}</td>
                    <td>{{ $employee->date_of_birth }}</td>
                    <td>{{ $employee->date_retarite }}</td>
                    <td>{{ $employee->specialite }}</td>
                    <td>
                        {{-- <div class="btn-group" role="group">
                            <a href="{{ route('employee.show', ['employee' => $employee->id]) }}" class="btn btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('employee.edit', ['employee' => $employee->id]) }}" class="btn btn-outline-dark" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div> --}}
    
    
                            <div class="btn-group" role="group">
                                <!-- View Button -->
                                <a href="{{ route('employee.show', ['employee' => $employee->id]) }}" class="btn btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
    
                                <!-- Edit Button -->
                                <a href="{{ route('employee.edit', ['employee' => $employee->id]) }}" class="btn btn-outline-dark" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
    
                                <!-- Delete Button -->
                                <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
    
                            </div>
    
    
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $data->links('vendor.pagination.bootstrap-5')}}
</div>
