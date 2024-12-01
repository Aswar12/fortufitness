<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialReportResource\Pages;
use App\Models\CheckIn;
use App\Models\FinancialReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Membership;
use Illuminate\Support\Facades\DB;

class FinancialReportResource extends Resource
{
    protected static ?string $model = FinancialReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('report_period')
                    ->label('Periode Laporan')
                    ->options([
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan', 
                        'monthly' => 'Bulanan',
                        'yearly' => 'Tahunan'
                    ])
                    ->required()
                    ->live(),

                Forms\Components\Select::make('report_year')
                    ->label('Tahun')
                    ->options(function () {
                        $currentYear = date('Y');
                        return array_combine(range($currentYear - 5, $currentYear), range($currentYear - 5, $currentYear));
                    })
                    ->required()
                    ->visible(fn($get) => in_array($get('report_period'), ['monthly', 'yearly', 'weekly']))
                    ->live(),

                Forms\Components\Select::make('report_month')
                    ->label('Bulan')
                    ->options([
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni', 
                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September', 
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ])
                    ->required()
                    ->visible(fn($get) => in_array($get('report_period'), ['daily', 'weekly', 'monthly']))
                    ->live(),

                Forms\Components\DatePicker::make('report_date')
                    ->label('Tanggal Laporan')
                    ->required()
                    ->visible(fn($get) => $get('report_period') === 'daily')
                    ->live(),

                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('generate')
                        ->label('Generate Laporan')
                        ->action(function ($data) {
                            try {
                                $period = $data['report_period'];
                                
                                // Tentukan tanggal untuk setiap periode
                                switch ($period) {
                                    case 'daily':
                                        $reportDate = Carbon::parse($data['report_date']);
                                        break;
                                    case 'weekly':
                                        $reportDate = Carbon::createFromDate($data['report_year'], $data['report_month'], 1)->endOfWeek();
                                        break;
                                    case 'monthly':
                                        $reportDate = Carbon::createFromDate($data['report_year'], $data['report_month'], 1)->endOfMonth();
                                        break;
                                    case 'yearly':
                                        $reportDate = Carbon::createFromDate($data['report_year'], 12, 31);
                                        break;
                                }

                                // Generate laporan
                                static::generateReport($reportDate, $period);

                                Notification::make()
                                    ->title('Laporan Berhasil Dibuat')
                                    ->success()
                                    ->send();

                                return Redirect::to(static::getUrl('index'));
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Gagal Membuat Laporan')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                ])
            ]);
    }

    protected static function generateReport(Carbon $reportDate, string $period)
    {
        // Tentukan rentang waktu berdasarkan periode
        $startDate = match ($period) {
            'daily' => $reportDate->copy()->startOfDay(),
            'weekly' => $reportDate->copy()->startOfWeek(),
            'monthly' => $reportDate->copy()->startOfMonth(),
            'yearly' => $reportDate->copy()->startOfYear(),
            default => throw new \InvalidArgumentException("Periode tidak valid")
        };

        $endDate = match ($period) {
            'daily' => $reportDate->copy()->endOfDay(),
            'weekly' => $reportDate->copy()->endOfWeek(),
            'monthly' => $reportDate->copy()->endOfMonth(),
            'yearly' => $reportDate->copy()->endOfYear(),
            default => throw new \InvalidArgumentException("Periode tidak valid")
        };

        // Perhitungan Keuangan
        $totalRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');

        $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $netIncome = $totalRevenue - $totalExpenses;

        // Perhitungan Keanggotaan
        $totalMemberships = Membership::where('status', 'active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $newMemberships = Membership::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $cancelledMemberships = Membership::whereBetween('cancelled_at', [$startDate, $endDate])
            ->count();

        // Perhitungan Check-In
        $totalCheckIns = CheckIn::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $averageDailyCheckIns = $period === 'daily' 
            ? $totalCheckIns 
            : $totalCheckIns / max(1, $startDate->diffInDays($endDate));

        // Simpan Laporan
        FinancialReport::create([
            'report_date' => $endDate->toDateString(),
            'report_period' => $period,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
            'total_memberships' => $totalMemberships,
            'new_memberships' => $newMemberships,
            'cancelled_memberships' => $cancelledMemberships,
            'total_check_ins' => $totalCheckIns,
            'average_daily_check_ins' => $averageDailyCheckIns,
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_date')
                    ->label('Tanggal Laporan')
                    ->date('d F Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('report_period')
                    ->label('Periode')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'daily' => 'success',
                        'weekly' => 'warning',
                        'monthly' => 'primary',
                        'yearly' => 'info',
                        default => 'gray'
                    })
                    ->formatStateUsing(fn(string $state) => match($state) {
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'monthly' => 'Bulanan', 
                        'yearly' => 'Tahunan',
                        default => $state
                    }),
                
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Pendapatan')
                    ->money('IDR')
                    ->color('success')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_expenses')
                    ->label('Pengeluaran')
                    ->money('IDR')
                    ->color('danger')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('net_income')
                    ->label('Pendapatan Bersih')
                    ->money('IDR')
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_memberships')
                    ->label('Total Anggota')
                    ->icon('heroicon-o-user-group')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('new_memberships')
                    ->label('Anggota Baru')
                    ->color('primary')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('cancelled_memberships')
                    ->label('Anggota Berhenti')
                    ->color('danger')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_check_ins')
                    ->label('Total Check-In')
                    ->icon('heroicon-o-check-badge')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('average_daily_check_ins')
                    ->label('Rata-rata Check-In')
                    ->numeric(2)
                    ->sortable(),
            ])
            ->defaultSort('report_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('report_period')
                    ->label('Periode')
                    ->options([
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'monthly' => 'Bulanan',
                        'yearly' => 'Tahunan'
                    ]),
                
                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(function () {
                        return FinancialReport::selectRaw('YEAR(report_date) as year')
                            ->distinct()
                            ->pluck('year')
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray();
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail'),
                
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus Laporan')
                    ->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Laporan Terpilih')
                        ->requiresConfirmation(),
                    
                    Tables\Actions\ExportBulkAction::make()
                        ->label('Ekspor Laporan')
                ])
            ]);
    }
    

    
    // Tambahan method untuk relasi (opsional)
    public static function getRelations(): array
    {
        return [
            // Tambahkan relasi jika diperlukan
        ];
    }
    
    // Definisi halaman
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinancialReports::route('/'),
            'create' => Pages\CreateFinancialReport::route('/create'),
            'view' => Pages\ViewFinancialReport::route('/{record}'),
        ];
    }

}