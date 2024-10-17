<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Membership;

class MembershipByTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Membership by Type';
    protected int | string | array $columnSpan = '1/2';
    protected int $chartHeight = 100;

    protected function getData(): array
    {
        $data = Membership::join('membership_types', 'memberships.membership_type_id', '=', 'membership_types.id')
            ->groupBy('membership_types.id', 'membership_types.name')
            ->selectRaw('count(*) as count, membership_types.name')
            ->pluck('count', 'name')
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
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
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
