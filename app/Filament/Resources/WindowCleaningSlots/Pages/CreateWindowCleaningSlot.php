<?php

namespace App\Filament\Resources\WindowCleaningSlots\Pages;

use App\Filament\Resources\WindowCleaningSlots\WindowCleaningSlotResource;
use App\Models\Service;
use Filament\Resources\Pages\CreateRecord;

class CreateWindowCleaningSlot extends CreateRecord
{
    protected static string $resource = WindowCleaningSlotResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['service_id'] = Service::query()
            ->where('slug', 'fonsterputsning')
            ->value('id');

        return $data;
    }
}