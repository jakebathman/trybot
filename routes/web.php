<?php

use App\Http\Controllers\JsonResponse;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'HomeController@index');

Route::get('ff_test', function () {
    $f = new \App\Services\FantasyFootball(true);
    return $f->updateScheduleItems('111799');
    // return $f->getMatchup('439427',1,3);

    return $f->createNflMatchupImage(true, true);
});

Route::get('mention_test', function () {
    $f = new \App\Http\Controllers\Slack\Slack;

    $m = new \App\Http\Controllers\Slack\Helpers\Message;
    $m->setText('Hi <@U0662EN06|hiderr>');

    $f->postMessage($m, 'G1LKKBAQN');
});


Route::get('twitch/{username}', 'TwitchController@getUserIdFromUserName');
Route::get('twitch', 'TwitchController@getNewlyStartedStreams');
Route::get('twitch_test', 'TwitchController@getStreamers');

Route::get('hfkwbzowbvfjhdhjfuhrb7364828', function () {
    $guesses = \DB::table('guesses')->orderBy('created_at', 'DESC')->get();
    $r       = '<meta name="viewport" content="width=device-width, initial-scale=1"><style>table {border-spacing: 10px;border-collapse: separate;}</style><table>';
    foreach ($guesses as $g) {
        $r .= '<tr>';
        $r .= '<td>' . $g->created_at . '</td>';
        // $r .= '<td>' . $g->ip . '</td>';
        $r .= '<td>' . $g->name . '</td>';
        $r .= '<td>' . $g->guess . '</td>';
        $r .= '</tr>';
    }
    $r .= '</table>';
    return $r;
});

Route::namespace('Admin')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
});

Route::get('test', function (\Illuminate\Http\Request $request) {
    return ':)';
});

Route::get('lenny', function (\Illuminate\Http\Request $request) {
    if (isset($request->response_url)) {
        // Slack request, so instead of returning anything let's post the response to the whole channel
        $sendReply = Curl::to(urldecode($request->response_url))
            ->withData([
                'response_type' => 'in_channel',
                'text'          => ' ',
            ])
            ->asJson()
            ->returnResponseObject()
            ->post();
    } else {
        return '( ͡° ͜ʖ ͡°)';
    }
});

Route::get('who', function () {
    return view('who', ['who' => session('who')]);
});

Route::post('/whom', function (\Illuminate\Http\Request $request) {
    // Save the user's name in session and send them to the home page
    session(['who' => $request->name]);
    if ($request->name) {
        return '1';
    }
    return '0';
});

Route::group([], function () {
    //    Route::get('', function () {
    //        return view('april_fools_2017', ['dev' => false]);
    //    });

    Route::get('game', function () {

        // Check the current state of the guessing
        $blocks         = config('april_fools_2017.codeBlocks');
        $settings       = \DB::table('settings')->get();
        $blocksToReturn = [];
        $blocksUnlocked = 0;

        $fullCodeUnlocked = false;
        foreach ($settings as $k => $v) {
            if ($v->is_unlocked == 1) {
                $blocksUnlocked++;
                $blocksToReturn[$v->block] = $blocks[$v->block];
            }
        }
        if ($blocksUnlocked === 3) {
            $fullCodeUnlocked = true;
        }

        return view('april_fools_2017', ['dev' => true, 'who' => session('who'), 'b' => $blocksToReturn, 'full' => $fullCodeUnlocked]);
    });

    Route::post('/guess', function (\Illuminate\Http\Request $request) {
        $correctKeys = config('april_fools_2017.correctKeys');
        $blocks      = config('april_fools_2017.codeBlocks');

        // Prepare the guess by standardizing it
        $guess      = $request->guess;
        $cleanGuess = trim($guess);
        $cleanGuess = preg_replace('/[^A-Za-z0-9\.]/', '', $cleanGuess);
        $cleanGuess = strtolower($cleanGuess);

        // Log the guess to MySQL
        $name  = session('who');
        $ip    = $request->ip();
        $guess = \App\Guess::create([
            'name'  => $name,
            'guess' => $cleanGuess,
            'ip'    => $ip,
        ]);
        if (empty($guess)) {
            return ['result' => false];
        }

        // Check the current state of the guessing
        $settings         = \DB::table('settings')->get();
        $fullCodeUnlocked = false;
        $blocksToReturn   = [];
        $blocksUnlocked   = 0;

        foreach ($settings as $k => $v) {
            if ($v->is_unlocked == 1) {
                $blocksUnlocked++;
                $blocksToReturn[$v->block] = $blocks[$v->block];
            }
        }

        if ($blocksUnlocked === 3) {
            $fullCodeUnlocked = true;
        }
        $r['correctGuess'] = false;

        $r['alreadyGuessed'] = false;
        // Check their guess
        if (in_array($cleanGuess, array_keys($correctKeys))) {
            // Correct guess!
            $blockNum = $correctKeys[$cleanGuess];
            if (isset($blocksToReturn[$blockNum])) {
                // Already guessed, even though it's correct
                $r['alreadyGuessed'] = true;
            }
            $blocksToReturn[$blockNum] = $blocks[$blockNum];
            $r['correctGuess']         = true;

            if (count($blocksToReturn) == 3) {
                $r['url'] = 'https://account.sonyentertainmentnetwork.com/liquid/cam/account/giftcard/redeem-gift-card-flow.action?voucherCode=9AR97QN3PEMN';
            }

            // Log that this person unlocked this part of the code
            \DB::table('settings')->where('block', '=', $blockNum)->update(['is_unlocked' => 1, 'unlocked_by' => $name]);
        }
        $r['blocks'] = $blocksToReturn;

        return $r;

        return $cleanGuess;
    });
});

/**
 *  Admin stuff
 */

Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'slack'], function () {
        Route::get('/', 'Slack\Slack@getEventTypes');
    });
});

/**
 * API functionality
 */

Route::name('api.')->prefix('api')->group(function () {

    // A learning route for Mostowy
    Route::name('mostowy.')->prefix('mostowy')->group(function () {
        Route::get('/', 'API\Mostowy@index')->name('index');
        Route::get('help', 'API\Mostowy@help')->name('help');
    });

    // api.ai
    Route::name('ai.')->prefix('ai')->group(function () {
        Route::get('query', 'ApiAi@query')->name('query');
    });

    // Slack
    Route::name('slack.')->prefix('slack')->group(function () {

        Route::any('event', 'Slack\Slack@event')->name('event');
        Route::get('emoji', 'Slack\Slack@getEmojiList')->name('emoji');

        // Slack slash commands
        Route::name('slash.')->prefix('slash')->group(function () {
            Route::any('google', 'Slack\Slash@google')->name('google');
            Route::any('twitch', 'Slack\Slash@twitch')->name('twitch');
            Route::any('tz', 'Slack\Slash@tz')->name('tz');
            Route::any('jizzme', 'Slack\Slash@jizzMe')->name('jizzme');
            Route::any('codes', 'Slack\Slash@codes')->name('codes');

            Route::name('fantasy.')->prefix('fantasy')->group(function () {
                Route::any('{command}', 'Slack\FantasyBot@command')->name('command');
            });
        });
    });

    Route::name('google.')->prefix('google')->group(function () {
        Route::any('search/{query}', 'GoogleSearch@search')->name('search');
    });

    // Discord
    Route::name('discord.')->prefix('discord')->group(function () {
        Route::any('create_voice_channel', 'DiscordController@create')->name('create_channel');
    });
});

Auth::routes();

Route::get('home', 'HomeController@index')->name('home');

Route::get('auth/slack', 'Auth\LoginController@redirectToProvider');
Route::get('auth/slack/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('auth/twitch/callback', 'Auth\TwitchOauthController@handleCallback');
