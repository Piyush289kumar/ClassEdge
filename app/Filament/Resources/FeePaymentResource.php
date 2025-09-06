<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeePaymentResource\Pages;
use App\Models\FeePayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeePaymentResource extends Resource
{
    protected static ?string $model = FeePayment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Academics';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('student_id')
                ->relationship('student', 'first_name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('fee_structure_id')
                ->relationship('feeStructure', 'name')
                ->searchable(),

            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('paid_on'),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'partial' => 'Partial',
                    'overdue' => 'Overdue',
                    'waived' => 'Waived',
                    'refunded' => 'Refunded',
                ])
                ->default('pending'),

            Forms\Components\Select::make('payment_mode')
                ->options([
                    'cash' => 'Cash',
                    'card' => 'Card',
                    'bank' => 'Bank Transfer',
                    'upi' => 'UPI',
                    'cheque' => 'Cheque',
                ])
                ->nullable(),

            Forms\Components\TextInput::make('reference_number')
                ->nullable(),

            Forms\Components\TextInput::make('late_fee')
                ->numeric()
                ->default(0),

            Forms\Components\TextInput::make('discount')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('student.first_name')->label('Student')->searchable(),
            Tables\Columns\TextColumn::make('feeStructure.name')->label('Fee Structure'),
            Tables\Columns\TextColumn::make('amount')->money('INR', true)->sortable(),
            Tables\Columns\TextColumn::make('paid_on')->date(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'success' => 'paid',
                    'warning' => 'pending',
                    'danger' => 'overdue',
                    'info' => 'partial',
                    'secondary' => 'waived',
                ]),
            Tables\Columns\TextColumn::make('payment_mode'),
            Tables\Columns\TextColumn::make('late_fee')->money('INR', true),
            Tables\Columns\TextColumn::make('discount')->money('INR', true),
        ])
        ->actions([Tables\Actions\EditAction::make()])
        ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeePayments::route('/'),
            'create' => Pages\CreateFeePayment::route('/create'),
            'edit' => Pages\EditFeePayment::route('/{record}/edit'),
        ];
    }
}
