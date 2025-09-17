<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseModulesRelationManagerRelationManager extends RelationManager
{
    // protected static string $relationship = 'CourseModulesRelationManager';
    protected static string $relationship = 'modules'; // This should match the relation method name in Course model

    protected static ?string $recordTitleAttribute = 'title'; // Use 'title' as the display name


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Module Title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Description'),

                Forms\Components\Repeater::make('resources')
                    ->label('Resources')
                    ->schema([
                        Forms\Components\FileUpload::make('file')
                            ->label('File')
                            ->directory('course-resources')
                            ->required(),
                        Forms\Components\TextInput::make('type')
                            ->label('Type')
                            ->required()
                            ->helperText('Specify the type: image, video, pdf, etc.'),
                    ])
                    ->columns(2)
                    ->createItemButtonLabel('Add Resource'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
