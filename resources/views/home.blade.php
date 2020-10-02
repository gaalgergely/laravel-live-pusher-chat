@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="displayuser-wrapper">
                <ul class="displayusers">

                    @foreach($users as $user)
                    <li class="oneuser" id="{{ $user->id }}">

                        @if($user->unread)
                            <span class="pendingmessages">{{ $user->unread }}</span>
                        @endif

                        <div class="starmedia">
                            <div class="starmedia-left">
                                <img src="{{ $user->image }}" alt="" class="media-objects">
                            </div>
                            <div class="starmedia-body">
                                <p class="name">{{ $user->name }}</p>
                                <p class="email">{{ $user->email }}</p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-8">

            <div id="communicationmessages"></div>


            <div class="text-input">
                <input type="text" name="typemessage" class="submit">
            </div>
        </div>

    </div>
</div>
@endsection
