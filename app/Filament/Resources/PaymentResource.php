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
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\MembershipResource;
use Filament\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    protected static ?string $navigationLabel = 'Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->label('Jumlah'),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'credit_card' => 'Kartu Kredit',
                        'bank_transfer' => 'Transfer Bank',
                        'paypal' => 'PayPal',
                    ])
                    ->required()
                    ->label('Metode Pembayaran'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'completed' => 'Selesai',
                        'failed' => 'Gagal',
                        'refunded' => 'Dikembalikan',
                    ])
                    ->required()
                    ->label('Status'),
                Forms\Components\FileUpload::make('proof_of_payment')
                    ->image()
                    ->label('Bukti Pembayaran'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('amount')->money('IDR')->sortable()->label('Jumlah'),
                Tables\Columns\TextColumn::make('payment_method')->sortable()->label('Metode'),
                Tables\Columns\TextColumn::make('status')->sortable()->label('Status'),
                Tables\Columns\ImageColumn::make('proof_of_payment')->label('Bukti'),
                Tables\Columns\TextColumn::make('payment_date')->date()->sortable()->label('Tanggal'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Ubah'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus'),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make('Informasi Pembayaran')
                            ->schema([
                                TextEntry::make('membership.user.name')
                                    ->label('Nama Member')
                                    ->weight('bold'),
                                TextEntry::make('amount')
                                    ->money('idr')
                                    ->label('Jumlah')
                                    ->icon('heroicon-o-currency-dollar'),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'refunded' => 'secondary',
                                        default => 'secondary',
                                    }),
                                TextEntry::make('payment_date')
                                    ->label('Tanggal')
                                    ->date()
                                    ->icon('heroicon-o-calendar'),
                            ])
                            ->columns(1)
                            ->columnSpan('1/2'),
                        Section::make('Bukti Pembayaran')
                            ->schema([
                                ImageEntry::make('proof_of_payment')
                                    ->height(400)
                                    ->extraImgAttributes(['loading' => 'lazy'])
                                    ->label('Bukti'),
                            ])
                            ->columns(1)
                            ->columnSpan('1/2'),
                    ])
                    ->columns(2),
            ]);
    }

    protected function confirmPayment(Payment $payment)
    {
        $payment->update(['status' => 'completed']);
        // Tambahkan logika tambahan untuk konfirmasi pembayaran
    }

    protected function rejectPayment(Payment $payment)
    {
        $payment->update(['status' => 'failed']);
        // Tambahkan logika tambahan untuk penolakan pembayaran
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['id', 'amount', 'payment_method', 'status', 'membership.user.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'ID' => $record->id,
            'Jumlah' => $record->amount,
            'Metode' => $record->payment_method,
            'Status' => $record->status,
            'Member' => $record->membership->user->name,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() > 0
            ? 'warning'
            : 'primary';
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
}
