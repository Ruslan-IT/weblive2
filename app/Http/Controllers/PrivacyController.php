<?php


namespace App\Http\Controllers;

use Inertia\Inertia;
class PrivacyController extends Controller
{

    public function index()
    {
        return Inertia::render('Privacy', [
            'title' => 'Политика',
        ]);
    }

}
