<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * @package Source\Models;
 */
class Log extends Model
{
    /**
     * Log constructor.
     */
    public function __construct()
    {
        parent::__construct('logs', ['id'], ['user_id', 'action']);
    }
}