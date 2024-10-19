<?php

namespace App\Filament\Resources\FinancialReportResource\Pages;

use App\Filament\Resources\FinancialReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewFinancialReport extends ViewRecord
{
    protected static string $resource = FinancialReportResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make('Informasi Umum')
                            ->schema([
                                TextEntry::make('report_date')
                                    ->label('Periode Laporan')
                                    ->date('F Y')
                                    ->icon('heroicon-o-calendar'),
                                TextEntry::make('total_revenue')
                                    ->label('Total Pendapatan')
                                    ->money('IDR')
                                    ->icon('heroicon-o-currency-dollar'),
                                TextEntry::make('total_expenses')
                                    ->label('Total Pengeluaran')
                                    ->money('IDR')
                                    ->icon('heroicon-o-credit-card'),
                                TextEntry::make('net_income')
                                    ->label('Pendapatan Bersih')
                                    ->money('IDR')
                                    ->icon('heroicon-o-chart-bar'),
                            ])
                            ->columns(2)
                            ->columnSpan('full'),

                        Section::make('Keanggotaan')
                            ->schema([
                                TextEntry::make('total_memberships')
                                    ->label('Total Anggota')
                                    ->icon('heroicon-o-users'),
                                TextEntry::make('new_memberships')
                                    ->label('Anggota Baru')
                                    ->icon('heroicon-o-user-plus'),
                                TextEntry::make('cancelled_memberships')
                                    ->label('Anggota Berhenti')
                                    ->icon('heroicon-o-user-minus'),
                                TextEntry::make('top_membership_type')
                                    ->label('Tipe Keanggotaan Terpopuler')
                                    ->icon('heroicon-o-star'),
                            ])
                            ->columns(2)
                            ->columnSpan('1/2'),

                        Section::make('Check-Ins dan Pengeluaran')
                            ->schema([
                                TextEntry::make('total_check_ins')
                                    ->label('Total Check-Ins')
                                    ->icon('heroicon-o-clipboard'),
                                TextEntry::make('average_daily_check_ins')
                                    ->label('Rata-rata Check-Ins Harian')
                                    ->numeric(2)
                                    ->icon('heroicon-o-chart-bar'),
                                TextEntry::make('top_expense_category')
                                    ->label('Kategori Pengeluaran Tertinggi')
                                    ->icon('heroicon-o-banknotes'),
                            ])
                            ->columns(1)
                            ->columnSpan('1/2'),

                        Section::make('Pembayaran')
                            ->schema([
                                TextEntry::make('total_pending_payments')
                                    ->label('Total Pembayaran Tertunda')
                                    ->money('IDR')
                                    ->icon('heroicon-o-clock'),
                                TextEntry::make('total_completed_payments')
                                    ->label('Total Pembayaran Selesai')
                                    ->money('IDR')
                                    ->icon('heroicon-o-check-circle'),
                                TextEntry::make('total_failed_payments')
                                    ->label('Total Pembayaran Gagal')
                                    ->money('IDR')
                                    ->icon('heroicon-o-x-circle'),
                                TextEntry::make('total_refunded_payments')
                                    ->label('Total Pembayaran Dikembalikan')
                                    ->money('IDR')
                                    ->icon('heroicon-o-arrow-uturn-left'),
                            ])
                            ->columns(2)
                            ->columnSpan('full'),
                    ])
                    ->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali ke Daftar')
                ->url(self::getResource()::getUrl('index'))
                ->color('secondary')
                ->icon('heroicon-o-arrow-left'),
            Actions\Action::make('print')
                ->label('Cetak Laporan')
                ->icon('heroicon-o-printer')
                ->action(fn() => $this->printReport()),

        ];
    }

    protected function printReport()
    {
        $report = $this->record;

        $pdf = PDF::loadView('financial-report-pdf', ['report' => $report]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-keuangan-' . $report->report_date->format('F-Y') . '.pdf');
    }
}
