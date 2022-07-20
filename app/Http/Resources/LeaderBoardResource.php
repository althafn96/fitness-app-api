<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaderBoardResource extends JsonResource
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
            'fullName' => $this->user->first_name . " " . $this->user->last_name,
            'displayPicture' => $this->user->display_picture,
            'stepsCount' => $this->steps_count,
            'isCurrentUser' => $this->user_id === auth()->id(),
            'rank' => $this->rank
        ];
    }
}
