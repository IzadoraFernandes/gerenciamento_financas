<?php


namespace App\Filament\Widgets;


use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;


class AnualGrafico extends ChartWidget
{

    protected static ?string $heading = 'Receitas e Despesas - Ano Atual';


    protected function getData(): array
    {
        $userId = auth()->id();
        $anoAtual = now()->year;


        $receitasPorMes = array_fill(1, 12, 0);
        $despesasPorMes = array_fill(1, 12, 0);


        $dados = DB::table('transacaos')
            ->select(DB::raw('EXTRACT(MONTH FROM data) as mes'), 'tipo', DB::raw('SUM(valor) as total'))
            ->where('id_usuario', $userId)
            ->whereYear('data', $anoAtual)
            ->groupBy('mes', 'tipo')
            ->get();


        foreach ($dados as $dado) {
            if ($dado->tipo === 'Receita') {
                $receitasPorMes[$dado->mes] = $dado->total;
            } else {
                $despesasPorMes[$dado->mes] = $dado->total;
            }
        }


        return [
            'datasets' => [
                [
                    'label' => 'Receitas',
                    'data' => array_values($receitasPorMes),
                    'borderColor' => '#00b894',
                    'fill' => false,
                ],
                [
                    'label' => 'Despesas',
                    'data' => array_values($despesasPorMes),
                    'borderColor' => '#ff7675',
                    'fill' => false,
                ],
            ],
            'labels' => [
                'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
                'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez',
            ],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
