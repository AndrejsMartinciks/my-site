<?php

namespace App\Filament\Resources\WindowCleaningSlots\Pages;

use App\Filament\Resources\WindowCleaningSlots\WindowCleaningSlotResource;
use App\Models\Service;
use Filament\Resources\Pages\EditRecord;

class EditWindowCleaningSlot extends EditRecord
{
    protected static string $resource = WindowCleaningSlotResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['service_id'] = Service::query()
            ->where('slug', 'fonsterputsning')
            ->value('id');

        return $data;
    }
}