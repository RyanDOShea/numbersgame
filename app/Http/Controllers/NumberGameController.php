<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumberGameController extends Controller
{

    const VICTORYTEXT = 'Right! You have won the game.';

    const PRETTYNUMBER = [
        '1' => 'first',
        '2' => 'second',
        '3' => 'last'
    ];


    public function startGame(Request $request){

        $currentAttempt = $request->session()->get('attemptsMade',0);

        if($currentAttempt == 0){
            $this->resetGame($request);
        }

        return view('numberGame', ['session' => $request->session()->all()]);

    }

    public function makeGuess(Request $request){

        $guess = $request->input('guess') ?? null;

        if($guess == null){
            return response()->json(['errorMessage' => 'No guess given'],400);
        }

        $attemptsMade = $request->session()->get('attemptsMade') + 1;

        if($attemptsMade > 3){
            return response()->json(['errorMessage' => 'Too Many Guesses'],400);
        }

        $guess = (int)$guess;
        $diff = $guess - $request->session()->get('correctNumber');

        $request->session()->put('attemptsMade', $attemptsMade);


        $outputText = 'Your ' . $this::PRETTYNUMBER[$attemptsMade] . ' guess is: ' . $guess;

        switch ($diff){
            case 0:
                $outputText .= '<br />' . $this::VICTORYTEXT;
                break;
            case 1:
                $outputText .= ' (hot)';
                break;
            case 2:
                $outputText .= ' (warm)';
                break;
            default:
                $outputText .= ' (cold)';
                break;
        }

        $result = collect([
                'outputText' => $outputText,
            ]);

        return response()->json($result);
    }

    public function resetGame(Request $request){

        $request->session()->put('correctNumber', rand(1,10));
        $request->session()->put('attemptsMade', 0);
    }

    public function prettyNumber($number){



    }
}
