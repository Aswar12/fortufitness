<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialReportResource\Pages;
use App\Filament\Resources\FinancialReportResource\RelationManagers;
use App\Models\CheckIn;
use App\Models\FinancialReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                Forms\Components\Select::make('report_month')
                    ->label('Bulan Laporan')
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
                    ])
                    ->required()
                    ->live(),
                Forms\Components\Select::make('report_year')
                    ->label('Tahun Laporan')
                    ->options(function () {
                        $currentYear = date('Y');
                        return array_combine(range($currentYear - 5, $currentYear), range($currentYear - 5, $currentYear));
                    })
                    ->required()
                    ->live(),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('generate')
                        ->label('Generate Report')
                        ->action(function ($record, $livewire) {
                            $reportMonth = $livewire->data['report_month'];
                            $reportYear = $livewire->data['report_year'];
                            $reportDate = Carbon::createFromDate($reportYear, $reportMonth, 1)->endOfMonth()->toDateString();
                            static::generateReport($reportDate);
                            Notification::make()->title('Report generated successfully')->success()->send();
                            return Redirect::to(static::getUrl('index'));
                        })
                        ->visible(fn($livewire) => $livewire instanceof Pages\CreateFinancialReport)
                ])
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_date')
                    ->label('Periode Laporan')
                    ->date('F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Total Pendapatan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_expenses')
                    ->label('Total Pengeluaran')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_income')
                    ->label('Pendapatan Bersih')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_memberships')
                    ->label('Total Anggota')
                    ->sortable(),
                Tables\Columns\TextColumn::make('new_memberships')
                    ->label('Anggota Baru')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cancelled_memberships')
                    ->label('Anggota Berhenti')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_check_ins')
                    ->label('Total Check-Ins')
                    ->sortable(),
                Tables\Columns\TextColumn::make('average_daily_check_ins')
                    ->label('Rata-rata Check-Ins Harian')
                    ->sortable()
                    ->numeric(2),
                Tables\Columns\TextColumn::make('top_membership_type')
                    ->label('Tipe Keanggotaan Terpopuler'),
                Tables\Columns\TextColumn::make('top_expense_category')
                    ->label('Kategori Pengeluaran Tertinggi'),
                Tables\Columns\TextColumn::make('total_pending_payments')
                    ->label('Total Pembayaran Tertunda')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_completed_payments')
                    ->label('Total Pembayaran Selesai')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_failed_payments')
                    ->label('Total Pembayaran Gagal')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_refunded_payments')
                    ->label('Total Pembayaran Dikembalikan')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->defaultSort('report_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(function () {
                        $years = FinancialReport::selectRaw('YEAR(report_date) as year')
                            ->distinct()
                            ->pluck('year')
                            ->sortDesc();
                        return $years->mapWithKeys(fn($year) => [$year => $year])->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                fn(Builder $query, $year): Builder => $query->whereYear('report_date', $year),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
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
            'index' => Pages\ListFinancialReports::route('/'),
            'create' => Pages\CreateFinancialReport::route('/create'),
            'view' => Pages\ViewFinancialReport::route('/{record}'),
        ];
    }


    protected static function generateReport($date)
    {
        $endDate = Carbon::parse($date)->endOfMonth();
        $startDate = $endDate->copy()->startOfMonth();

        $totalRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $netIncome = $totalRevenue - $totalExpenses;

        $totalMemberships = Membership::where('status', 'active')->count();
        $newMemberships = Membership::whereBetween('created_at', [$startDate, $endDate])->count();
        $cancelledMemberships = Membership::whereBetween('cancelled_at', [$startDate, $endDate])->count();

        $totalCheckIns = CheckIn::whereBetween('created_at', [$startDate, $endDate])->count();
        $averageDailyCheckIns = $totalCheckIns / $endDate->daysInMonth;

        $topMembershipType = Membership::where('status', 'active')
            ->select('membership_type_id', DB::raw('count(*) as total'))
            ->groupBy('membership_type_id')
            ->orderByDesc('total')
            ->first();
        $topMembershipType = $topMembershipType ? $topMembershipType->membershipType->name : null;

        $topExpenseCategory = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('category')
            ->selectRaw('category, sum(amount) as total')
            ->orderByDesc('total')
            ->first()->category ?? 'N/A';

        $totalPendingPayments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->sum('amount');
        $totalCompletedPayments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');
        $totalFailedPayments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'failed')
            ->sum('amount');
        $totalRefundedPayments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'refunded')
            ->sum('amount');

        FinancialReport::create([
            'report_date' => $endDate->toDateString(),
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
            'total_memberships' => $totalMemberships,
            'new_memberships' => $newMemberships,
            'cancelled_memberships' => $cancelledMemberships,
            'total_check_ins' => $totalCheckIns,
            'average_daily_check_ins' => $averageDailyCheckIns,
            'top_membership_type' => $topMembershipType,
            'top_expense_category' => $topExpenseCategory,
            'total_pending_payments' => $totalPendingPayments,
            'total_completed_payments' => $totalCompletedPayments,
            'total_failed_payments' => $totalFailedPayments,
            'total_refunded_payments' => $totalRefundedPayments,
        ]);
    }
}
