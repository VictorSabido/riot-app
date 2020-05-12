<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> --}}
    <link rel="stylesheet" href="css/app.css">
    <title>Riot</title>
</head>
<body>
    {{-- <img src="Ornn.jpg" id="bg" alt=""> --}}
    <div class="browser" id="app">
        <section>
            {{-- <div class="row">
                <div class="input-field col s6">
                    <input id="name" type="text" class="validate">
                    <label for="name">Nombre del invocador</label>
                </div>
                <div class="input-field col s6">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Buscar!
                    </button>
                </div>
            </div> --}}
            <div class="input-field">
                <input id="last_name" type="text" class="validate">
                <label for="last_name">Nombre del invocador</label>
            </div>
            <div class="center">
                <button class="btn waves-effect waves-light" type="submit" name="action">Buscar</button>
            </div>
            {{-- <div class="center">
                <img src="giphy.gif" alt="" width="100px">
            </div> --}}
        </section>
        
    </div>
    {{-- <script src="js/app.js"></script> --}}
    <script src="js/app.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            M.updateTextFields();
        });
    </script>
</body>
</html>
