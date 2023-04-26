<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * @package Source\Models;
 */
class Ticket extends Model
{
    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        parent::__construct('tickets', ['id'], ['id_client', 'situation']);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return (new Client)->findById($this->id_client);
    }

    /**
     * @return Charge
     */
    public function getCharge(): Charge
    {
        return (new Charge())->find('id_ticket = :idc', "idc={$this->id}")->fetch();
    }
}