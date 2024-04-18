<?php

namespace App\Filament\Resources;

use App\Enums\ReligionStatus;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use App\Models\StudentHasClass;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid as ComponentsGrid;
use Filament\Infolists\Components\Group as ComponentsGroup;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split as ComponentsSplit;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

use function Laravel\Prompts\select;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-group';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nis')
                            ->label('NIS'),
                        TextInput::make('name')
                            ->label('Nama Student')
                            ->required(),
                        Select::make('gender')
                            ->options([
                                "Male" => "Male",
                                "Female" => "Female"
                            ]),
                        DatePicker::make('birthday')
                            ->label('Birthday'),
                        Select::make('religion')
                            ->options(ReligionStatus::class),
                        TextInput::make('contact'),
                        FileUpload::make('profile')
                            ->directory("students")
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        // Explicitly convert string values to integers
                        $recordsPerPage = (int)$livewire->getTableRecordsPerPage();
                        $currentPage = (int)$livewire->getTablePage();

                        return (string) (
                            $rowLoop->iteration +
                            ($recordsPerPage * ($currentPage - 1))
                        );
                    }
                ),

                TextColumn::make('nis')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('gender')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('birthday')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('religion'),
                TextColumn::make('contact'),
                ImageColumn::make('profile'),
                TextColumn::make('status')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state): string => ucwords("{$state}"))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // BulkAction::make('Accept')
                    //     ->icon('heroicon-m-check')
                    //     ->requiresConfirmation()
                    //     ->action(function (Collection $records) {
                    //         return $records->each->update(['status' => 'accept']);
                    //     }),
                    // BulkAction::make('Off')
                    //     ->icon('heroicon-m-x-circle')
                    //     ->requiresConfirmation()
                    //     ->action(function (Collection $records) {
                    //         return $records->each(function ($record) {
                    //             $id = $record->id;
                    //             Student::where('id', $id)->update(['status' => 'of']);
                    //         });
                    //     }),

                    BulkAction::make('Change Status')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('Status')
                                ->label('Status')
                                ->options(['accept' => 'Accept', 'of' => 'Off', 'move' => 'Move', 'grade' => 'Grade'])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                Student::where('id', $record->id)->update(['status' => $data['Status']]);
                            });
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
        // ->headerActions([
        //     Tables\Actions\CreateAction::make(),
        // ]);
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}')
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Fieldset::make('Biodata')
                            ->schema([
                                ComponentsSplit::make([
                                    ImageEntry::make('profile')
                                        ->hiddenLabel()
                                        ->grow(false),
                                    ComponentsGrid::make(2)
                                        ->schema([
                                            ComponentsGroup::make([
                                                TextEntry::make('nis'),
                                                TextEntry::make('name'),
                                                TextEntry::make('gender'),
                                                TextEntry::make('birthday'),

                                            ])
                                                ->inlineLabel()
                                                ->columns(1),

                                            ComponentsGroup::make([
                                                TextEntry::make('religion'),
                                                TextEntry::make('contact'),
                                                TextEntry::make('status')
                                                    ->badge()
                                                    ->color(fn (string $state): string => match ($state) {
                                                        'accept' => 'success',
                                                        'off' => 'danger',
                                                        'grade' => 'success',
                                                        'move' => 'warning',
                                                        'wait' => 'gray'
                                                    }),
                                                ViewEntry::make('QRCode')
                                                    ->view('filament.resources.students.qrcode'),
                                            ])
                                                ->inlineLabel()
                                                ->columns(1),
                                        ])

                                ])->from('lg')
                            ])->columns(1)
                    ])->columns(2)
            ]);
    }
}
