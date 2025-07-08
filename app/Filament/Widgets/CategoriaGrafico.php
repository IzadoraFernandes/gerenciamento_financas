<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Illuminate\Support\Carbon;

class CategoriaGrafico extends ChartWidget
{
    protected static ?string $heading = 'Gastos por Categoria (Despesas)';

    protected function getFormSchema(): array
    {
        return [
            Select::make('mes')
                ->label('Mês')
                ->options($this->getMeses())
                ->default(now()->format('m'))
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateChartData()),
        ];
    }

    protected function getMeses(): array
    {
        return collect(range(1, 12))->mapWithKeys(function ($num) {
            return [
                str_pad($num, 2, '0', STR_PAD_LEFT) => Carbon::create()->month($num)->translatedFormat('F'),
            ];
        })->toArray();
    }

    protected function getData(): array
    {
        $userId = auth()->id();
        $mesSelecionado = $this->filters['mes'] ?? now()->format('m');

        $categoria = DB::table('transacaos')
            ->join('categorias', 'transacaos.id_categoria', '=', 'categorias.id')
            ->selectRaw('categorias.nome as categoria, SUM(transacaos.valor) as total')
            ->where('transacaos.id_usuario', $userId)
            ->whereRaw("LOWER(transacaos.tipo) = 'despesa'")
            ->whereMonth('transacaos.data', $mesSelecionado)
            ->groupBy('categorias.nome')
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
