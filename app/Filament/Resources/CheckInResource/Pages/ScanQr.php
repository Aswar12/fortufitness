<?php

namespace App\Filament\Resources\CheckInResource\Pages;

use App\Filament\Resources\CheckInResource;
use Filament\Resources\Pages\Page;

class ScanQr extends Page
{
    protected static string $resource = CheckInResource::class;

    protected static string $view = 'filament.resources.check-in-resource.pages.scan-qr';
}
