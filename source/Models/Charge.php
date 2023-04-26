<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * @package Source\Models
 */
class Charge extends Model
{
    /**
     * Charge constructor.
     */
    public function __construct()
    {
        parent::__construct('charges', ['id'], ['id_ticket']);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        $ticket = (new Ticket)->findById($this->id_ticket);
        return (new Client)->findById($ticket->id_client);
    }

    public function getTicket()
    {
        return (new Ticket)->findById($this->id_ticket);
    }
}