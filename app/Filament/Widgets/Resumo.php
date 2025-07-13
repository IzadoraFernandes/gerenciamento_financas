<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class Resumo extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        // Get filters from the request
        $dataInicio = Request::input('tableFilters.data_inicio');
        $dataFinal = Request::input('tableFilters.data_final');
        $name = Request::input('tableFilters.name');

        $query = DB::table('transacaos')
            ->where('id_usuario', $userId);

        // Apply date filters if available
        if ($dataInicio) {
            $query->where('data', '>=', $dataInicio);
        } else {
            $query->whereMonth('data', now()->month);
        }

        if ($dataFinal) {
            $query->where('data', '<=', $dataFinal);
        }

        // Apply name filter if available
        if ($name) {
            $query->where('descricao', 'like', "%{$name}%");
        }

        // Calculate entradas (income)
        $entradas = (float) DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->when($dataInicio, function($query) use ($dataInicio) {
                return $query->where('data', '>=', $dataInicio);
            }, function($query) {
                return $query->whereMonth('data', now()->month);
            })
            ->when($dataFinal, function($query) use ($dataFinal) {
                return $query->where('data', '<=', $dataFinal);
            })
            ->when($name, function($query) use ($name) {
                return $query->where('descricao', 'like', "%{$name}%");
            })
            ->whereRaw("LOWER(tipo) = 'receita'")
            ->sum('valor');

        // Calculate despesas (expenses)
        $despesas = (float) DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->when($dataInicio, function($query) use ($dataInicio) {
                return $query->where('data', '>=', $dataInicio);
            }, function($query) {
                return $query->whereMonth('data', now()->month);
            })
            ->when($dataFinal, function($query) use ($dataFinal) {
                return $query->where('data', '<=', $dataFinal);
            })
            ->when($name, function($query) use ($name) {
                return $query->where('descricao', 'like', "%{$name}%");
            })
            ->whereRaw("LOWER(tipo) = 'despesa'")
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
