<?php

namespace App\Core\Data;

use Illuminate\Database\Eloquent\Model;

interface Repository
{
    /**
     * Get model
     *
     * @return Model
     */
    function getModel(): Model;

    /**
     * Get file model translation
     *
     * @return String
     */
    public function getTransFileName() : String;
}
