<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuardianResource\Pages;
use App\Models\Guardian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuardianResource extends Resource
{
    protected static ?string $model = Guardian::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Academics';
    protected static ?string $navigationLabel = 'Guardians';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Guardian Details')
                    ->description('Basic information about the guardian')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->label('First Name')
                            ->placeholder('Enter first name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->placeholder('Enter last name')
                            ->maxLength(255),

                        Forms\Components\Select::make('relation')
                            ->options([
                                'father' => 'Father',
                                'mother' => 'Mother',
                                'brother' => 'Brother',
                                'sister' => 'Sister',
                                'guardian' => 'Guardian',
                                'other' => 'Other',
                            ])
                            ->label('Relation')
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select relation'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->placeholder('example@email.com')
                            ->label('Email'),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->placeholder('+91 9876543210')
                            ->label('Phone'),

                        Forms\Components\TextInput::make('occupation')
                            ->placeholder('E.g., Engineer')
                            ->label('Occupation'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Address Information')
                    ->schema([
                        Forms\Components\TextInput::make('address_line1')
                            ->label('Address Line 1')
                            ->placeholder('Street, Area'),

                        Forms\Components\TextInput::make('address_line2')
                            ->label('Address Line 2')
                            ->placeholder('Apartment, Landmark'),

                        Forms\Components\TextInput::make('city')
                            ->label('City'),

                        Forms\Components\TextInput::make('state')
                            ->label('State'),

                        Forms\Components\TextInput::make('country')
                            ->label('Country')
                            ->default('IN'),

                        Forms\Components\TextInput::make('zip')
                            ->label('ZIP Code'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\KeyValue::make('meta')
                            ->label('Metadata')
                            ->addButtonLabel('Add Info')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('relation')
                    ->colors(fn(string $state): string => match ($state) {
                        'father' => 'primary',
                        'mother' => 'success',
                        'guardian' => 'warning',
                        'brother' => 'info',
                        'sister' => 'indigo',
                        default => 'secondary',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->icon('heroicon-o-phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-o-envelope')
                    ->searchable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('City')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added On')
                    ->date('d M, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('relation')
                    ->options([
                        'father' => 'Father',
                        'mother' => 'Mother',
                        'brother' => 'Brother',
                        'sister' => 'Sister',
                        'guardian' => 'Guardian',
                        'other' => 'Other',
                    ])
                    ->label('Relation'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuardians::route('/'),
            'create' => Pages\CreateGuardian::route('/create'),
            'edit' => Pages\EditGuardian::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
