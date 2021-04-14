<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ tenant('Name') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<footer>
    <div
        class="cls-global-footer cls-global-footer-inverse cls-global-footer-body d-print-none mt-3 pb-0 focus-highlight">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col">
                            <address>
                                {{--                                <?php $addr = json_decode(app()->tenant->getKey('CLUB_ADDRESS')); ?>--}}
                                {{--                                <strong><?= htmlspecialchars(app()->tenant->getKey('CLUB_NAME')) ?></strong><br>--}}
                                {{--                                <?php if ($addr) {--}}
                                {{--                                for ($i = 0; $i < sizeof($addr); $i++) { ?>--}}
                                {{--                                <?= htmlspecialchars($addr[$i]) ?><br>--}}
                                {{--                                <?php }--}}
                                {{--                                } ?>--}}
                            </address>
                            <p><i class="fa fa-flag fa-fw" aria-hidden="true"></i> <a href="#">Report an issue with this
                                    page</a>
                            </p>
                            <p><i class="fa fa-info-circle fa-fw" aria-hidden="true"></i> <a href="{{ url('/about') }}">Support
                                    information</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-sm-6 col-lg-6">
                            <ul class="list-unstyled cls-global-footer-link-spacer">
                                <li><strong>Membership System Support</strong></li>
                                <li>
                                    <a href="{{ url("privacy") }}" target="_blank"
                                       title="{{ tenant('Name') }} Privacy Policy">
                                        Our Privacy Policy
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('help-and-support') }}" title="Help and Support">
                                        Help and Support
                                    </a>
                                </li>
                                <li>
                                    <a href="https://membership.git.myswimmingclub.uk/whats-new/" target="_blank"
                                       title="New membership system features">
                                        What's new?
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/notify') }}" target="_self"
                                       title="About our Notify Email Service">
                                        Emails from us
                                    </a>
                                </li>
                                <li>
                                    <a href="https://github.com/Chester-le-Street-ASC/Membership" target="_blank"
                                       title="Membership by CLSASC on GitHub">
                                        GitHub
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <ul class="list-unstyled cls-global-footer-link-spacer">
                                <li><strong>Related Sites</strong></li>
                                <li><a title="British Swimming" target="_blank"
                                       href="https://www.swimming.org/britishswimming/">British
                                        Swimming</a></li>
                                <li><a title="the Amateur Swimming Association" target="_blank"
                                       href="https://www.swimming.org/swimengland/">Swim England</a></li>
                                {{--                                <li><a title="<?= htmlspecialchars($districts[app()->tenant->getKey('ASA_DISTRICT')]['title']) ?>" target="_blank" href="<?= htmlspecialchars($districts[app()->tenant->getKey('ASA_DISTRICT')]['website']) ?>"><?= htmlspecialchars($districts[app()->tenant->getKey('ASA_DISTRICT')]['name']) ?></a></li>--}}
                                {{--                                <li><a title="<?= htmlspecialchars($counties[app()->tenant->getKey('ASA_COUNTY')]['title']) ?>" target="_blank" href="<?= htmlspecialchars($counties[app()->tenant->getKey('ASA_COUNTY')]['website']) ?>"><?= htmlspecialchars($counties[app()->tenant->getKey('ASA_COUNTY')]['name']) ?></a></li>--}}
                                @if (false)
                                    {{--                                    <li><a title="<?= htmlspecialchars(app()->tenant->getKey('CLUB_NAME')) ?> Website" target="_blank" href="<?= htmlspecialchars(app()->tenant->getKey('CLUB_WEBSITE')) ?>"><?= htmlspecialchars(app()->tenant->getKey('CLUB_NAME')) ?></a></li>--}}
                                @endif
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cls-global-footer-legal">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-auto source-org vcard copyright">
                    <div class="row no-gutters">
                        <div class="col-auto">
                            <a href="https://myswimmingclub.uk" target="_blank"
                               title="Swimming Club Data Systems Website">
                                <img src="{{ url('/img/corporate/scds.png') }}" width="100">
                            </a>
                            <div class="d-block d-sm-none mb-3"></div>
                        </div>
                    </div>

                </div>
                <div class="col">

                    @php
                        $timeEnd = microtime(true);
                        $seconds = $timeEnd - LARAVEL_START;
                    @endphp
                    <p class="hidden-print mb-1">
                        Membership is designed and built by <a class="text-white" href="https://www.myswimmingclub.uk"
                                                               target="_blank">Swimming Club Data Systems</a>. Licenced
                        to {{ tenant('Name') }}.
                    </p>
                    <p class="mb-1">Page rendered in {{ number_format($seconds, 3) }} seconds. Software version LARAVEL-DEV.
                    </p>
                    <p class="mb-0">
                        &copy; {{ (new DateTime('now', new DateTimeZone('Europe/London')))->format('Y') }} <span
                            class="org fn">Swimming Club Data Systems</span>. Swimming Club Data Systems is not
                        responsible
                        for the content of external sites.
                    </p>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
    <div id="app-js-info" data-root="{{ url('') }}"
         data-check-login-url="{{ url('/check-login.json') }}"
         data-service-worker-url="{{ url('/sw/js') }}"></div>
</footer>

</body>
</html>
