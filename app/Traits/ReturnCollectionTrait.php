<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ReturnCollectionTrait
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
    public function returnCollection(?int $code = null, ?string $status = null, ?string $message = null, AnonymousResourceCollection $results = null): array
    {
        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'results' => $results
        ];
    }
}
