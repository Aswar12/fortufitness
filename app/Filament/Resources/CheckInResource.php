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
use App\Filament\Resources\CheckInResource\Pages\ScanQrModal;

class CheckInResource extends Resource
{
    protected static ?string $model = CheckIn::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    public $showScanQrModal = false;
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


                Tables\Actions\DeleteAction::make() // Menambahkan aksi hapus
                    ->action(function (CheckIn $record) {
                        $record->delete(); // Logika hapus
                    })->label('Hapus')
                    ->color('danger') // Menandai tombol dengan warna merah
                    ->requiresConfirmation(),
            ])->searchable()
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
            'scan' => Pages\ScanQr::route('/scan'),
        ];
    } // In CheckInResource.php

    // In CheckInResource.php

    public function store(Request $request)
    {
        $membershipId = $request->input('membership_id');
        // Retrieve the membership and user information from the database
        $membership = Membership::find($membershipId);
        $user = $membership->user;
        // Create a new check-in record
        $checkIn = new CheckIn();
        $checkIn->user_id = $user->id;
        $checkIn->membership_id = $membershipId;
        $checkIn->check_in_time = now();
        $checkIn->save();

        // Return a success response
        return response()->json(['message' => 'Check-in successful']);
    }
}
