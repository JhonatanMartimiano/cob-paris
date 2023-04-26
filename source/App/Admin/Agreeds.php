<?php

namespace Source\App\Admin;

use Source\Models\Client;
use Source\Models\Ticket;
use Source\Support\Pager;

/**
 * Class Agreeds
 * @package Source\App\Admin
 */
class Agreeds extends Admin
{
    /**
     * Agreeds constructor.
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
            echo json_encode(["redirect" => url("/admin/agreeds/home/{$s}/1")]);
            return;
        }

        $search = null;
        $agreeds = (new Ticket())->find("situation = 'agreed'");

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
            $agreeds = (new Ticket())->find("id_client = :idc AND situation = 'agreed'", "idc={$client->id}");
            if (!$agreeds->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/agreeds/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/agreeds/home/{$all}/"));
        $pager->pager($agreeds->count(), 20, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Acordados",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/agreeds/home", [
            "app" => "agreeds/home",
            "head" => $head,
            "search" => $search,
            "agreeds" => $agreeds->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    public function removeAgreed(?array $data): void
    {
        if ($_POST) {
            if (user()->level < 5) {
                $this->message->warning("Você não possui permissão para essa ação!")->flash();
                echo json_encode(["redirect" => url("/admin/agreeds/home")]);
                return;
            }
        } else {
            if (user()->level < 5) {
                $this->message->warning('Você não possui permissão para essa ação!')->flash();
                redirect('admin/agreeds/home');
                return;
            }
        }

        if (!empty($data["agreed_id"])) {
            $agreedID = filter_var($data["agreed_id"], FILTER_VALIDATE_INT);

            $agreed = (new Ticket)->findById($agreedID);

            $agreed->situation = 'open';
            $agreed->number_agreement = null;

            if (!$agreed->save()) {
                $json["message"] = $agreed->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Boleto removido do acordo com sucesso...")->flash();
            echo json_encode(["reload" => true]);

            return;
        }
    }
}