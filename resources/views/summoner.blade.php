<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <title>Starter Template - Materialize</title>
    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<nav class="purple lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">Logo</a>
        <ul class="right hide-on-med-and-down">
            <li><a href="{{ route('searcher') }}">Volver atrás</a></li>
        </ul>
        <ul id="nav-mobile" class="sidenav">
            <li><a href="#">Navbar Link</a></li>
        </ul>
        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
</nav>

<div class="section no-pad-bot" id="app">
    <div class="container">
        <div class="row">
            <div class="col s12 m4 l4">
                <div class="card blue-grey lighten-5">
                    <div class="card-content black-text">
                        <span class="card-title center"><strong>{{ $summ->name }}</strong></span>
                        <div class="center profile">
                            <div class="profileIconDiv">
                                <span class="level">150</span>
                                <img src="{{ asset('storage/profile_icons/'.$summ->profileIconId.'.png') }}" alt="Profile Icon" class="profileIcon">
                            </div>
                        </div>
                        <a class="waves-effect waves-light btn"><i class="material-icons right">cloud</i>button</a>

                    </div>
                </div>
            </div>
            @foreach ($summ->leagues as $league)
            {{-- {{ dd($league) }} --}}
                <top-card
                    col="s12 m4 l4"
                    league="{{ $league->queueType }}"
                    level="{{ $league->rank }}"
                    image="{{ asset('images/leagues/'.$league->tier.'_'.$league->rank.'.png') }}"
                    league-points="{{ $league->leaguePoints }}"
                ></top-card>
            @endforeach

            {{-- <div class="col s12 m4 l4">
                <div class="card blue-grey lighten-5">
                    <div class="card-content black-text">
                        <span class="card-title center"><strong>Ranked Solo</strong></span>
                        <div class="profile">
                            <div class="profileIconDiv">
                                <span class="level">{{ $soloq->rank }}</span>
                                <img src="{{ asset('images/leagues/'.$soloq->tier.'_'.$soloq->rank.'.png') }}" alt="Profile Icon" class="profileIcon">
                            </div>
                        </div>
                        <p>{{ $soloq->leaguePoints }} LPs</p>
                        <p>W {{ $soloq->wins }} / L {{ $soloq->losses }}</p>
                        <p>WinRate {{ $soloqWinRatio }}%</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m4 l4">
                <div class="card blue-grey lighten-5">
                    <div class="card-content black-text">
                        <span class="card-title center"><strong>Ranked Flex</strong></span>
                        <div class="profile">
                            <div class="profileIconDiv">
                                <span class="level">{{ $flex->rank }}</span>
                                <img src="{{ asset('images/leagues/'.$flex->tier.'_'.$flex->rank.'.png') }}" alt="Profile Icon" class="profileIcon">
                            </div>
                        </div>
                        <div>
                            <p>{{ $flex->leaguePoints }} LPs</p>
                            <p>W {{ $flex->wins }} / L {{ $flex->losses }}</p>
                            <p>WinRate {{ $flexWinRatio }}%</p>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>


<div class="container">

</div>

<footer class="page-footer purple lighten-1">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Company Bio</h5>
                <p class="grey-text text-lighten-4">We are a team of college students working on this project like it's our full time job. Any amount would help support and continue development on this project and is greatly appreciated.</p>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Settings</h5>
                <ul>
                    <li><a class="white-text" href="#!">Link 1</a></li>
                    <li><a class="white-text" href="#!">Link 2</a></li>
                    <li><a class="white-text" href="#!">Link 3</a></li>
                    <li><a class="white-text" href="#!">Link 4</a></li>
                </ul>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Connect</h5>
                <ul>
                    <li><a class="white-text" href="#!">Link 1</a></li>
                    <li><a class="white-text" href="#!">Link 2</a></li>
                    <li><a class="white-text" href="#!">Link 3</a></li>
                    <li><a class="white-text" href="#!">Link 4</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            ©Copyright 2020 <strong>Víctor Sabido</strong> All Rights Reserved
        </div>
    </div>
</footer>

  <!--  Scripts-->
  <script src="{{ asset('js/summoner.js') }}"></script>

  </body>
</html>
