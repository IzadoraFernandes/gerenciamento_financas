<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransacaoGrafico extends ChartWidget
{
    protected static ?string $heading = 'Resumo de Entradas e Saídas';
    public ?string $filter = null;

    protected function getFilters(): array
    {
        return $this->getMesesDisponiveis();
    }

    protected function getFormSchema(): array
    {
        $mesSelecionado = $this->filter ?? now()->month;
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
        $mesSelecionado = $this->filter ?? now()->format('m');

        $entradas = (float) DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->whereRaw("LOWER(tipo) = 'receita'")
            ->whereMonth('data', $mesSelecionado)
            ->sum('valor');

        $saidas = (float) DB::table('transacaos')
            ->where('id_usuario', $userId)
            ->whereRaw("LOWER(tipo) = 'despesa'")
            ->whereMonth('data', $mesSelecionado)
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


    protected function getMesesDisponiveis(): array
    {
        // busca meses
        $meses = DB::table('transacaos')
            ->where('id_usuario', auth()->id())
            ->selectRaw('DISTINCT EXTRACT(MONTH FROM data) as mes')
            ->orderByDesc('mes')
            ->pluck('mes')
            ->mapWithKeys(function ($mes) {
                return [
                    str_pad($mes, 2, '0', STR_PAD_LEFT) => Carbon::create()->month($mes)->translatedFormat('F'),
                ];
            })
            ->toArray();

        if (empty($meses)) {
            $mesAtual = now()->month;
            $meses = [str_pad($mesAtual, 2, '0', STR_PAD_LEFT) => Carbon::create()->month($mesAtual)->translatedFormat('F')];
        }

        return $meses;
    }


    protected function getType(): string
    {
        return 'pie';
    }
}
