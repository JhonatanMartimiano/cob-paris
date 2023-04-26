<?php

namespace Source\App\Admin;

use Source\Models\Client;
use Source\Support\Pager;

/**
 * Class Clients
 * @package Source\App\Admin
 */
class Clients extends Admin
{
    /**
     * Clients constructor.
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
            echo json_encode(["redirect" => url("/admin/clients/home/{$s}/1")]);
            return;
        }

        $search = null;
        $clients = (new Client())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $clients = (new Client())->find("name LIKE CONCAT('%', :s, '%') OR cpf_cnpj LIKE CONCAT('%', :s, '%')", "s={$search}");
            if (!$clients->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/clients/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/clients/home/{$all}/"));
        $pager->pager($clients->count(), 20, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Clientes",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/clients/home", [
            "app" => "clients/home",
            "head" => $head,
            "search" => $search,
            "clients" => $clients->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function client(?array $data): void
    {
        if ($_POST) {
            if (user()->level < 5) {
                $this->message->warning("Você não possui permissão para essa ação!")->flash();
                echo json_encode(["redirect" => url("/admin/clients/home")]);
                return;
            }
        } else {
            if (user()->level < 5) {
                $this->message->warning('Você não possui permissão para essa ação!')->flash();
                redirect('admin/clients/home');
                return;
            }
        }

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $clientCreate = new Client();
            $clientCreate->name = $data["name"];
            $clientCreate->cpf_cnpj = clean_mask($data["cpf_cnpj"]);

            $searchClient = (new Client)->find('cpf_cnpj = :cpf_cnpj', "cpf_cnpj={$clientCreate->cpf_cnpj}")->count();

            if (!$searchClient) {
                if (!$clientCreate->save()) {
                    $json["message"] = $clientCreate->message()->render();
                    echo json_encode($json);
                    return;
                }

                $this->message->success("Cliente cadastrado com sucesso...")->flash();
                $json["redirect"] = url("/admin/clients/client/{$clientCreate->id}");
            } else {
                $this->message->warning("Você tentou criar um cliente que já existe")->flash();
                echo json_encode(["redirect" => url("/admin/clients/home")]);
                return;
            }

            echo json_encode($json);
            return;
        }

        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $clientUpdate = (new Client())->findById($data["client_id"]);

            if (!$clientUpdate) {
                $this->message->error("Você tentou gerenciar um cliente que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/clients/home")]);
                return;
            }

            $clientUpdate->name = $data["name"];
            $clientUpdate->cpf_cnpj = clean_mask($data["cpf_cnpj"]);

            if (!$clientUpdate->save()) {
                $json["message"] = $clientUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Cliente atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $clientDelete = (new Client())->findById($data["client_id"]);

            if (!$clientDelete) {
                $this->message->error("Você tentnou deletar um cliente que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/clients/home")]);
                return;
            }

            $clientDelete->destroy();

            $this->message->success("O cliente foi excluído com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/clients/home")]);

            return;
        }

        $clientEdit = null;
        if (!empty($data["client_id"])) {
            $clientId = filter_var($data["client_id"], FILTER_VALIDATE_INT);
            $clientEdit = (new Client())->findById($clientId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($clientEdit ? "Perfil de {$clientEdit->name}" : "Novo Cliente"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/clients/client", [
            "app" => "clients/home",
            "head" => $head,
            "client" => $clientEdit
        ]);
    }
}
