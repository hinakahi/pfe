@extends('layouts.app')
@section('title', 'Réclamation')
@section('page-title', 'Détails')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <p><strong>Étudiante :</strong> {{ $reclamation->etudiante->name }}</p>
                <p><strong>Sujet :</strong> {{ $reclamation->sujet }}</p>
                <p><strong>Message :</strong></p>
                <p>{{ $reclamation->message }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.reclamations.update', $reclamation->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="en_attente" @selected($reclamation->statut === 'en_attente')>En attente</option>
                            <option value="en_cours" @selected($reclamation->statut === 'en_cours')>En cours</option>
                            <option value="resolue" @selected($reclamation->statut === 'resolue')>Résolue</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Réponse</label>
                        <textarea name="reponse" class="form-control" rows="4">{{ $reclamation->reponse }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection