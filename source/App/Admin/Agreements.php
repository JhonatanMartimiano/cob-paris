<?php

namespace Source\App\Admin;

use Source\Models\Agreement;
use Source\Models\Charge;
use Source\Models\Client;
use Source\Models\Ticket;
use Source\Support\Pager;

/**
 * Class Agreements
 * @package Source\App\Admin
 */
class Agreements extends Admin
{
    /**
     * Agreements constructor.
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
            echo json_encode(["redirect" => url("/admin/agreements/home/{$s}/1")]);
            return;
        }

        $search = null;
        $agreements = (new Agreement())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $agreements = (new Agreement())->find("name LIKE CONCAT('%', :s, '%')", "s={$search}");
            if (!$agreements->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/agreements/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/agreements/home/{$all}/"));
        $pager->pager($agreements->count(), 20, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Acordos",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/agreements/home", [
            "app" => "agreements/home",
            "head" => $head,
            "search" => $search,
            "agreements" => $agreements->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function agreement(?array $data): void
    {
        if ($_POST) {
            if (user()->level < 5) {
                $this->message->warning("Você não possui permissão para essa ação!")->flash();
                echo json_encode(["redirect" => url("/admin/agreements/home")]);
                return;
            }
        } else {
            if (user()->level < 5) {
                $this->message->warning('Você não possui permissão para essa ação!')->flash();
                redirect('admin/agreements/home');
                return;
            }
        }

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $agreementCreate = new Agreement();
            $agreementCreate->id_client = $data["id_client"];
            $agreementCreate->value = clean_mask($data['value']);
            $agreementCreate->installments = $data["installments"];
            $agreementCreate->form_payment = $data["form_payment"];
            $agreementCreate->communication_report = $data["communication_report"];
            $agreementCreate->created = date_fmt_back(date_fmt('now', 'Y-m-d'));

            if (!$agreementCreate->save()) {
                $json["message"] = $agreementCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $tickets = explode(',', $data['id_tickets']);

            if ($tickets) {
                for ($i=0; $i < count($tickets); $i++) { 
                    $id = intval($tickets[$i]);
                    $ticket = (new Ticket)->findById($id);
                    $ticket->situation = 'agreed';
                    $ticket->number_agreement = $agreementCreate->id;
                    
                    if (!$ticket->save()) {
                        $json["message"] = $ticket->message()->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }

            $this->message->success("Acordo cadastrado com sucesso...")->flash();
            $json["redirect"] = url("/admin/agreements/agreement/{$agreementCreate->id}");

            echo json_encode($json);
            return;
        }

        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $agreementUpdate = (new Agreement())->findById($data["agreement_id"]);

            if (!$agreementUpdate) {
                $this->message->error("Você tentou gerenciar um acordo que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/agreements/home")]);
                return;
            }

            $agreementUpdate->status = 'canceled';

            $tickets = (new Ticket)->find('id_agreement = :ida', "ida={$agreementUpdate->id}")->fetch(true);

            if ($tickets) {
                foreach ($tickets as $ticket) {
                    $ticket->status = 'canceled';
    
                    if (!$ticket->save()) {
                        $json["message"] = $ticket->message()->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }

            if (!$agreementUpdate->save()) {
                $json["message"] = $agreementUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Acordo cancelado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $agreementDelete = (new Agreement())->findById($data["agreement_id"]);

            if (!$agreementDelete) {
                $this->message->error("Você tentnou deletar um acordo que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/agreements/home")]);
                return;
            }

            $agreementDelete->destroy();

            $this->message->success("O acordo foi excluído com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/agreements/home")]);

            return;
        }

        $agreementEdit = null;
        $client = null;
        if (!empty($data["agreement_id"])) {
            $clientId = filter_var($data["agreement_id"], FILTER_VALIDATE_INT);
            $agreementEdit = (new Agreement())->findById($clientId);
            $client = (new Client)->findById($agreementEdit->id_client);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($agreementEdit ? "Perfil de {$agreementEdit->name}" : "Novo Acordo"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/agreements/agreement", [
            "app" => "agreements/home",
            "head" => $head,
            "agreement" => $agreementEdit,
            "client" => $client
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
            $clientId = (new Client)->find('cpf_cnpj = :cpf_cnpj', "cpf_cnpj={$cpf_cnpj}")->fetch()->id;

            if ($client->count()) {
                $json["client"] = $client->fetch()->data();
                $agreements = (new Ticket)->find('id_client = :idc AND situation = :sit', "idc={$clientId}&sit=negotiation")->fetch(true);
                $agreementsArr = null;
                $dueDaysArr = null;
                if ($agreements) {
                    foreach ($agreements as $agreement) {
                        $agreementsArr[] = $agreement->data();
                        $dueDaysArr[] = abs(date_diff_system($agreement->due_date));
                    }
                }
                $json["agreements"] = $agreementsArr;
                $json["dueDays"] = $dueDaysArr;
                echo json_encode($json);
                return;
            } else {
                $json['message'] = $this->message->warning("Cliente não encontrado!")->render();
                echo json_encode($json);
                return;
            }
        }
    }
}