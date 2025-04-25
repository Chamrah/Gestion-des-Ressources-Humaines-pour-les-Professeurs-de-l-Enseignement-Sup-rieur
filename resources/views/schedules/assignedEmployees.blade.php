@include('components.private.header')
@include('components.private.sidebar')
<section class="home-section" style="margin-bottom: 500px">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">{{ $schedule->name }} staff</span>
    </div>
    <div class="mycontent">

        <div class="container-sm mt-5">
            <h2>{{ $schedule->name }} - Employés assignés</h2>
            {{ $employees->links('vendor.pagination.simple-bootstrap-4') }}
            
            @if ($employees->isEmpty())
                <p>Aucun employé n’est actuellement assigné à cet emploi du temps.</p>
            @else
                <div class="list-group">
                    @foreach ($employees as $employee)
                        @if ($employee->training == 0)
                            <a href="{{ route('employee.show', $employee->id) }}"
                                class="list-group-item list-group-item-action">
                                {{ $employee->first_name }} , {{ $employee->last_name }} -
                                <span class="badge text-bg-primary">Employé</span>
                                <span class="text-primary float-end">Voir plus</span>
                            </a>
                        @else
                            <a href="{{ route('trainee.show', $employee->id) }}"
                                class="list-group-item list-group-item-action">
                                {{ $employee->first_name }} , {{ $employee->last_name }} -
                                <span class="badge text-bg-info">Stagiaire</span>
                                <span class="text-primary float-end">Voir plus</span>
                            </a>
                        @endif
                    @endforeach
                </div>
          
            
                <!-- Pagination -->
                <div class="mt-4">
        {{ $employees->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>
</section>

@include('components.private.footer')
