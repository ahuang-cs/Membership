@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form class="mt-8 space-y-6" action="#" method="POST">
                            <input type="hidden" name="remember" value="true">
                            @csrf
                            <div class="form-group">
                                <label for="email-address">Email address</label>
                                <input id="email-address" name="email" type="email" autocomplete="email" required
                                       class="form-control"
                                       placeholder="Email address">
                            </div>
                            <div class="form-group">
                                <label for="password" class="sr-only">Password</label>
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                       required
                                       class="form-control"
                                       placeholder="Password">
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember_me" name="remember_me" type="checkbox"
                                           class="">
                                    <label for="remember_me" class="">
                                        Remember me
                                    </label>
                                </div>

                                <div class="text-sm">
                                    <a href="#" class="">
                                        Forgot your password?
                                    </a>
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary">
                                    Sign in
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
