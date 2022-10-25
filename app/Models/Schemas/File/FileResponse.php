<?php

namespace App\Models\Schemas\File;

/**
 * @OA\Schema
 *     schema="FileResponse",
 *     type="object",
 *     title="FileResponse",
 *     description="File model"
 * )
 */
class FileResponse
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $path;
}

?>
