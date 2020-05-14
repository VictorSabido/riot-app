<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searcher.css') }}">
    <title>Riot</title>
</head>
<body>
    <div class="browser" id="app">
        <section @click="focusInput">
            <div class="input-field">
                <input type="text" ref="summoner" id="summoner" v-model="summoner" @keyup.enter="sendSummoner" autofocus>
                <label for="last_name">Nombre del invocador</label>
            </div>
            <div class="center">
                <button class="btn waves-effect waves-light" @click="sendSummoner" name="action">Buscar</button>
            </div>
            <div class="center" style="display: none">
                <img src="giphy.gif" alt="" width="100px">
            </div>
        </section>
    </div>
    <script src="{{ asset('js/searcher.js') }}"></script>
    <script>
        $(document).ready(function() {
            M.updateTextFields();
        });
    </script>
</body>
</html>
