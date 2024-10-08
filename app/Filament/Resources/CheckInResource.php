<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckInResource\Pages;
use App\Filament\Resources\CheckInResource\RelationManagers;
use App\Models\CheckIn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CheckInResource extends Resource
{
    protected static ?string $model = CheckIn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('ID Pengguna')
                    ->required()
                    ->searchable()
                    ->options(function () {
                        return \App\Models\User::all()->pluck('name', 'id');
                    }),
                Forms\Components\DateTimePicker::make('check_in_time')
                    ->label('Waktu Check-in')
                    ->required()
                    ->default(now()),
                Forms\Components\DateTimePicker::make('check_out_time')
                    ->label('Waktu Check-out'),
                Forms\Components\TextInput::make('check_in_method')
                    ->label('Metode Check-in')
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('barcode')
                //     ->label('Scan Barcode')
                //     ->hint('Scan barcode using camera to fill this input')
                //     ->reactive(),
                // Forms\Components\View::make('filament.custom-components.scan-barcode')
                //     ->label('Scan Barcode Area')
                //     ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in_time')
                    ->label('Waktu Check-in')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out_time')
                    ->label('Waktu Check-out')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in_method')
                    ->label('Metode Check-in')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus'),
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
            'index' => Pages\ListCheckIns::route('/'),
            'create' => Pages\CreateCheckIn::route('/create'),
            'edit' => Pages\EditCheckIn::route('/{record}/edit'),
        ];
    }

    public static function getActions(): array
    {
        return [
            Action::make('scanQR')
                ->label('Scan QR')
                ->icon('heroicon-o-qr-code')
                ->size(ActionSize::Large)
                ->color('success')
                ->action(function () {
                    // Logika untuk menampilkan modal scan QR
                })
                ->modalHeading('Scan QR Code Member')
                ->modalDescription('Silakan scan QR code member untuk melakukan check-in.')
                ->modalContent(view('filament.resources.checkin-resource.pages.scan-qr-modal'))
                ->modalSubmitActionLabel('Check-in')
        ];
    }
}
