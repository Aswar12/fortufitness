<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Filters\Filter;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;


    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-down';
    protected static ?string $navigationLabel = 'Pengeluaran Gym';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->label('Deskripsi')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah')
                    ->formatStateUsing(fn($record) => $record ? 'Rp ' . number_format($record->amount, 0, ',', '.') : 'N/A')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\Select::make('category')
                    ->options([
                        'operational' => 'Operasional',
                        'maintenance' => 'Pemeliharaan',
                        'marketing' => 'Pemasaran',
                    ])
                    ->label('Kategori')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi'),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn($record) => 'Rp ' . number_format($record->amount, 0, ',', '.'))
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'operational' => 'Operasional',
                        'maintenance' => 'Pemeliharaan',
                        'marketing' => 'Pemasaran',
                    ]),
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('date_start')
                            ->label('Tanggal Mulai'),
                        Forms\Components\DatePicker::make('date_end')
                            ->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): void {
                        if (!empty($data['date_start']) && !empty($data['date_end'])) {
                            $query->whereBetween('date', [$data['date_start'], $data['date_end']]);
                        }
                    }),
                Filter::make('month')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options([
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): void {
                        if (!empty($data['month'])) {
                            $query->whereMonth('date', $data['month']);
                        }
                    }),
            ])
            ->searchable()
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make() // Menambahkan aksi hapus
                    ->action(function (Expense $record) {
                        $record->delete(); // Logika hapus
                    })->label('Hapus')
                    ->color('danger') // Menandai tombol dengan warna merah
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->action(fn(Expense $record) => self::printReport($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),

        ];
    }




    public static function printReport(Expense $record)
    {
        $pdf = PDF::loadView('print.expense-pdf', ['expense' => $record]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'pengeluaran-' . $record->id . '.pdf');
    }

    public static function printAllReports()
    {
        $expenses = Expense::all();

        $pdf = PDF::loadView('print.all-expenses-pdf', ['expenses' => $expenses]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'pengeluaran-semua.pdf');
    }
}
