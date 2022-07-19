<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'full_name' => $this->first_name . " " . $this->last_name,
            'display_picture' => $this->display_picture,
            'stepsCount' => $this->currentDayStepsCount,
            'isCurrentUser' => $this->id === auth()->id()
        ];
    }
}
