<?php

namespace App\Models\Schemas\Category;
/**
 * @OA\Schema
 *     schema="CategoryResponse",
 *     type="object",
 *     title="CategoryResponse",
 *     description="Category model"
 * )
 */
class CategoryResponse
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $slug;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $status;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $parent_id;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $created_at;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $updated_at;
}
