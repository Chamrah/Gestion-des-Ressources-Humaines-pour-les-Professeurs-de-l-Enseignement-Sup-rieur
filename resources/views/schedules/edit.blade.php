@include('components.private.header')
@include('components.private.sidebar')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

<section class="home-section" style="margin-bottom: 500px">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Modifier l’emploi du temps</span>
    </div>
    <div class="mycontent">
        <div class="container-sm mt-5">
            <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Important pour Laravel pour reconnaître une opération de mise à jour -->
                <div class="mb-3">
                    <label for="scheduleName" class="form-label">Nom de l’emploi du temps</label>
                    <input type="text" class="form-control" id="scheduleName" name="scheduleName"
                        placeholder="Entrez le nom de l’emploi du temps" value="{{ old('scheduleName', $schedule->name) }}" required>
                </div>
                <h2 class="mb-3">Sélecteur de jours de la semaine</h2>
                <div class="row">
                    <div class="col">
                        <label class="form-label">Sélectionnez les jours</label>
                        @foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $index => $jour)
                            @php
                                $daysMap = ['Lundi' => 'Monday', 'Mardi' => 'Tuesday', 'Mercredi' => 'Wednesday', 'Jeudi' => 'Thursday', 'Vendredi' => 'Friday', 'Samedi' => 'Saturday', 'Dimanche' => 'Sunday'];
                                $dayKey = $daysMap[$jour];
                            @endphp
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="{{ strtolower($dayKey) }}"
                                    name="days[]" value="{{ $dayKey }}"
                                    {{ in_array($dayKey, old('days', explode(',', $schedule->days_of_week))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ strtolower($dayKey) }}">{{ $jour }}</label>
                            </div>
                        @endforeach
                    </div>
    
                    <div class="col">
                        <label for="startTime" class="form-label">Heure de début</label>
                        <input type="text" class="form-control mb-3 timepicker" id="startTime" name="startTime"
                            value="{{ old('startTime', $schedule->start_time) }}">
    
                        <label for="endTime" class="form-label">Heure de fin</label>
                        <input type="text" class="form-control mb-3 timepicker" id="endTime" name="endTime"
                            value="{{ old('endTime', $schedule->end_time) }}">
                    </div>
                </div>
    
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
    
        </div>
    </div>
    <div aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
        <!-- Toast -->
        <div class="toast" id="validationToast">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Erreur de la validation</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
            <div class="toast-body bg-white">
                <!-- Error messages will be inserted here -->
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                let errorMessage = `<ul>`;
                @foreach ($errors->all() as $error)
                    errorMessage += `<li>{{ $error }}</li>`;
                @endforeach
                errorMessage += `</ul>`;

                document.querySelector('#validationToast .toast-body').innerHTML = errorMessage;
                var toast = new bootstrap.Toast(document.getElementById('validationToast'));
                toast.show();
            @endif
        });
    </script>
</section>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
</script>

@include('components.private.footer')
