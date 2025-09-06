<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeeStructureResource\Pages;
use App\Models\FeeStructure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeeStructureResource extends Resource
{
    protected static ?string $model = FeeStructure::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Academics';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('course_id')
                ->relationship('course', 'name')
                ->searchable()
                ->nullable(),

            Forms\Components\Select::make('batch_id')
                ->relationship('batch', 'code')
                ->searchable()
                ->nullable(),

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('due_date'),

            Forms\Components\Toggle::make('is_recurring'),
            Forms\Components\Select::make('frequency')
                ->options([
                    'monthly' => 'Monthly',
                    'quarterly' => 'Quarterly',
                    'yearly' => 'Yearly',
                ])
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('amount')->money('INR', true)->sortable(),
            Tables\Columns\TextColumn::make('due_date')->date(),
            Tables\Columns\IconColumn::make('is_recurring')->boolean(),
            Tables\Columns\TextColumn::make('frequency'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])
        ->actions([Tables\Actions\EditAction::make()])
        ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeeStructures::route('/'),
            'create' => Pages\CreateFeeStructure::route('/create'),
            'edit' => Pages\EditFeeStructure::route('/{record}/edit'),
        ];
    }
}
