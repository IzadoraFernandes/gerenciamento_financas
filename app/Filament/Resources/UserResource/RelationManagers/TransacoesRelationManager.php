<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;

class TransacoesRelationManager extends RelationManager
{
    protected static string $relationship = 'transacoes';
    protected static ?string $title = 'Transações';

    public function form(Form $form): Form

    {
        return $form
            ->schema([
                TextInput::make('descricao')->required()->label('Descrição'),
                TextInput::make('valor')->numeric()->required()->label('Valor'),
                Select::make('tipo')
                    ->options([
                        'receita' => 'Receita',
                        'despesa' => 'Despesa',
                    ])
                    ->required()
                    ->label('Tipo'),
                TextInput::make('categoria')->required()->label('Categoria'),
                DatePicker::make('data')->required()->label('Data'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('descricao')->label('Descrição')->searchable(),
                TextColumn::make('valor')->label('Valor')->money('BRL'),
                TextColumn::make('tipo')->label('Tipo')->badge(),
                TextColumn::make('categoria')->label('Categoria'),
                TextColumn::make('data')->label('Data')->date('d/m/Y'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Nova Transação'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
