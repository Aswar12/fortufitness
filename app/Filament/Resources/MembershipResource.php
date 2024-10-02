<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages;
use App\Filament\Resources\MembershipResource\RelationManagers;
use App\Models\Membership;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Form::select('user_id', 'User', User::all()->pluck('name', 'id'))
                    ->required()
                    ->reactive(),
                Form::select('membership_type_id', 'Membership Type', MembershipType::all()->pluck('name', 'id'))
                    ->required()
                    ->reactive(),
                Form::date('start_date', 'Start Date')
                    ->required(),
                Form::date('end_date', 'End Date')
                    ->required(),
                Form::select('status', 'Status', [
                    'active' => 'Active',
                    'expired' => 'Expired',
                ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name', 'User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('membership_type.name', 'Membership Type')
                    ->searchable()
                    ->sortable(),
                DateColumn::make('start_date', 'Start Date')
                    ->searchable()
                    ->sortable(),
                DateColumn::make('end_date', 'End Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status', 'Status')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                CreateAction::make(),
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListMemberships::route('/'),
            'create' => Pages\CreateMembership::route('/create'),
            'edit' => Pages\EditMembership::route('/{record}/edit'),
        ];
    }
}
