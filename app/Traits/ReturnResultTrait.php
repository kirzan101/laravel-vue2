<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\JsonResource;

trait ReturnResultTrait
{
    /**
     * set model results
     *
     * @param integer|null $code
     * @param string|null $status
     * @param string|null $message
     * @param integer|null $last_id
     * @param [type] $result
     * @return array
     */
    public function returnResult(?int $code = null, ?string $status = null, ?string $message = null, ?int $last_id = null, JsonResource $result = null): array
    {
        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'last_id' => $last_id,
            'result' => $result
        ];
    }
}
