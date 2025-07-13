<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Filament\Widgets\Resumo;
use App\Filament\Widgets\AnualGrafico;
use App\Filament\Widgets\TransacaoGrafico;
use App\Filament\Widgets\CategoriaGrafico;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('')->schema([
                TextInput::make('name'),
                DatePicker::make('data_inicio'),
                DatePicker::make('data_final'),

            ])->columns(3)
        ]);
    }

    public function getHeaderWidgets(): array
    {
        return [
            Resumo::class
        ];
    }

    public function getWidgets(): array
    {
        return [
            TransacaoGrafico::class,
            CategoriaGrafico::class,
            AnualGrafico::class,
        ];
    }
}
