<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
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
        $userId = Auth::id();
        $anoSelecionado = $this->filter ?? now()->format('Y');

        // Get filters from the request
        $dataInicio = Request::input('tableFilters.data_inicio');
        $dataFinal = Request::input('tableFilters.data_final');
        $name = Request::input('tableFilters.name');

        $receitasPorMes = array_fill(1, 12, 0);
        $despesasPorMes = array_fill(1, 12, 0);

        $query = DB::table('transacaos')
            ->select(DB::raw('EXTRACT(MONTH FROM data) as mes'), 'tipo', DB::raw('SUM(valor) as total'))
            ->where('id_usuario', $userId);

        // Apply year filter if no specific date range is provided
        if (!$dataInicio && !$dataFinal) {
            $query->whereYear('data', $anoSelecionado);
        }

        // Apply date filters if available
        if ($dataInicio) {
            $query->where('data', '>=', $dataInicio);
        }

        if ($dataFinal) {
            $query->where('data', '<=', $dataFinal);
        }

        // Apply name filter if available
        if ($name) {
            $query->where('descricao', 'like', "%{$name}%");
        }

        $dados = $query->groupBy('mes', 'tipo')->get();

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
