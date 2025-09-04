<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers\SubjectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\StudentsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\AdmissionsRelationManager;
use App\Models\Batch;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Academics';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('code')->required(),
                Forms\Components\Textarea::make('description'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('code'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SubjectsRelationManager::class,
            StudentsRelationManager::class,
            AdmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit'   => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
