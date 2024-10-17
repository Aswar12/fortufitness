<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Membership;

class MembershipByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Membership by Status';
    protected int | string | array $columnSpan = '1/2';
    protected int $chartHeight = 100;

    protected function getData(): array
    {
        $data = Membership::groupBy('status')
            ->selectRaw('count(*) as count, status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Memberships',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'cutout' => '60%',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'size' => 10,
                        ],
                        'boxWidth' => 10,
                    ],
                ],

            ],
        ];
    }

    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'max-w-xs mx-auto',
        ];
    }
}
