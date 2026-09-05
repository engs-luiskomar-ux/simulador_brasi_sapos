<?php

namespace App\Http\Controllers;

use App\Services\ClassificacaoService;
use Illuminate\View\View;

class ClassificacaoController extends Controller
{
    public function index(ClassificacaoService $classificacaoService): View
    {
        $classificacao = $classificacaoService->calcular();

        return view('classificacao.index', compact('classificacao'));
    }
}
