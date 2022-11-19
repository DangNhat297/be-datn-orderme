<?php
    namespace App\Models\Schemas\Setting;

    /**
     * @OA\Schema(
     *      schema="SettingRespone",
     *      title="Setting Request",
     *      description="Setting model",
     *      type="object",
     *      required={"name"}
     * )
     *
     */

    class SettingResponse{
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
        public $phone;
        /**
         * @OA\Property()
         *
         * @var string
         */
        public $email;
        /**
         * @OA\Property()
         *
         * @var string
         */
        public $logo;
        /**
         * @OA\Property()
         *
         * @var string
         */
        public $address;
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
        /**
         * @OA\Property()
         *
         * @var integer
         */
        private $id;


    }

