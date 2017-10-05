<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NumberGameController extends Controller
{

    const VICTORY_TEXT = 'Right! You have won the game.';
    const GAME_OVER_TEXT = 'Sorry, you lose. The correct number was ';

    const PRETTY_NUMBER = [
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

        $validator = Validator::make($request->all(), [
            'guess' => 'required|integer|between:0,11'
        ]);

        if($validator->fails()){
            return response()->json([
                'errorMessage' => 'Please Enter a number from 1 to 10'
            ],400);
        }

        $guess = $request->input('guess') ?? null;

        $attemptsMade = $request->session()->get('attemptsMade') + 1;

        if($attemptsMade > 3){
            return response()->json(['errorMessage' => 'Too Many Guesses'],400);
        }

        $request->session()->put('attemptsMade', $attemptsMade);

        $result = $this->processGameState($request, $guess, $attemptsMade);

        return response()->json($result);
    }

    /**
     * @param $request
     * @param $guess
     * @param $attemptsMade
     * @return \Illuminate\Support\Collection
     *
     * Logic to take game input, and generate the output to return to user.
     */
    public function processGameState($request, $guess, $attemptsMade){

        $result = collect();

        $guess = (int)$guess;
        $diff = abs($guess - $request->session()->get('correctNumber'));

        $outputText = 'Your ' . $this::PRETTY_NUMBER[$attemptsMade] . ' guess is: ' . $guess;

        switch ($diff){
            case 0:
                $outputText .= '<br />' . $this::VICTORY_TEXT;
                $result['victoryFlag'] = true;
                $request->session()->put('gameFinishFlag', true);
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

        if($diff != 0 && $attemptsMade >= 3){
            $outputText .= '<br />' . $this::GAME_OVER_TEXT . $request->session()->get('correctNumber');
            $result['gameOverFlag'] = true;
            $request->session()->put('gameFinishFlag', true);
        }

        $pastGuesses = $request->session()->get('pastGuesses',[]);
        array_push($pastGuesses, $outputText);
        $request->session()->put('pastGuesses', $pastGuesses);

        $result['outputText'] = $outputText;

        return $result;
    }

    public function resetGame(Request $request){

        $request->session()->put('correctNumber', rand(1,10));
        $request->session()->put('attemptsMade', 0);
        $request->session()->put('pastGuesses', []);
        $request->session()->put('gameFinishFlag', false);
    }

}
