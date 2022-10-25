<?php

namespace App\Models\Schemas\File;

/**
 * @OA\Schema
 *     schema="FileUploadRequest",
 *     type="object",
 *     title="FileUploadRequest",
 *     description="File model"
 * )
 */
class FileUploadRequest
{
    /**
     * @OA\Property()
     *
     * @var object
     */
    public $file;
}

?>
