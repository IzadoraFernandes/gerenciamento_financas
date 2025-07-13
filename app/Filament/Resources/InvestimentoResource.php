<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestimentoResource\Pages;
use App\Models\Investimento;
use App\Models\TipoInvestimento;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Facades\Filament;

class InvestimentoResource extends Resource
{
    protected static ?string $model = Investimento::class;
    protected static ?string $modelLabel = 'Investimento';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                Select::make('id_tipo_investimento')
                    ->label('Tipo de Investimento')
                    ->relationship('tipoInvestimento', 'tipo')
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('tipo')->required()->label('Novo Tipo'),
                    ])
                    ->createOptionUsing(fn ($data) => TipoInvestimento::create($data))
                    ->required(),

                TextInput::make('instituicao')->required(),
                DatePicker::make('data')
                    ->default(now())
                    ->required(),
                TextInput::make('valor')
                    ->prefix('R$ ')
                    ->numeric()
                    ->required(),
                TextInput::make('rendimento_esperado')
                    ->required()
                    ->label('Rendimento Esperado (%)')
                    ->numeric(),

                Select::make('id_usuario')
                    ->default(fn () => Filament::auth()->user()->id_usuario)
                    ->hidden()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipoInvestimento.tipo')
                    ->label('Tipo de Investimento')
                    ->searchable(),

                TextColumn::make('instituicao')
                    ->label('Instituição')
                    ->searchable(),

                TextColumn::make('data')
                    ->label('Data')
                    ->date()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('rendimento_esperado')
                    ->label('Rendimento Esperado (%)')
                    ->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvestimentos::route('/'),
            'create' => Pages\CreateInvestimento::route('/create'),
            'edit' => Pages\EditInvestimento::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('id_usuario', auth()->id());
    }
}
