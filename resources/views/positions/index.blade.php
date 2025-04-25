@include('components.private.header')
@include('components.private.sidebar')
<style>
    .badge-hover-animate {
        transition: transform 0.3s ease, background-color 0.3s ease;
        /* Smooth transition for transform and color */
    }

    .badge-hover-animate:hover {
        transform: scale(1.4);
        /* Scales up the badge */
        /* Changes color on hover, adjust color as needed */
    }
</style>

<section class="home-section" style="margin-bottom: 500px">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Grades</span>
    </div>






</div>

<div class="mycontent">
<div class="container-sm mt-5">
    <!-- Bouton pour déclencher la modale -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Ajouter un Poste
    </button>

    <!-- Modale -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajouter un Poste</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('positions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salaire</label>
                            <input type="numeric" class="form-control" id="salary" name="salary">
                        </div>
                        <button type="submit" class="btn btn-primary">Soumettre</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>

                    </div>
                </div>
            </div>

            <hr>

            <ol class="list-group list-group-numbered">
                @foreach ($data as $position)
                    <li class="list-group-item d-flex justify-content-between align-items-start @if (!($position->employees()->count() > 0 || $position->trainees()->count() > 0)) text-danger @endif">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">{{ $position->name }}</div>
                            Base Salary : {{ $position->salary }}
                        </div>

                        @if ($position->employees()->count() > 0 || $position->trainees()->count() > 0)
                            <div class="btn-group btn-group-sm me-2" role="group" aria-label="Small button group">
                                <a href="{{ route('positions.edit', ['position' => $position->id]) }}"
                                    class="btn btn-outline-primary" tabindex="-1" role="button"
                                    aria-disabled="true">Modifier</a>
                            </div>
                            <a
                                href="{{ route('positions.employees', ['position' => $position->id, 'status' => 'employee']) }}">
                                <span class="badge text-bg-primary rounded-pill badge-hover-animate"
                                    style="font-size: 20px">
                                    {{ $position->employees()->count() }}
                                </span>
                            </a>
                            
                        @else
                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                <a href="{{ route('positions.edit', ['position' => $position->id]) }}"
                                    class="btn btn-outline-primary" tabindex="-1" role="button"
                                    aria-disabled="true">Modifier</a>
                                <a href="{{ route('positions.delete', ['position' => $position->id]) }}"
                                    class="btn btn-outline-danger" tabindex="-1" role="button"
                                    aria-disabled="true">Supprimer</a>
                            </div>
                        @endif



                    </li>
                @endforeach
            </ol>
            <hr>


            @php
                $totalEmployees = 0;
                $positionWithMostEmployees = null;
                $maxEmployeeCount = 0;
                $positionWithFewestEmployees = null;
                $minEmployeeCount = PHP_INT_MAX;
                $totalSalaryExpense = 0;
                $totalTrainees = 0;
                $positionNames = $data->pluck('name');
                $employeeCounts = $data->map(function ($position) {
                    return $position->employees()->count();
                });
                $traineeCounts = $data->map(function ($position) {
                    return $position->trainees()->count();
                });

            @endphp
            @foreach ($data as $position)
                @php
                    $employeeCount = $position->employees()->count();
                    $totalEmployees += $employeeCount;
                    if ($employeeCount > $maxEmployeeCount) {
                        $maxEmployeeCount = $employeeCount;
                        $positionWithMostEmployees = $position;
                    }
                    if ($employeeCount < $minEmployeeCount) {
                        $minEmployeeCount = $employeeCount;
                        $positionWithFewestEmployees = $position;
                    }
                    $totalSalaryExpense += $position->salary * $employeeCount;
                    $totalTrainees += $position->trainees()->count();
                @endphp
            @endforeach



            @include('positions.components.statistics')


            <!-- Include necessary scripts -->

            <!-- Chart canvas -->
            @include('positions.components.positionsChart')

        </div>
    </div>

</section>
@error('name')
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto text-danger">Error</strong>
                <small>position name</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ $message }}
            </div>
        </div>
    </div>
@enderror
@error('salary')
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto text-danger">Error</strong>
                <small>position salary</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ $message }}
            </div>
        </div>
    </div>
@enderror

<script>
    // Display the toast when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        var toastEl = document.getElementById('liveToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
</script>
@include('components.private.footer')