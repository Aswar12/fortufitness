<?php
// DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Expense;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        $dataPengeluaran = [
            [
                'description' => 'Biaya sewa gedung',
                'amount' => 1000000,
                'date' => '2024-01-01',
                'category' => 'operational',
            ],
            [
                'description' => 'Biaya listrik dan air',
                'amount' => 500000,
                'date' => '2024-01-15',
                'category' => 'operational',
            ],
            [
                'description' => 'Biaya perawatan peralatan',
                'amount' => 200000,
                'date' => '2024-02-01',
                'category' => 'maintenance',
            ],
            [
                'description' => 'Biaya promosi di media sosial',
                'amount' => 300000,
                'date' => '2024-02-15',
                'category' => 'marketing',
            ],
            [
                'description' => 'Biaya pembelian peralatan baru',
                'amount' => 800000,
                'date' => '2024-03-01',
                'category' => 'maintenance',
            ],
            [
                'description' => 'Biaya sewa ruang meeting',
                'amount' => 400000,
                'date' => '2024-03-15',
                'category' => 'operational',
            ],
            [
                'description' => 'Biaya pelatihan staf',
                'amount' => 600000,
                'date' => '2024-04-01',
                'category' => 'operational',
            ],
            [
                'description' => 'Biaya iklan di koran',
                'amount' => 700000,
                'date' => '2024-04-15',
                'category' => 'marketing',
            ],
        ];

        foreach ($dataPengeluaran as $pengeluaran) {
            Expense::factory()->create($pengeluaran);
        }
    }
}
