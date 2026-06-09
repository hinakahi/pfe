@extends('layouts.app')
@section('title', 'Réclamations')
@section('page-title', 'Gestion des réclamations')

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Étudiante</th>
                    <th>Sujet</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reclamations as $rec)
                <tr>
                    <td>{{ $rec->etudiante->name }}</td>
                    <td>{{ $rec->sujet }}</td>
                    <td>{{ $rec->date_reclamation->format('d/m/Y') }}</td>
                    <td><span class="badge bg-warning">{{ $rec->statut }}</span></td>
                    <td>
                        <a href="{{ route('admin.reclamations.show', $rec->id) }}" class="btn btn-sm btn-primary">Voir</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection