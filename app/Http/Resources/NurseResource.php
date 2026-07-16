<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NurseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'registration_number' => $this->registration_number,
            'name'                => $this->name,
            'qualification'       => $this->qualification,
            'registration_date'   => $this->registration_date?->format('d M Y'),
            'expiry_date'         => $this->expiry_date?->format('d M Y'),
            'status'              => $this->status,
            'district'            => $this->district,
            'state'               => $this->state,
            'institute'           => $this->whenLoaded('institute', fn() => [
                'name'     => $this->institute?->name,
                'district' => $this->institute?->district,
            ]),
            // Hide sensitive personal data from public verification endpoint
            // Phone, email, full address are NOT returned on public verify
        ];
    }
}
