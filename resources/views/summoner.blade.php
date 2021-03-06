<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <title>Starter Template - Materialize</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<nav class="purple lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">League of legends</a>
        <ul class="right hide-on-med-and-down">
            <li><a href="{{ route('searcher') }}">Volver atrás</a></li>
        </ul>
        <ul id="nav-mobile" class="sidenav">
            <li><a href="#">Navbar Link</a></li>
        </ul>
        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
</nav>
<div id="navbar" class="center">
    <span>Updated at {{ $summ->updated_at->format('H:i d/m/Y ') }}</span>
</div>

<div class="section no-pad-bot" id="app">
    <div class="container">
        <div class="row">
            <div class="col s12 m4 l4">
                <div class="card blue-grey lighten-5">
                    <div class="refresh" @click="updateSummoner">
                        <form id="updateSummoner" action="{{ route('summUpdate', $summ->name) }}" method="post">
                            @csrf
                            <a class="btn-floating button-refresh purple lighten-1 noselect"><i class="material-icons" ref="refresh">refresh</i></a>
                        </form>
                    </div>
                    <div class="card-content black-text">
                        <span class="card-title center"><strong>{{ $summ->name }}</strong></span>
                        <div class="center profile">
                            <div class="profileIconDiv">
                                <span class="level">{{ $summ->summonerLevel }}</span>
                                <img src="{{ asset('storage/profile_icons/'.$summ->profileIconId.'.png') }}" alt="Profile Icon" class="profileIcon">
                            </div>
                        </div>
                        <div class="center">
                            @for ($i = 0; $i < 6; $i++)
                                <img src="{{ asset('storage/champions/'.$summ->masteries[$i]->getChampionImage()) }}" class="masteries tooltipped" data-position="bottom" data-tooltip="Lvl {{ $summ->masteries[$i]->championLevel }} <br>{{ number_format($summ->masteries[$i]->championPoints, 0, ',', '.') }}" alt="{{ $summ->masteries[$i]->getChampionImage() }}" >
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($summ->leagues as $league)
                <top-card
                    col="s12 m4 l4"
                    :league="{{ $league }}"
                    image="{{ asset('images/leagues/'.$league->tier.'_'.$league->rank.'.png') }}"
                ></top-card>
            @endforeach
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <h4 class="center">Historial</h4>
                <hr>
            </div>
            @foreach ($matchs as $match)
                <div class="col s12 m12 l12">
                    <div class="card blue-grey history {{ $match['bgColor'] }}">
                        <div class="card-content black-text">
                            <div class="row">
                                <div class="col s4 m4 l4">
                                    <div class="profile prof-history">
                                        <div class="profileIconDiv">
                                            <span class="level">{{ $match['stats']['champLevel'] }}</span>
                                            <img src="{{ asset('storage/champions/'.$match['champion']) }}" alt="{{ $match['champion'] }}" class="profileIcon">
                                        </div>
                                        <span class="center"><strong>{{ $match['gameDuration'] }}</strong></span>
                                    </div>
                                </div>
                                <div class="col s3 m3 l3">
                                    <div>
                                        <p class="center">KDA</p>
                                        <p class="center">
                                            <strong>{{ $match['stats']['kills'] }}</strong> / 
                                            <span>{{ $match['stats']['deaths'] }}</span> / 
                                            <span>{{ $match['stats']['assists'] }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col s5 m5 l5">
                                    <div class="champions">
                                        @foreach ($match['teams'] as $team)
                                            @foreach ($team as $participant)
                                            <div class="champion">
                                                <a href="{{ route('summInfo', $participant['summonerName']) }}">
                                                    <img src="{{ asset('storage/champions/'.$participant['championImage']) }}" class="masteries tooltipped" data-position="bottom" data-tooltip="{{ $participant['championName'] }}" alt="" >
                                                    <span class="summonerName">{{ $participant['summonerName'] }}</span>
                                                </a>
                                            </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<footer class="page-footer purple lighten-1">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Company Bio</h5>
                <p class="grey-text text-lighten-4">We are a team of college students working on this project like it's our full time job. Any amount would help support and continue development on this project and is greatly appreciated.</p>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Últimos buscados</h5>
                <ul>
                    @foreach ($history as $h)
                        <li><a class="white-text" href="{{ route('summInfo', $h->name) }}">{{ $h->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Records

                </h5>
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

<script src="{{ asset('js/summoner.js') }}"></script>
</body>
</html>
