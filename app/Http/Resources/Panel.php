<?php

namespace App\Http\Resources;

use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Panel extends JsonResource
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
            'dimensions' => $this->dimensions . ' m',
            'format' => $this->format,
            'price' => formatDecimalNumber($this->price) . ' $',
            'location' => $this->location,
            'faces' => Face::collection($this->faces),
            'created_by' => !empty($this->created_by) ? ModelsUser::find($this->created_by) : $this->created_by,
            'updated_by' => !empty($this->updated_by) ? ModelsUser::find($this->updated_by) : $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
