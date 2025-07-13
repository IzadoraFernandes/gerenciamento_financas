<?php

namespace App\Filament\Resources\InvestimentoResource\Pages;

use App\Filament\Resources\InvestimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvestimentos extends ListRecords
{
    protected static string $resource = InvestimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
