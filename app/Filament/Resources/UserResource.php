<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Member';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')->label('Kata Sandi')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Perempuan' => 'Perempuan',

                        'Laki-laki' => 'Laki-laki',
                    ]),
                Forms\Components\DatePicker::make('birth_date')
                    ->label('Tanggal lahir'),
                Forms\Components\TextInput::make('phone_number')->label('Nomor Telpon/Wa')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')->label('Alamat')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('emergency_contact')->label('Kontak Darurat')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat '),
                Tables\Columns\TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('profile_photo_url')
                    ->label('Foto Profil')
                    ->height(50),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role'),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Nomor Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('emergency_contact')
                    ->label('Kontak Darurat')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),

                Tables\Actions\DeleteAction::make() // Menambahkan aksi hapus
                    ->action(function (FinancialReport $record) {
                        $record->delete(); // Logika hapus
                    })->label('Hapus')
                    ->color('danger') // Menandai tombol dengan warna merah
                    ->requiresConfirmation(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
