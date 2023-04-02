<?php

namespace Dbaeka\MsNotification\Services;

class CreateMessage
{
    /**
     * @return array<string,mixed>
     */
    public function execute(StatusUpdateData $data): array
    {
        return array_merge($this->getDefaultFields(), [
            'body' => [
                [
                    'type' => 'TextBlock',
                    'size' => 'medium',
                    'weight' => 'bolder',
                    'text' => 'Order Status Changed to ' . strtoupper($data->new_order_status)
                ],
                [
                    'type' => 'FactSet',
                    'facts' => [
                        ['title' => 'Order UUID:', 'value' => $data->order_uuid],
                        ['title' => 'Old Status:', 'value' => $data->old_order_status],
                        ['title' => 'New Status:', 'value' => $data->new_order_status],
                        ['title' => 'Date Updated:', 'value' => $data->updated_time->toDateTimeString()]
                    ]
                ]
            ],
        ]);
    }

    /**
     * @return array<string,mixed>
     */
    private function getDefaultFields(): array
    {
        return [
            'type' => 'AdaptiveCard',
            '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
            'version' => '1.4'
        ];
    }
}
