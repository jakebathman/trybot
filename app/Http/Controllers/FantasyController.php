<?php

namespace App\Http\Controllers;

use App\Services\FantasyFootball;
use Illuminate\Http\Request;

class FantasyController extends Controller
{
    public function updateLeagues()
    {
        $ff = new FantasyFootball;

        $ff->updateAllLeagues();
    }
}
