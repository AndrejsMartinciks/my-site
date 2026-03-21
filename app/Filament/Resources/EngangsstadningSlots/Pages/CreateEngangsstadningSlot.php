<?php

namespace App\Filament\Resources\EngangsstadningSlots\Pages;

use App\Filament\Resources\EngangsstadningSlots\EngangsstadningSlotResource;
use App\Models\Service;
use Filament\Resources\Pages\CreateRecord;

class CreateEngangsstadningSlot extends CreateRecord
{
    protected static string $resource = EngangsstadningSlotResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['service_id'] = Service::query()
            ->where('slug', 'engangsstadning')
            ->value('id');

        return $data;
    }
}