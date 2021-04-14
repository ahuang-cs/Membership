@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-lg-8">
                <h1>Hello {{ Auth::user()->Forename }}</h1>
                <p class="lead">Welcome to your {{ tenant('Name') }} account!</p>

                <p>
                    You may notice that things look a little bit different here. We're rewriting the SCDS Membership
                    system from the ground up to better support multi-tenancy and structure the application for future
                    growth.
                </p>

                <p>
                    This system is currently only supporting user authentication. Once you are authenticated you will be
                    handed off to the legacy application for all features.
                </p>
            </div>
        </div>

    </div>
@endsection
