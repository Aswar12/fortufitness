<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('print-all')
                ->label('Cetak Laporan')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    $expenses = Expense::all();

                    $pdf = PDF::loadView('print.all-expenses-pdf', ['expenses' => $expenses]);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'pengeluaran-semua.pdf');
                }),
        ];
    }
}
