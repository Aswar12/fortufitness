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
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Membership;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Validator;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Actions;


class FinancialReportResource extends Resource
{
    protected static ?string $model = FinancialReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Keuangan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Generate Laporan Keuangan')
                    ->schema([
                        Select::make('report_type')
                            ->label('Tipe Laporan')
                            ->options([
                                'daily' => 'Harian',
                                'weekly' => 'Mingguan',
                                'monthly' => 'Bulanan',
                                'yearly' => 'Tahunan',
                            ])
                            ->required()
                            ->live(), // Gunakan live untuk reaktivitas

                        DatePicker::make('report_date')
                            ->label('Tanggal Laporan')
                            // Hanya tampil jika report_type sudah dipilih
                            ->visible(fn($get) => $get('report_type') !== null)
                            ->required(fn($get) => $get('report_type') !== null)
                            ->maxDate(now())
                            ->rules(function ($get) {
                                return [
                                    function ($attribute, $value, $fail) use ($get) {
                                        $reportType = $get('report_type');

                                        if (!$reportType) {
                                            $fail('Pilih tipe laporan terlebih dahulu.');
                                            return;
                                        }

                                        $date = Carbon::parse($value);

                                        switch ($reportType) {
                                            case 'daily':
                                                if (!$date->isToday() && !$date->isPast()) {
                                                    $fail('Tanggal laporan harian harus hari ini atau di masa lalu.');
                                                }
                                                break;
                                            case 'weekly':
                                                if (!$date->isStartOfWeek()) {
                                                    $fail('Untuk laporan mingguan, pilih hari Senin.');
                                                }
                                                break;
                                            case 'monthly':
                                                if (!$date->is('first day of this month')) {
                                                    $fail('Untuk laporan bulanan, pilih tanggal 1 bulan.');
                                                }
                                                break;
                                            case 'yearly':
                                                if (!$date->isStartOfYear()) {
                                                    $fail('Untuk laporan tahunan, pilih 1 Januari.');
                                                }
                                                break;
                                        }
                                    }
                                ];
                            })
                            ->hint(fn($get) => match ($get('report_type')) {
                                'daily' => 'Pilih tanggal hari ini atau sebelumnya',
                                'weekly' => 'Pilih hari Senin pada minggu yang diinginkan',
                                'monthly' => 'Pilih tanggal 1 pada bulan yang diinginkan',
                                'yearly' => 'Pilih tanggal 1 Januari pada tahun yang diinginkan',
                                default => ''
                            })

                    ])
            ]);
    }

    // Tambahkan method submit di resource
    public function submit(array $data): void
    {
        try {
            // Validasi manual
            if (empty($data['report_type']) || empty($data['report_date'])) {
                Notification::make()
                    ->title('Gagal Generate Laporan')
                    ->body('Silakan lengkapi semua field.')
                    ->danger()
                    ->send();
                return;
            }

            // Parsing tanggal
            $date = Carbon::parse($data['report_date']);
            $reportType = $data['report_type'];

            // Generate laporan
            $report = $this->generateReport($date, $reportType);

            // Notifikasi sukses
            Notification::make()
                ->title('Laporan Berhasil Dibuat')
                ->body("Laporan {$reportType} berhasil dibuat.")
                ->success()
                ->send();

            // Redirect
            redirect()->route('filament.admin.resources.financial-reports.index');
        } catch (\Exception $e) {
            // Tangani error
            Notification::make()
                ->title('Gagal Membuat Laporan')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Log error untuk debugging
            \Log::error('Gagal generate laporan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // Method generate laporan (sesuaikan dengan kebutuhan Anda)


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_type')
                    ->label('Tipe Laporan')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'daily' => 'info',
                        'weekly' => 'warning',
                        'monthly' => 'success',
                        'yearly' => 'primary',
                        default => 'gray'
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('report_date')
                    ->label('Periode Laporan')
                    ->date('d F Y')
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
                Tables\Filters\SelectFilter::make('report_type')
                    ->label('Tipe Laporan')
                    ->options([
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'monthly' => 'Bulanan',
                        'yearly' => 'Tahunan'
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn(Builder $query, $reportType) => $query->where('report_type', $reportType)
                        );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->action(function (FinancialReport $record) {
                        $record->delete();
                    })->label('Hapus')
                    ->color('danger')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus'),
                ]),
            ])
            ->searchable();
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

    protected static function generateReport($date, $reportType)
    {
        DB::beginTransaction();
        try {
            switch ($reportType) {
                case 'daily':
                    $startDate = $date->copy()->startOfDay();
                    $endDate = $date->copy()->endOfDay();
                    break;
                case 'weekly':
                    $startDate = $date->copy()->startOfWeek();
                    $endDate = $date->copy()->endOfWeek();
                    break;
                case 'monthly':
                    $startDate = $date->copy()->startOfMonth();
                    $endDate = $date->copy()->endOfMonth();
                    break;
                case 'yearly':
                    $startDate = $date->copy()->startOfYear();
                    $endDate = $date->copy()->endOfYear();
                    break;
                default:
                    throw new \Exception('Tipe laporan tidak valid');
            }

            // Debug: Log rentang tanggal
            \Log::info('Rentang Tanggal Laporan', [
                'start_date' => $startDate->toDateTimeString(),
                'end_date' => $endDate->toDateTimeString(),
                'report_type' => $reportType
            ]);

            // Hitung total pendapatan
            $totalRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('amount');

            // Hitung total pengeluaran
            $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');

            // Debug: Log data keuangan
            \Log::info('Data Keuangan', [
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
            ]);

            // Hitung pendapatan bersih
            $netIncome = $totalRevenue - $totalExpenses;

            // Hitung total keanggotaan
            $totalMemberships = Membership::where('status', 'active')
                ->count(); // Ubah dari whereBetween ke count total

            // Hitung keanggotaan baru
            $newMemberships = Membership::whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Hitung keanggotaan yang dibatalkan
            $cancelledMemberships = Membership::whereBetween('cancelled_at', [$startDate, $endDate])
                ->count();

            // Debug: Log data keanggotaan
            \Log::info('Data Keanggotaan', [
                'total_memberships' => $totalMemberships,
                'new_memberships' => $newMemberships,
                'cancelled_memberships' => $cancelledMemberships,
            ]);

            // Hitung total check-in
            $totalCheckIns = CheckIn::whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Hitung rata-rata check-in harian
            $daysDiff = max(1, $startDate->diffInDays($endDate) + 1);
            $averageDailyCheckIns = $totalCheckIns / $daysDiff;

            // Temukan tipe keanggotaan terpopuler
            $topMembershipType = Membership::select('membership_type_id', DB::raw('count(*) as total'))
                ->groupBy('membership_type_id')
                ->orderByDesc('total')
                ->first();

            $topMembershipTypeName = $topMembershipType
                ? optional($topMembershipType->membershipType)->name
                : 'Tidak Ada';

            // Temukan kategori pengeluaran tertinggi
            $topExpenseCategory = Expense::whereBetween('created_at', [$startDate, $endDate])
                ->select('category', DB::raw('SUM(amount) as total'))
                ->groupBy('category')
                ->orderByDesc('total')
                ->first();

            $topExpenseCategoryName = $topExpenseCategory
                ? $topExpenseCategory->category
                : 'Tidak Ada';

            // Debug: Log data top kategori
            \Log::info('Top Kategori', [
                'top_membership_type' => $topMembershipTypeName,
                'top_expense_category' => $topExpenseCategoryName,
            ]);

            // Hitung pembayaran
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

            // Debug: Log data pembayaran
            \Log::info('Data Pembayaran', [
                'total_pending_payments' => $totalPendingPayments,
                'total_completed_payments' => $totalCompletedPayments,
                'total_failed_payments' => $totalFailedPayments,
                'total_refunded_payments' => $totalRefundedPayments,
            ]);

            // Persiapkan data dengan default value
            $reportData = [
                'report_type' => $reportType,
                'report_date' => $endDate->toDateString(),
                'total_revenue' => $totalRevenue ?? 0,
                'total_expenses' => $totalExpenses ?? 0,
                'net_income' => $netIncome ?? 0,
                'total_memberships' => $totalMemberships ?? 0,
                'new_memberships' => $newMemberships ?? 0,
                'cancelled_memberships' => $cancelledMemberships ?? 0,
                'total_check_ins' => $totalCheckIns ?? 0,
                'average_daily_check_ins' => round($averageDailyCheckIns, 2) ?? 0,
                'top_membership_type' => $topMembershipTypeName ?? 'Tidak Ada',
                'top_expense_category' => $topExpenseCategoryName ?? 'Tidak Ada',
                'total_pending_payments' => $totalPendingPayments ?? 0,
                'total_completed_payments' => $totalCompletedPayments ?? 0,
                'total_failed_payments' => $totalFailedPayments ?? 0,
                'total_refunded_payments' => $totalRefundedPayments ?? 0,
            ];

            // Debug: Log data akhir yang akan disimpan
            \Log::info('Data Laporan Akhir', $reportData);

            // Buat laporan keuangan
            $financialReport = FinancialReport::create($reportData);

            DB::commit();

            return $financialReport;
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error dengan detail lengkap
            \Log::error('Gagal membuat laporan keuangan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Kirim notifikasi
            Notification::make()
                ->title('Gagal Membuat Laporan')
                ->body('Terjadi kesalahan saat membuat laporan: ' . $e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }
        dd($totalMemberships);
    }
}
