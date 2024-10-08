<?php

namespace App\Filament\Resources\CheckinResource\Pages;

use App\Filament\Resources\CheckinResource;
use Filament\Resources\Pages\Page;

class ScanBarcode extends Page
{
    protected static string $resource = CheckinResource::class;

    protected static string $view = 'filament.resources.checkin-resource.pages.scan-barcode';
}
