<?php

namespace App\Filament\Resources\FinancialReportResource\Pages;

use App\Filament\Resources\FinancialReportResource;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Membership;
use App\Models\CheckIn;
use App\Models\FinancialReport;
use Filament\Notifications\Notification;

class CreateFinancialReport extends CreateRecord
{
    protected static string $resource = FinancialReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            $date = Carbon::parse($data['report_date']);
            $reportType = $data['report_type'];

            // Generate laporan
            $reportData = $this->generateReport($date, $reportType);

            return $reportData;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Membuat Laporan')
                ->body($e->getMessage())
                ->danger()
                ->send();

            \Log::error('Gagal generate laporan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    protected function generateReport($date, $reportType): array
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

            // Hitung total pendapatan
            $totalRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('amount');

            // Hitung total pengeluaran
            $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');

            // Hitung pendapatan bersih
            $netIncome = $totalRevenue - $totalExpenses;

            // Hitung total keanggotaan aktif
            $totalMemberships = Membership::where('status', 'active')->count();

            // Hitung keanggotaan baru
            $newMemberships = Membership::whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Hitung keanggotaan yang dibatalkan
            $cancelledMemberships = Membership::whereBetween('cancelled_at', [$startDate, $endDate])
                ->count();

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

            // Persiapkan data laporan
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

            DB::commit();

            return $reportData;
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error dengan detail lengkap
            \Log::error('Gagal membuat laporan keuangan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}
