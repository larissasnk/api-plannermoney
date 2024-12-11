<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObjetivoFinanceiroController;
use App\Http\Controllers\Evolucao52SemanasController;
use App\Http\Controllers\Metodo502030Controller;
use App\Http\Controllers\GastoCartaoController;
use App\Http\Controllers\CustoDiarioController;
use App\Http\Controllers\PlanejamentoViagemController;
use App\Http\Controllers\AquisicaoBemController;
use App\Http\Controllers\PlanilhaMercadoController;
use App\Http\Controllers\GastoResidencialController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\GastoFamiliarController;
use App\Http\Controllers\DividaController;
use App\Http\Controllers\InvestimentoController;
use App\Http\Controllers\RelatorioController;

// Rotas de autenticação públicas (não protegidas)
Route::post('logout', [AuthController::class, 'logout']);
Route::post('login', [AuthController::class, 'login']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('register', [AuthController::class, 'register']);

// Rotas protegidas por autenticação
Route::middleware(['auth:api'])->group(function () {
    Route::get('get-user', [AuthController::class, 'getUser']); // Rota de exemplo para obter os dados do usuário logado
    Route::apiResource('gastos-familiares', GastoFamiliarController::class);
    Route::apiResource('aquisicao-bem', AquisicaoBemController::class);
    Route::apiResource('planilha-mercado', PlanilhaMercadoController::class);
    Route::apiResource('objetivo-financeiro', ObjetivoFinanceiroController::class);
    Route::apiResource('evolucao-52-semanas', Evolucao52SemanasController::class);
    Route::apiResource('metodo-502030', Metodo502030Controller::class);
    Route::apiResource('gasto-cartao', GastoCartaoController::class);
    Route::apiResource('custo-diario', CustoDiarioController::class);
    Route::apiResource('planejamento-viagem', PlanejamentoViagemController::class);
    Route::apiResource('gasto-residencial', GastoResidencialController::class);
    Route::apiResource('receita', ReceitaController::class);
    Route::apiResource('transacao', TransacaoController::class);
    Route::apiResource('divida', DividaController::class);
    Route::apiResource('investimentos', InvestimentoController::class);
    Route::get('/relatorio-consolidado', [RelatorioController::class, 'consolidado']);
    Route::put('user', [AuthController::class, 'updateProfile']);

    // Rota protegida para obter o usuário autenticado
    Route::get('user', function (Request $request) {
        return $request->user();
    });
});
