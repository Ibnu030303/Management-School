<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdjancencyResource\Pages;
use App\Filament\Resources\AdjancencyResource\RelationManagers;
use App\Models\Adjacency;
use App\Models\Adjancency;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\View\View;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class AdjancencyResource extends Resource
{
    protected static ?string $model = Adjacency::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                AdjacencyList::make('subjects')
                    ->form([
                        Forms\Components\TextInput::make('label')
                            ->required(),
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->modalContent(fn(Adjacency $record): View => view('filament.pages.actions.adjacency', ['record' => $record]))
                    ->modalSubmitAction(false),
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
            'index' => Pages\ListAdjancencies::route('/'),
            'create' => Pages\CreateAdjancency::route('/create'),
            'edit' => Pages\EditAdjancency::route('/{record}/edit'),
        ];
    }
}