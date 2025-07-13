<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransacaoResource\Pages;
use App\Models\Transacao;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker, Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;


class TransacaoResource extends Resource
{
    protected static ?string $model = Transacao::class;
    protected static ?string $modelLabel = 'Transações';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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

                Select::make('id_categoria')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('nome')->required()->label('Nova Categoria'),
                    ])
                    ->createOptionUsing(fn ($data) => \App\Models\Categoria::create($data)->id_categoria)
                    ->required(),


                Hidden::make('id_usuario')
                    ->default(fn () => Filament::auth()->user()->id_usuario),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable(),

                TextColumn::make('valor')
                    ->label('Valor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('data')
                    ->label('Data')
                    ->date()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->searchable(),

                TextColumn::make('categoria.nome')
                    ->label('Categoria')
                    ->searchable(),

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id_usuario', auth()->id());
    }
}
