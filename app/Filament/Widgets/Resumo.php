<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class Resumo extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        $entradas = (float) DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->whereRaw("LOWER(tipo) = 'receita'")
            ->whereMonth('data', now()->month)
            ->sum('valor');

        $despesas = (float) DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->whereRaw("LOWER(tipo) = 'despesa'")
            ->whereMonth('data', now()->month)
            ->sum('valor');

        $saldo = $entradas - $despesas;

        // Condições para destaque
        $entradasColor = $saldo > $despesas ? 'success' : 'default';
        $despesasColor = $saldo <= $despesas ? 'danger' : 'default';


        // Exibir gráfico com destaque
        $entradasStat = Stat::make('Valor de entradas', 'R$ ' . number_format($entradas, 2, ',', '.'))
            ->color($entradasColor);

        if ($entradasColor === 'success') {
            $entradasStat->chart([10, 20, 15, 30, 25]);
        }

        $despesasStat = Stat::make('Valor das despesas', 'R$ ' . number_format($despesas, 2, ',', '.'))
            ->color($despesasColor);

        if ($despesasColor === 'danger') {
            $despesasStat->chart([10, 20, 15, 30, 25]);
        }

        return [
            $entradasStat,
            $despesasStat,
            Stat::make('Saldo final do mês', 'R$ ' . number_format($saldo, 2, ',', '.'))
                ->description($saldo >= 0 ? 'Saldo positivo' : 'Saldo negativo')
                ->color($saldo >= 0 ? 'success' : 'danger'),
        ];

    }
}
