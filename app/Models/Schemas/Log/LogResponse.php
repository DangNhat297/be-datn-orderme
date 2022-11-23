<?php
namespace App\Models\Schemas\Log;

/**
 * @OA\Schema(
 *      schema="LogResponse",
 *      title="Log Data",
 *      description="Log model",
 *      type="object",
 * )
 *
 */
class LogResponse
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $status;

    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/UserResponse"
     * )
     *
     * @var object
     */
    public $change_by;
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

?>
