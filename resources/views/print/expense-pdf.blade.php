<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Detail Pengeluaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .detail {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ecf0f1;
            border-radius: 5px;
        }

        .detail label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            width: 150px;
        }

        .amount {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.2em;
        }

        .category {
            text-transform: capitalize;
            color: #3498db;
        }

        .date {
            color: #7f8c8d;
        }

        .description {
            background-color: #f9f9f9;
            border-left: 4px solid #3498db;
            padding: 10px;
            margin-top: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Detail Pengeluaran</h1>
        </div>

        <div class="detail">
            <label>Deskripsi:</label>
            <span class="description">{{ $expense->description }}</span>
        </div>

        <div class="detail">
            <label>Jumlah:</label>
            <span class="amount">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
        </div>

        <div class="detail">
            <label>Tanggal:</label>
            <span class="date">{{ \Carbon\Carbon::parse($expense->date)->format('d F Y') }}</span>
        </div>

        <div class="detail">
            <label>Kategori:</label>
            <span class="category">
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
            </span>
        </div>

        <div class="footer">
            <p>Laporan Detail Pengeluaran | Generated {{ date('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>