<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers\CourseModulesRelationManagerRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\SubjectsRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\BatchesRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\AdmissionsRelationManager;
use App\Models\Course;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Academics';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Course Name')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description'),

                Forms\Components\Select::make('duration')
                    ->label('Duration')
                    ->options([
                        '1 month' => '1 month',
                        '3 month' => '3 month',
                        '6 month' => '6 month',
                        '1 year' => '1 year',
                    ])
                    ->required()
                    ->default('1 month'),

                Forms\Components\TextInput::make('credits')
                    ->label('Credits')
                    ->numeric()
                    ->default(0),

                Forms\Components\Textarea::make('meta')
                    ->label('Additional Info')
                    ->json(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Course Name')->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Description'),                
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
            ]);
        ;
    }

    public static function getRelations(): array
    {
        return [
            CourseModulesRelationManagerRelationManager::class,
            // SubjectsRelationManager::class,
            // BatchesRelationManager::class,
            // AdmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
