<?php

namespace Source\App\Admin;

use Source\Models\Client;
use Source\Models\Ticket;
use Source\Support\Pager;

/**
 * Class Reports
 * @package Source\App\Admin
 */
class Reports extends Admin
{
    /**
     * Reports constructor.
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
        $tickets = (new Ticket())->find();
        if (!empty($data) && !empty($data['start_date']) && !empty($data['due_date'])) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $tickets = (new Ticket())->find('due_date BETWEEN :sd AND :dd', "sd={$data['start_date']}&dd={$data['due_date']}");
        }

        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/reports/home/{$s}/1")]);
            return;
        }

        $search = null;
        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $tickets = (new Ticket())->find("name LIKE CONCAT('%', :s, '%') OR cpf_cnpj LIKE CONCAT('%', :s, '%')", "s={$search}");
            if (!$tickets->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/reports/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/reports/home/{$all}/"));
        $pager->pager($tickets->count(), 20, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Relatórios",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/reports/home", [
            "app" => "reports/home",
            "head" => $head,
            "search" => $search,
            "tickets" => $tickets->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "finished" => (new Ticket)->find('situation = :sit', "sit=finished")->count(),
            "due" => (new Ticket)->find("due_date - CURDATE() < 0 AND (situation = 'open' OR situation = 'defeated')")->count(),
            "agreed" => (new Ticket)->find('situation = :sit', "sit=negotiation")->count(),
            "open" => (new Ticket)->find('situation = :sit AND due_date - CURDATE() >= 4', "sit=open")->count(),
            "toWin" => (new Ticket)->find('situation = :sit AND due_date - CURDATE() <= 3', "sit=open")->count(),
            "lowForPayment" => (new Ticket)->find('situation = :sit', "sit=lowForPayment")->count(),
            "courts" => (new Ticket)->find('situation = :sit', "sit=courts")->count(),
            "protested" => (new Ticket)->find('situation = :sit', "sit=protested")->count(),
            "canceled" => (new Ticket)->find('situation = :sit', "sit=canceled")->count(),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function report(?array $data): void
    {
        $clientEdit = null;
        if (!empty($data["client_id"])) {
            $clientId = filter_var($data["client_id"], FILTER_VALIDATE_INT);
            $clientEdit = (new Client())->findById($clientId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . "Relatório",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );


        echo $this->view->render("widgets/reports/report", [
            "app" => "reports/home",
            "head" => $head,
            "client" => $clientEdit,
            "tickets" => (new Ticket)->find('id_client = :idc', "idc={$clientEdit->id}")->fetch(true),
            "finished" => (new Ticket)->find('id_client = :idc AND situation = :sit', "idc={$clientEdit->id}&sit=finished")->count(),
            "due" => (new Ticket)->find("id_client = :idc AND due_date - CURDATE() < 0 AND (situation = 'open' OR situation = 'defeated')", "idc={$clientEdit->id}")->count(),
            "agreed" => (new Ticket)->find('id_client = :idc AND situation = :sit', "idc={$clientEdit->id}&sit=negotiation")->count(),
            "open" => (new Ticket)->find('id_client = :idc AND situation = :sit AND due_date - CURDATE() >= 4', "idc={$clientEdit->id}&sit=open")->count(),
            "toWin" => (new Ticket)->find('id_client = :idc AND situation = :sit AND due_date - CURDATE() <= 3', "idc={$clientEdit->id}&sit=open")->count(),
            "lowForPayment" => (new Ticket)->find('id_client = :idc AND situation = :sit', "idc={$clientEdit->id}&sit=lowForPayment")->count(),
            "courts" => (new Ticket)->find('id_client = :idc AND situation = :sit', "idc={$clientEdit->id}&sit=courts")->count(),
            "protested" => (new Ticket)->find('id_client = :idc AND situation = :sit', "idc={$clientEdit->id}&sit=protested")->count(),
            "canceled" => (new Ticket)->find('id_client = :idc AND situation = :sit', "idc={$clientEdit->id}&sit=canceled")->count()
        ]);
    }
}