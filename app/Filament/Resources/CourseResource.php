<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers\SubjectsRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\BatchesRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\AdmissionsRelationManager;
use App\Models\Course;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Academics';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('code'),
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
            BatchesRelationManager::class,
            AdmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit'   => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
