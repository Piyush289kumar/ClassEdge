<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdmissionResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers\GuardiansRelationManager;
use App\Models\Admission;
use App\Models\Student;
use App\Models\Course;
use App\Models\Batch;
use Filament\Forms;
use Filament\Forms\Components\Grid;
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
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('first_name')
                                ->label('First Name')
                                ->required()
                                ->placeholder('Enter first name'),

                            Forms\Components\TextInput::make('last_name')
                                ->label('Last Name')
                                ->required()
                                ->placeholder('Enter last name'),
                        ]),

                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->placeholder('example@example.com'),

                            Forms\Components\TextInput::make('mobile_number')
                                ->label('Mobile Number')
                                ->required()
                                ->placeholder('Enter mobile number'),

                            Forms\Components\Select::make('gender')
                                ->label('Gender')
                                ->options([
                                    'Male' => 'Male',
                                    'Female' => 'Female',
                                    'Other' => 'Other',
                                ])
                                ->required()
                                ->placeholder('Select gender'),

                            Forms\Components\DatePicker::make('dob')
                                ->label('Date of Birth')
                                ->required()
                                ->placeholder('Select date of birth'),
                            Forms\Components\Select::make('guardian_id')
                                ->label('Guardian')
                                ->relationship('guardian', 'first_name')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('first_name')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('last_name')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('phone')
                                        ->maxLength(20),
                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('occupation')
                                        ->maxLength(255),
                                ])
                                ->placeholder('Select or create guardian'),

                        ]),




                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->placeholder('Enter address'),
                    ]),

                Forms\Components\Section::make('Academic Details')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('course_id')
                                ->label('Course')
                                ->relationship('course', 'name')
                                ->required()
                                ->placeholder('Select course'),

                            Forms\Components\Select::make('course_duration')
                                ->label('Course Duration')
                                ->options([
                                    '3 month' => '3-Month Beginner',
                                    '6 month' => '6-Month Advanced',
                                    '1 year' => '1-Year Mastery',
                                ])
                                ->required()
                                ->default('3 month'),

                            Forms\Components\Select::make('batch_id')
                                ->label('Batch')
                                ->relationship('batch', 'name')
                                ->required()
                                ->placeholder('Select batch'),


                            Forms\Components\Select::make('store_id')
                                ->label('Branch')
                                ->relationship('store', 'name')
                                ->required()
                                ->placeholder('Select branch'),

                            Forms\Components\Select::make('occupation')
                                ->label('Occupation')
                                ->options([
                                    'Job' => 'Job',
                                    'Business' => 'Business',
                                    'Self Employed' => 'Self Employed',
                                    'Student' => 'Student',
                                    'Other' => 'Other',
                                ])
                                ->required()
                                ->placeholder('Select occupation'),
                        ]),

                        Forms\Components\CheckboxList::make('class_days')
                            ->label('Class Days')
                            ->options([
                                'Monday' => 'Monday',
                                'Tuesday' => 'Tuesday',
                                'Wednesday' => 'Wednesday',
                                'Thursday' => 'Thursday',
                                'Friday' => 'Friday',
                                'Saturday' => 'Saturday',
                            ])
                            ->columns(6)  // Display all 6 options in one row
                            ->required()
                            ->helperText('Select exactly 2 days')
                            ->rule(['array', 'min:2', 'max:2'])
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (count($state ?? []) > 2) {
                                    array_pop($state);
                                    $set('class_days', $state);
                                }
                            }),
                    ]),

                Forms\Components\Section::make('Admission Details')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('admission_date')
                                ->label('Admission Date')
                                ->default(now())
                                ->required()
                                ->hidden()
                                ->placeholder('Select admission date'),

                            Forms\Components\DatePicker::make('admitted_on')
                                ->label('Admitted On')
                                ->required()
                                ->default(now())
                                ->placeholder('Select admitted date'),

                            Forms\Components\Select::make('payment_method')
                                ->label('Payment Method')
                                ->options([
                                    'Cash' => 'Cash',
                                    'UPI' => 'UPI',
                                    'Other' => 'Other',
                                ])
                                ->required()
                                ->default('Cash')
                                ->placeholder('Select payment method'),

                            Forms\Components\Toggle::make('fee_submitted')
                                ->required()
                                ->label('Fee Submitted'),

                            Forms\Components\TextInput::make('payment_reference')
                                ->label('Payment Reference')
                                ->placeholder('Enter payment reference'),

                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->required()
                                ->options([
                                    'pending' => 'Pending',
                                    'admitted' => 'Admitted',
                                    'rejected' => 'Rejected',
                                    'withdrawn' => 'Withdrawn',
                                    'completed' => 'Completed',
                                ])
                                ->default('pending'),

                            Forms\Components\CheckboxList::make('heard_about')
                                ->label('How did you hear about the class?')
                                ->options([
                                    'Google' => 'Google',
                                    'Social Media' => 'Social Media',
                                    'Reference' => 'Reference',
                                    'Other' => 'Other',
                                ])
                                ->required()
                                ->helperText('Select all that apply'),
                        ]),

                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\FileUpload::make('photo_path')
                            ->label('Photo')
                            ->image(),

                        Forms\Components\KeyValue::make('meta')
                            ->label('Additional Info')
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('first_name')->sortable(),
                Tables\Columns\TextColumn::make('mobile_number'),
                Tables\Columns\TextColumn::make('batch.name')->label('Batch'),
                Tables\Columns\TextColumn::make('course.name')->label('Course'),
                Tables\Columns\TextColumn::make('store.name')->label('Store'),
                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\TextColumn::make('admitted_on')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'admitted' => 'Admitted',
                        'rejected' => 'Rejected',
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
            // GuardiansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmissions::route('/'),
            'create' => Pages\CreateAdmission::route('/create'),
            'edit' => Pages\EditAdmission::route('/{record}/edit'),
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
