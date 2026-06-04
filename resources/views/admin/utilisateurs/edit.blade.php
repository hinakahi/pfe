@extends('layouts.app')
@section('title', 'Modifier utilisateur')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('page-title', 'Modifier ')

@section('content')
<div class="card" style="max-width:640px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nom complet</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $utilisateur->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Matricule</label>
                    <input type="text" name="matricule" class="form-control @error('matricule') is-invalid @enderror"
                           value="{{ old('matricule', $utilisateur->matricule) }}" required>
                    @error('matricule')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Téléphone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $utilisateur->phone) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $utilisateur->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Rôle</label>
                <select name="role" class="form-select" required>
                    <option value="admin"            {{ old('role',$utilisateur->role)=='admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="etudiante"        {{ old('role',$utilisateur->role)=='etudiante' ? 'selected' : '' }}>Étudiante</option>
                    <option value="resp_hebergement" {{ old('role',$utilisateur->role)=='resp_hebergement' ? 'selected' : '' }}>Resp. Hébergement</option>
                    <option value="technicien"       {{ old('role',$utilisateur->role)=='technicien' ? 'selected' : '' }}>Technicien</option>
                    <option value="resp_foyer"       {{ old('role',$utilisateur->role)=='resp_foyer' ? 'selected' : '' }}>Resp. Foyer</option>
                </select>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <label class="form-label fw-semibold">Nouveau mot de passe <span class="text-muted"></span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Confirmer</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
                <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection