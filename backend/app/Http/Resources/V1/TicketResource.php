<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workerId' => $this->worker_id,
            'department' => $this->department,
            'message' => $this->message,
            'response' => $this->response,
            'status' => $this->status,
            'priority' => $this->priority,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'openedAt' => $this->opened_at,
            'closedAt' => $this->closed_at,
            'files' => FileResource::collection($this->whenLoaded('files'))
        ];
    }
}
