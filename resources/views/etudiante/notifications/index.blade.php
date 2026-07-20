@extends('layouts.app')
@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    @include('partials._notifications-list', [
        'notifications' => $notifications,
        'readRouteName' => 'etudiante.notifications.read'
    ])
</div>
@endsection