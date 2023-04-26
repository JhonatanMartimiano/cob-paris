<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * @package Source\Models
 */
class Agreement extends Model
{

    /**
     * Agreement constructor.
     */
    public function __construct()
    {
        parent::__construct('agreements', ['id'], ['id_client']);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return (new Client)->findById($this->id_client);
    }
}