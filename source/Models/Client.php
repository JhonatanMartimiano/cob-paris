<?php

namespace Source\Models;

use Source\Core\Model;

class Client extends Model
{
    public function __construct()
    {
        parent::__construct('clients', ['id'], ['name']);
    }

    /**
     * @return Ticket
     */
    public function getTicket(): Ticket
    {
        return (new Ticket)->find('id_client = :idc', "idc={$this->id}")->fetch();
    }
}