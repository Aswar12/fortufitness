<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran Gym</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .logo {
            max-height: 80px;
            max-width: 150px;
            margin-right: 20px;
            object-fit: contain;
        }

        .header-content {
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        table thead {
            background-color: #3498db;
            color: white;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tbody tr:hover {
            background-color: #e6e6e6;
        }

        .summary {
            margin-top: 20px;
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">

        <div class="header-content">
            <h1>Laporan Pengeluaran Gym</h1>
        </div>
    </div>

    <div class="report-info">
        <div>
            <strong>Tanggal Cetak:</strong> {{ date('d F Y') }}
        </div>
        <div>
            <strong>Total Laporan:</strong> {{ count($expenses) }} Transaksi
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($expenses as $index => $expense)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $expense->description }}</td>
                <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d F Y') }}</td>
                <td>
                    @switch($expense->category)
                    @case('operational')
                    Operasional
                    @break
                    @case('maintenance')
                    Pemeliharaan
                    @break
                    @case('marketing')
                    Pemasaran
                    @break
                    @default)
                    {{ $expense->category }}
                    @endswitch
                </td>
            </tr>
            @php $total += $expense->amount; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Total Pengeluaran:</strong> Rp {{ number_format($total, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Laporan Resmi Pengeluaran Gym | Generated {{ date('d F Y H:i:s') }}</p>
    </div>
</body>

</html>