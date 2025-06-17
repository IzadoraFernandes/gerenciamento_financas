<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TransacaoGrafico extends ChartWidget
{
    protected static ?string $heading = 'Resumo de Entradas e Saídas';

    protected function getData(): array
    {
        $userId = auth()->id();

        $entradas = (float) DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->whereRaw("LOWER(tipo) = 'receita'")
            ->whereMonth('data', now()->month)
            ->sum('valor');

        $saidas = (float) DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->whereRaw("LOWER(tipo) = 'despesa'")
            ->whereMonth('data', now()->month)
            ->sum('valor');

        return [
            'datasets' => [[
                'data' => [$entradas, $saidas],
                'backgroundColor' => ['#00b894', '#ff7675'],
                'label' => 'Entradas e Saídas',
            ]],
            'labels' => ['Entradas', 'Saídas'],
        ];
    }


    protected function getType(): string
    {
        return 'pie';
    }
}
