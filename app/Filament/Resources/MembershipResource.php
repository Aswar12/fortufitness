<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages;
use App\Filament\Resources\MembershipResource\RelationManagers;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Livewire\after;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Keanggotaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pilih Member')
                    ->options(User::pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('membership_type_id')
                    ->label('Pilih Tipe Keanggotaan')
                    ->options(MembershipType::pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $membershipType = MembershipType::find($state);
                            $set('end_date', now()->addDays($membershipType->duration)->format('Y-m-d'));
                        }
                    }),
                Forms\Components\Hidden::make('status')
                    ->label('Status')
                    ->default('active'),
                Forms\Components\Hidden::make('start_date')
                    ->label('Tanggal Mulai')
                    ->default(now()->format('Y-m-d')),
                Forms\Components\Hidden::make('end_date')
                    ->label('Tanggal Berakhir'),
            ]);
    }

    public static function afterCreate(Membership $record)
    {
        if (!$record->end_date) {
            $membershipType = MembershipType::find($record->membership_type_id);
            $record->update([
                'end_date' => now()->addDays($membershipType->duration),
            ]);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Member')
                    ->searchable(),
                Tables\Columns\TextColumn::make('membershipType.name')
                    ->label('Tipe Keanggotaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Berakhir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diupdate')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberships::route('/'),
            'create' => Pages\CreateMembership::route('/create'),
            'edit' => Pages\EditMembership::route('/{record}/edit'),
        ];
    }
}
