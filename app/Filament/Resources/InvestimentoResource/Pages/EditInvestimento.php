<?php

namespace App\Filament\Resources\InvestimentoResource\Pages;

use App\Filament\Resources\InvestimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestimento extends EditRecord
{
    protected static string $resource = InvestimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
