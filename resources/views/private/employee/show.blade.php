@include('components.private.header')
@include('components.private.sidebar')

@extends('layout.app')

@section('content')
<div class="container">
    <h2>Détails de l'employé</h2>
    <ul class="list-group">

        <li class="list-group-item"><strong>Doti :</strong> {{ $employee->tpr }}</li>

        <li class="list-group-item"><strong>Prénom :</strong> {{ $employee->first_name }}</li>
        <li class="list-group-item"><strong>Nom :</strong> {{ $employee->last_name }}</li>
        <li class="list-group-item"><strong>Nom et Prénom :</strong> {{ $employee->NOM_ET_PRENOM }}</li>
        <li class="list-group-item"><strong>Nom Prénom :</strong> {{ $employee->nom_prenom }}</li>
        <li class="list-group-item"><strong>الاسم :</strong> {{ $employee->prenomar }}</li>
        <li class="list-group-item"><strong>النسب :</strong> {{ $employee->nomar }}</li>

        <li class="list-group-item"><strong>CIN :</strong> {{ $employee->national_id }}</li>
        <li class="list-group-item"><strong>DRMC :</strong> {{ $employee->drmc }}</li>
        <li class="list-group-item"><strong>DRM/ATT/S :</strong> {{ $employee->drm_att_s }}</li>
        <li class="list-group-item"><strong>Cadre :</strong> {{ $employee->cadre }}</li>
        <li class="list-group-item"><strong>Date Effet 1 :</strong> {{ $employee->date_effet1 }}</li>
        <li class="list-group-item"><strong>Date Effet 2 :</strong> {{ $employee->date_effet2 }}</li>
        <li class="list-group-item"><strong>Échelon :</strong> {{ $employee->ech }}</li>
        <li class="list-group-item"><strong>Grade :</strong> {{ $employee->grade }}</li>
        <li class="list-group-item"><strong>Indice :</strong> {{ $employee->indice }}</li>
        <li class="list-group-item"><strong>Département :</strong> {{ $employee->dep }}</li>
        <li class="list-group-item"><strong>Spécialité :</strong> {{ $employee->specialite }}</li>
        <li class="list-group-item"><strong>Sexe :</strong> {{ $employee->sex }}</li>
        <li class="list-group-item"><strong>Date de naissance :</strong> {{ $employee->date_of_birth }}</li>

    </ul>
    <a href="{{ route('employee.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection

@include('components.private.footer')
