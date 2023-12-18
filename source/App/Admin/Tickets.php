<?php

namespace Source\App\Admin;

use Source\Models\Agreement;
use Source\Models\Client;
use Source\Models\Ticket;
use Source\Support\Pager;

/**
 * Class Tickets
 * @package Source\App\Admin
 */
class Tickets extends Admin
{
    /**
     * Tickets constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array|null $data
     */
    public function home(?array $data): void
    {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/tickets/home/{$s}/1")]);
            return;
        }

        $search = null;
        $tickets = (new Ticket())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $client = (new Client)->find("cpf_cnpj = :s", "s={$search}")->fetch();
            if ($client) {
                $tickets = (new Ticket())->find('id_client = :idc', "idc={$client->id}");
            } else {
                $tickets = (new Ticket())->find('request_number = :rn', "rn={$search}");
            }
            if (!$tickets->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/tickets/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/tickets/home/{$all}/"));
        $pager->pager($tickets->count(), 20, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Boletos",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/tickets/home", [
            "app" => "tickets/home",
            "head" => $head,
            "search" => $search,
            "tickets" => $tickets->limit($pager->limit())->offset($pager->offset())->order("id DESC")->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function ticket(?array $data): void
    {
        if ($_POST) {
            if (user()->level < 5) {
                $this->message->warning("Você não possui permissão para essa ação!")->flash();
                echo json_encode(["redirect" => url("/admin/tickets/home")]);
                return;
            }
        } else {
            if (user()->level < 5) {
                $this->message->warning('Você não possui permissão para essa ação!')->flash();
                redirect('admin/tickets/home');
                return;
            }
        }

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $ticketCreate = new Ticket();
            $ticketCreate->id_client = $data["id_client"];
            $ticketCreate->ticket_number = $data["ticket_number"];
            $ticketCreate->bank_number = $data["bank_number"];
            $ticketCreate->request_number = $data["request_number"];
            $ticketCreate->value = clean_mask($data['value']);
            $ticketCreate->issue_date = date_fmt_back($data["issue_date"]);
            $ticketCreate->due_date = date_fmt_back($data["due_date"]);
            $ticketCreate->situation = 'open';
            if ($data["id_agreement"]) {
                $ticketCreate->id_agreement = $data["id_agreement"];
            }

            $currentDate = date_fmt('now', 'Y-m-d');
            $dueDate = date_fmt_back($data["due_date"]);
            if ($currentDate > $dueDate) {
                $json["message"] = $this->message->warning('Data de vencimento incorreta.')->render();
                echo json_encode($json);
                return;
            }

            $searchTicket = (new Ticket)->find('ticket_number = :tn', "tn={$ticketCreate->ticket_number}")->count();

            if (!$searchTicket) {
                if (!$ticketCreate->save()) {
                    $json["message"] = $ticketCreate->message()->render();
                    echo json_encode($json);
                    return;
                }

                $this->message->success("Boleto cadastrado com sucesso...")->flash();
                $json["redirect"] = url("/admin/tickets/ticket/{$ticketCreate->id}");
            } else {
                $this->message->warning("Você tentou criar um boleto que já existe")->flash();
                echo json_encode(["redirect" => url("/admin/tickets/home")]);
                return;
            }

            echo json_encode($json);
            return;
        }

        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $ticketUpdate = (new Ticket())->findById($data["ticket_id"]);

            if (!$ticketUpdate) {
                $this->message->error("Você tentou gerenciar um boleto que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/tickets/home")]);
                return;
            }

            $numberTicket = $ticketUpdate->ticket_number;

            $ticketUpdate->ticket_number = $data["ticket_number"];
            $ticketUpdate->bank_number = $data["bank_number"];
            $ticketUpdate->request_number = $data["request_number"];
            $ticketUpdate->value = preg_replace("/[^0-9]/", "", $data["value"]);
            $ticketUpdate->issue_date = date_fmt_back($data["issue_date"]);
            $ticketUpdate->due_date = date_fmt_back($data["due_date"]);
            $ticketUpdate->situation = 'open';
            if ($data["id_agreement"]) {
                $ticketUpdate->id_agreement = $data["id_agreement"];
            }

            $currentDate = date_fmt('now', 'Y-m-d');
            $dueDate = date_fmt_back($data["due_date"]);
            if ($currentDate > $dueDate) {
                $json["message"] = $this->message->warning('Data de vencimento incorreta.')->render();
                echo json_encode($json);
                return;
            }

            $searchTicket = (new Ticket)->find('ticket_number = :tn', "tn={$ticketUpdate->ticket_number}")->count();

            if ($searchTicket && $numberTicket == $data['ticket_number']) {
                if (!$ticketUpdate->save()) {
                    $json["message"] = $ticketUpdate->message()->render();
                    echo json_encode($json);
                    return;
                }
            } elseif (!$searchTicket) {
                if (!$ticketUpdate->save()) {
                    $json["message"] = $ticketUpdate->message()->render();
                    echo json_encode($json);
                    return;
                }
            } else {
                $this->message->warning("Você tentou criar um boleto que já existe")->flash();
                echo json_encode(["redirect" => url("/admin/tickets/home")]);
                return;
            }

            $this->message->success("Boleto atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $ticketDelete = (new Ticket())->findById($data["ticket_id"]);

            if (!$ticketDelete) {
                $this->message->error("Você tentnou deletar um boleto que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/tickets/home")]);
                return;
            }

            $ticketDelete->destroy();

            $this->message->success("O boleto foi excluído com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/tickets/home")]);

            return;
        }

        $ticketEdit = null;
        $agreements = null;
        if (!empty($data["ticket_id"])) {
            $ticketId = filter_var($data["ticket_id"], FILTER_VALIDATE_INT);
            $ticketEdit = (new Ticket())->findById($ticketId);
            if ($ticketEdit->id_agreement) {
                $agreements = (new Agreement)->find('id_client = :idc', "idc={$ticketEdit->id_client}")->fetch(true);
            }
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . "Novo Boleto",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/tickets/ticket", [
            "app" => "tickets/home",
            "head" => $head,
            "ticket" => $ticketEdit,
            "agreements" => $agreements
        ]);
    }

    /**
     * @param array|null $data
     * @return void
     */
    public function searchClient(?array $data): void
    {
        if ($data['cpf_cnpj']) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $cpf_cnpj = clean_mask($data['cpf_cnpj']);
            $client = (new Client)->find('cpf_cnpj = :cpf_cnpj', "cpf_cnpj={$cpf_cnpj}");
            $clientID = (new Client)->find('cpf_cnpj = :cpf_cnpj', "cpf_cnpj={$cpf_cnpj}")->fetch()->id;

            if ($client->count()) {
                $json["client"] = $client->fetch()->data();
                $agreements = (new Agreement)->find('id_client = :idc', "idc={$clientID}")->fetch(true);
                $agreementsArr = null;
                if (!empty($agreements)) {
                    foreach ($agreements as $agreement) {
                        $agreementsArr[] = $agreement->data();
                    }
                }
                $json["agreements"] = $agreementsArr;
                echo json_encode($json);
                return;
            } else {
                $json['message'] = $this->message->warning("Cliente não encontrado!")->render();
                echo json_encode($json);
                return;
            }
        }
    }

    public function othersTickets(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $ticket = (new Ticket())->findById($data["ticket_id"]);

            $ticketCreate = new Ticket();
            $ticketCreate->id_client = $ticket->id_client;
            $ticketCreate->ticket_number = $data["ticket_number"];
            $ticketCreate->bank_number = $ticket->bank_number;
            $ticketCreate->request_number = $ticket->request_number;
            $ticketCreate->value = $ticket->value;
            $ticketCreate->issue_date = $ticket->issue_date;
            $ticketCreate->due_date = $data["due_date"];
            $ticketCreate->situation = 'open';
            if ($data["id_agreement"]) {
                $ticketCreate->id_agreement = $data["id_agreement"];
            }

            $searchTicket = (new Ticket)->find('ticket_number = :tn', "tn={$ticketCreate->ticket_number}")->count();

            if (!$searchTicket) {
                if (!$ticketCreate->save()) {
                    $json["status"] = "warning";
                    echo json_encode($json);
                    return;
                }

                $json["status"] = "success";
            } else {
                $json["status"] = "warning";
                echo json_encode($json);
                return;
            }
            echo json_encode($json);
            return;
        }
    }
}
