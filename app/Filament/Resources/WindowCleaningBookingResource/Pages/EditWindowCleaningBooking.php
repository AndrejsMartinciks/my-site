<?php

namespace App\Filament\Resources\WindowCleaningBookingResource\Pages;

use App\Filament\Resources\WindowCleaningBookingResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class EditWindowCleaningBooking extends EditRecord
{
    protected static string $resource = WindowCleaningBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (array_key_exists('addons', $data)) {
            $data['addons'] = $this->prettyJson($data['addons']);
        }

        if (array_key_exists('calculator_summary', $data)) {
            $data['calculator_summary'] = $this->prettyJson($data['calculator_summary']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return Arr::only($data, [
            'status',
            'manager_note',
        ]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $updateData = $data;

        if (($data['status'] ?? null) === 'contacted' && empty($record->contacted_at)) {
            $updateData['contacted_at'] = now();
        }

        if (($data['status'] ?? null) === 'done' && empty($record->done_at)) {
            $updateData['done_at'] = now();
        }

        if (($data['status'] ?? null) !== 'done' && $record->done_at && ($data['status'] ?? null) !== 'done') {
            $updateData['done_at'] = null;
        }

        $record->update($updateData);

        return $record;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Booking updated';
    }

    private function prettyJson(mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '';
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: $value;
            }

            return $value;
        }

        return '';
    }
}