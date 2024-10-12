<?php

namespace App\Filament\Resources;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->label('Amount'),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'paypal' => 'PayPal',
                    ])
                    ->required()
                    ->label('Payment Method'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->label('Status'),
                Forms\Components\FileUpload::make('proof_of_payment')
                    ->image()
                    ->label('Proof of Payment'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('payment_method')->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\ImageColumn::make('proof_of_payment'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(2)
                    ->schema([
                        Section::make()
                            ->schema([
                                TextEntry::make('membership.user.name')
                                    ->label('Nama Member')
                                    ->weight('bold'),
                                TextEntry::make('Jumlah Bayar')
                                    ->money('idr')
                                    ->icon('heroicon-o-currency-dollar'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'verified' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary',
                                    }),
                                TextEntry::make('due_date')
                                    ->date()
                                    ->icon('heroicon-o-calendar'),
                            ])
                            ->columns(2),
                        Section::make()
                            ->schema([
                                ImageEntry::make('proof_of_payment')
                                    ->height(400)
                                    ->extraImgAttributes(['loading' => 'lazy'])
                                    ->label('Proof of Payment')
                                    ->crop(fn($image) => $image->crop(0, 0, 200, 100)),
                            ]),
                    ]),
                Section::make('Admin Notes')
                    ->schema([
                        TextEntry::make('admin_notes')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
