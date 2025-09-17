<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseModuleResource\Pages;
use App\Filament\Resources\CourseModuleResource\RelationManagers;
use App\Models\CourseModule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseModuleResource extends Resource
{
    protected static ?string $model = CourseModule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Academics';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name')
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('Module Title')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description'),

                Forms\Components\Repeater::make('resources')
                    ->label('Resources')
                    ->schema([
                        Forms\Components\FileUpload::make('file')
                            ->label('File')
                            ->directory('course-resources'),
                        Forms\Components\TextInput::make('type')
                            ->label('Type')
                            ->helperText('Enter type like image, video, pdf'),
                    ])
                    ->columns(2)
                    ->createItemButtonLabel('Add Resource'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('title')->label('Title')->searchable(),
                Tables\Columns\TextColumn::make('course.name')->label('Course'),
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
            'index' => Pages\ListCourseModules::route('/'),
            'create' => Pages\CreateCourseModule::route('/create'),
            'edit' => Pages\EditCourseModule::route('/{record}/edit'),
        ];
    }
}
