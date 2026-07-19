@extends('layouts.app')
@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    @include('partials._notifications-list', ['notifications' => $notifications, 'readRouteName' => 'hebergement.notifications.read'])
</div>
@endsection