<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AnualGrafico extends ChartWidget
{

    use InteractsWithPageFilters;

    protected static ?string $heading = 'Receitas e Despesas - Por Ano';

    public ?string $filter = null;

    protected function getFilters(): array
    {
        return $this->getAnosDisponiveis();
    }

    protected function getData(): array
    {


        $userId = auth()->id();
        $anoSelecionado = $this->filter ?? now()->year;



        $receitasPorMes = array_fill(1, 12, 0);
        $despesasPorMes = array_fill(1, 12, 0);

        $dados = DB::table('transacaos')
            ->select(DB::raw('EXTRACT(MONTH FROM data) as mes'), 'tipo', DB::raw('SUM(valor) as total'))
            ->where('id_usuario', $userId)
            ->whereYear('data', $anoSelecionado)
            ->groupBy('mes', 'tipo')
            ->get();

        foreach ($dados as $dado) {
            if (strtolower($dado->tipo) === 'receita') {
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

    protected function getAnosDisponiveis(): array
    {
        // busca anos
        $anos = DB::table('transacaos')
            ->where('id_usuario', auth()->id())
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM data) as ano')
            ->orderByDesc('ano')
            ->pluck('ano')
            ->mapWithKeys(fn ($ano) => [(string) $ano => $ano])
            ->toArray();

        // fallback
        if (empty($anos)) {
            $anoAtual = now()->year;
            $anos = [$anoAtual => $anoAtual];
        }

        return $anos;
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
