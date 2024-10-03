<?php

namespace App\Filament\Resources\MembershipResource\Pages;

use App\Filament\Resources\MembershipResource;
use Filament\Resources\Pages\Page;

class MembershipPage extends Page
{
    protected static string $resource = MembershipResource::class;

    protected static string $view = 'filament.resources.membership-resource.pages.membership-page';
}
