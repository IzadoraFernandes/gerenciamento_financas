<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransacaoResource\Pages;
use App\Filament\Resources\TransacaoResource\RelationManagers;
use App\Models\Transacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker, Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransacaoResource extends Resource
{
    protected static ?string $model = Transacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('descricao')->required(),
                TextInput::make('valor')->numeric()->required(),
                DatePicker::make('data')->required(),

                Select::make('tipo')
                    ->options([
                        'Receita' => 'Receita',
                        'Despesa' => 'Despesa',
                    ])->required(),

                TextInput::make('categoria')->required(),

                Select::make('id_usuario')
                    ->relationship('users', 'name')->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('descricao')->label('Descrição')->searchable(),
                TextColumn::make('valor')->label('Valor')->searchable() ->sortable(),
                TextColumn::make('data')->label('Data')->searchable() ->sortable(),
                TextColumn::make('tipo')->label('Tipo')->searchable(),
                TextColumn::make('categoria')->label('Categoria')->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransacaos::route('/'),
            'create' => Pages\CreateTransacao::route('/create'),
            'edit' => Pages\EditTransacao::route('/{record}/edit'),
        ];
    }
}
