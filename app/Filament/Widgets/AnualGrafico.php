<?php

namespace App\Filament\Widgets;

use Filament\Forms;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AnualGrafico extends ChartWidget
{
    protected static ?string $heading = 'Receitas e Despesas - Por Ano';

    // Define um formulário de filtro com um select de anos
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('ano')
                ->label('Ano')
                ->options($this->getAnosDisponiveis())
                ->default(now()->year)
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateChartData()), // atualiza o gráfico ao mudar o ano
        ];
    }

    protected function getAnosDisponiveis(): array
    {
        $userId = auth()->id();

        return DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM data) as ano')
            ->orderByDesc('ano')
            ->pluck('ano', 'ano')
            ->toArray();
    }

    protected function getData(): array
    {
        $userId = auth()->id();
        $anoSelecionado = $this->filterFormData['ano'] ?? now()->year;

        $receitasPorMes = array_fill(1, 12, 0);
        $despesasPorMes = array_fill(1, 12, 0);

        $dados = DB::table('transacaos')
            ->select(DB::raw('EXTRACT(MONTH FROM data) as mes'), 'tipo', DB::raw('SUM(valor) as total'))
            ->where('id_usuario', $userId)
            ->whereYear('data', $anoSelecionado)
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
