<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdmissionResource\Pages;
use App\Models\Admission;
use App\Models\Student;
use App\Models\Course;
use App\Models\Batch;
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

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Academics';
    protected static ?string $navigationLabel = 'Admissions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Student
                Forms\Components\Select::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'first_name') // uses relation
                    ->searchable()
                    ->preload()
                    ->required(),

                // Course
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // Batch
                Forms\Components\Select::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\DatePicker::make('admitted_on')
                    ->label('Admission Date')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'admitted'  => 'Admitted',
                        'rejected'  => 'Rejected',
                        'withdrawn' => 'Withdrawn',
                        'completed' => 'Completed',
                    ])
                    ->default('admitted')
                    ->required(),

                Forms\Components\TextInput::make('fee_total')
                    ->label('Total Fee')
                    ->numeric()
                    ->prefix('₹')
                    ->nullable(),

                Forms\Components\TextInput::make('fee_paid')
                    ->label('Fee Paid')
                    ->numeric()
                    ->prefix('₹')
                    ->nullable(),

                Forms\Components\TextInput::make('payment_reference')
                    ->label('Payment Reference')
                    ->maxLength(255)
                    ->nullable(),

                Forms\Components\KeyValue::make('meta')
                    ->label('Additional Info')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.first_name')
                    ->label('Student')
                    ->description(fn ($record) => $record->student->last_name ?? null)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('course.name')
                    ->label('Course')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('batch.name')
                    ->label('Batch')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('admitted_on')
                    ->label('Admission Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'admitted',
                        'danger'  => 'rejected',
                        'secondary' => 'withdrawn',
                        'info'    => 'completed',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('fee_total')
                    ->label('Total Fee')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fee_paid')
                    ->label('Fee Paid')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_reference')
                    ->label('Payment Ref.')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'admitted'  => 'Admitted',
                        'rejected'  => 'Rejected',
                        'withdrawn' => 'Withdrawn',
                        'completed' => 'Completed',
                    ]),
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
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // In future: add PaymentRelationManager, DocumentsRelationManager, etc.
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAdmissions::route('/'),
            'create' => Pages\CreateAdmission::route('/create'),
            'edit'   => Pages\EditAdmission::route('/{record}/edit'),
            // 'view'   => Pages\ViewAdmission::route('/{record}'),
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
