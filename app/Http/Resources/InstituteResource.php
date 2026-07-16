<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstituteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'district'           => $this->district,
            'affiliation_type'   => $this->affiliation_type,
            'affiliation_number' => $this->affiliation_number,
            'status'             => $this->status,
            'seats_gnm'          => $this->seats_gnm,
            'seats_anm'          => $this->seats_anm,
            'principal_name'     => $this->principal_name,
            'contact_email'      => $this->contact_email,
            'contact_phone'      => $this->contact_phone,
            'address'            => $this->address,
            'approved_date'      => $this->approved_date?->format('Y-m-d'),
        ];
    }
}
