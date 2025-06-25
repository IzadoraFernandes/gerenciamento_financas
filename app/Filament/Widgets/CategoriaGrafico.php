<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategoriaGrafico extends ChartWidget
{
    protected static ?string $heading = 'Gastos por Categoria (Despesas)';

    protected function getData(): array
    {
        $userId = auth()->id();

        $categoria = DB::table('transacaos')
            ->selectRaw('categoria, SUM(valor) as total')
            ->where('id_usuario', $userId)
            ->whereRaw("LOWER(tipo) = 'despesa'")
            ->whereMonth('data', now()->month)
            ->groupBy('categoria')
            ->get();

        $labels = $categoria->pluck('categoria')->toArray();
        $data = $categoria->pluck('total')->toArray();

        return [
            'datasets' => [[
                'data' => $data,
                'backgroundColor' => $this->generateColors(count($data)),
                'label' => 'Despesas por Categoria',
            ]],
            'labels' => $labels,
        ];
    }

    protected function generateColors(int $count): array
    {
        $colors = [
            '#ff7675', '#74b9ff', '#ffeaa7', '#55efc4',
            '#a29bfe', '#fab1a0', '#81ecec', '#fd79a8',
            '#e17055', '#00b894', '#fdcb6e', '#0984e3'
        ];

        return array_slice(array_merge($colors, $colors), 0, $count);
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
