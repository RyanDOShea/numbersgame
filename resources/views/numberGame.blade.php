<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">


    <title>Guess the Number Game</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Mukta" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Mukta', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        #app {
            margin: 10px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

    </style>
</head>
<body>

<div id="app">

    <p>
        I am thinking of a number from 1 to 10. <br />
        You must guess what it is in three tries.<br />
        <button type="button" id="reset_button">Reset Game</button>
        <hr>

        <form action="#">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <label>Enter a guess </label>
            <input type="number" id="number_guess" min="1" max="10"><input type="button" value="Guess it" id="guess_button">
        </form>
    </p>



    <ul id="game_guesses">

    </ul>





</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script type="text/javascript">
    $("#guess_button").bind("click", function(){

        var guess = parseInt($("#number_guess").val());

        if(guess > 10 || guess < 1){
            alert('Please Enter a number from 1 to 10');
            $('#number_guess').val('').focus();
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            method: 'POST',
            context: this,
            url: "/makeGuess",
            data: {guess: $("#number_guess").val()},
            dataType: "json",

            success: function(result){
                //do stuff like replacing bits of the text

                $('#game_guesses').append('<li>' + result.outputText +'</li>');
                $('#number_guess').val('').focus();

            },
            error: function(errorMessage){
                //console.log(errorMessage.responseJSON.errorMessage);
                alert(errorMessage.responseJSON.errorMessage);
            },
        });
    });

    $("#reset_button").bind("click", function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            method: 'GET',
            context: this,
            url: "/resetGame",
            data: [],

            success: function(){
                //do stuff like replacing bits of the text
                location.reload(); // reset the game completely

                //if we want to reset the game without reloading the page then we'd have to use JS to clear the guesses
            },
            error: function(){
                //do other stuff
                console.log("no");
            },
        });
    });
</script>

</body>
</html>
