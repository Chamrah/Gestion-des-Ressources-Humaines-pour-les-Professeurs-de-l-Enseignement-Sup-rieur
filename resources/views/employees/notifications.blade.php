@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">📢 Notifications des promotions</h2>

        @if ($notifications->count())
            <ul class="list-group">
                @foreach ($notifications as $notif)
                    <li class="list-group-item">
                        <strong>{{ $notif->employee->NOM_ET_PRENOM ?? 'Employé inconnu' }}</strong><br>
                        {{ $notif->message }} <br>
                        <small class="text-muted">{{ $notif->date_changement->format('d/m/Y') }}</small>
                    </li>
                @endforeach
            </ul>

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="alert alert-info">Aucune notification trouvée.</div>
        @endif
    </div>
@endsection

