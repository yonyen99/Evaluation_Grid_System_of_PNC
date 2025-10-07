
@extends('layout.app')
@section('page_title', 'detail User')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/user.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">User Details</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Profile Image -->
                <div class="col-md-4 text-center">
                    <img src="{{ asset('storage/' . $user->profile) }}" 
                        class="rounded-circle mb-3" width="150" height="150" alt="Profile">
                </div>

                <!-- User Info -->
                <div class="col-md-8">
                    <h4 class="mb-2 text-bold">{{ ucwords($user->firstname) }} {{ ucwords($user->lastname) }}</h4>
                    <p class="mb-1"><strong>Username:</strong> {{ $user->username }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Role:</strong> 
                        {{ $user->roles()->exists() ? ucwords($user->roles()->first()->name) : 'No Role' }}
                    </p>
                    <p class="mb-0"><strong>Registered On:</strong> {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

@endsection