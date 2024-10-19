<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            border-bottom: 1px solid #ccc;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Periode: {{ $report->report_date->format('F Y') }}</p>
    </div>

    <div class="section">
        <h2>Informasi Umum</h2>
        <div class="grid">
            <p><strong>Total Pendapatan:</strong> Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</p>
            <p><strong>Total Pengeluaran:</strong> Rp {{ number_format($report->total_expenses, 0, ',', '.') }}</p>
            <p><strong>Pendapatan Bersih:</strong> Rp {{ number_format($report->net_income, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="section">
        <h2>Keanggotaan</h2>
        <div class="grid">
            <p><strong>Total Anggota:</strong> {{ $report->total_memberships }}</p>
            <p><strong>Anggota Baru:</strong> {{ $report->new_memberships }}</p>
            <p><strong>Anggota Berhenti:</strong> {{ $report->cancelled_memberships }}</p>
            <p><strong>Tipe Keanggotaan Terpopuler:</strong> {{ $report->top_membership_type }}</p>
        </div>
    </div>

    <div class="section">
        <h2>Check-Ins dan Pengeluaran</h2>
        <div class="grid">
            <p><strong>Total Check-Ins:</strong> {{ $report->total_check_ins }}</p>
            <p><strong>Rata-rata Check-Ins Harian:</strong> {{ number_format($report->average_daily_check_ins, 2) }}</p>
            <p><strong>Kategori Pengeluaran Tertinggi:</strong> {{ $report->top_expense_category }}</p>
        </div>
    </div>
</body>

</html>