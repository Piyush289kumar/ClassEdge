<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdmissionResource\Pages;
use App\Filament\Resources\AdmissionResource\RelationManagers;
use App\Models\Admission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdmissionResource extends Resource
{
    protected static ?string $model = Admission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_id')
                    ->required()
                    ->maxLength(26),
                Forms\Components\TextInput::make('course_id')
                    ->required()
                    ->maxLength(26),
                Forms\Components\TextInput::make('batch_id')
                    ->maxLength(26)
                    ->default(null),
                Forms\Components\DatePicker::make('admitted_on')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('fee_total')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('fee_paid')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('payment_reference')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('course_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admitted_on')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('fee_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fee_paid')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListAdmissions::route('/'),
            'create' => Pages\CreateAdmission::route('/create'),
            'edit' => Pages\EditAdmission::route('/{record}/edit'),
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
