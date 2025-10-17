<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class OfertaController extends Controller
{
    public function index()
    {
        return Inertia::render('Oferta', [
            'title' => 'Публичная оферта',
        ]);
    }
}
