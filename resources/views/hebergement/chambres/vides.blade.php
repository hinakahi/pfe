@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chambres Vides</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Type</th>
                <th>Bloc</th>
                <th>Étage</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chambres as $chambre)
            <tr>
                <td>{{ $chambre->numero }}</td>
                <td>{{ $chambre->type }}</td>
                <td>{{ $chambre->bloc }}</td>
                <td>{{ $chambre->etage }}</td>
                <td>Disponible</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection