<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'invoice_number'  => $this->invoice_number,
            'total_price'     => $this->total_price,
            'status'          => $this->status,
            'payment_method'  => $this->payment_method,
            'paid_amount'     => $this->paid_amount,
            'change_amount'   => $this->change_amount,
            'notes'           => $this->notes,
            'items'           => $this->whenLoaded('details', function () {
                return $this->details->map(fn($d) => [
                    'product_id'   => $d->product_id,
                    'product_name' => $d->product_name,
                    'price'        => $d->price,
                    'qty'          => $d->qty,
                    'subtotal'     => $d->subtotal,
                ]);
            }),
            'created_at'      => $this->created_at?->toIso8601String(),
        ];
    }
}
