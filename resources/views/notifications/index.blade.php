@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title=">Notifications<a href="#" class="btn btn-sm brand-bg-color float-right" >Mark all as read</a></h6>
                    </div>
                    <div class="card-body">
                        @if(!empty($notifications))
                        <ul class="list-group text-decoration-none">
                            @foreach($notifications as $notification)
                                <li class="list-group-item">
                                    <a href="#" class="text-decoration-none text-black">
                                        <div class="media">
                                            <img src="{{ url('images/profile/'.($notification->sentBy->profile_image !== null ? $notification->sentBy->profile_image : 'placeholder.jpg')) }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                            <div class="media-body">
                                                <h3 class="dropdown-item-title">
                                                    {{ $notification->sentBy->name }}
                                                    <span class="float-right text-sm {{ $notification->is_read ? "text-secondary" : "text-success" }}"><i class="fas fa-star"></i></span>
                                                </h3>
                                                <h6><b>{{ $notification->title }}</b></h6>
                                                <p class="text-sm">{{ $notification->message }}</p>
                                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <hr>
                            @endforeach
                        </ul>
                        @else
                            <p>No notifications found</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
