<?php

namespace Source\App\Admin;

use PHPExcel;
use Source\Models\Charge;
use Source\Models\Client;
use Source\Models\Ticket;
use Source\Support\Pager;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * Class Charges
 * @package Source\App\Admin
 */
class Charges extends Admin
{
    /**
     * Charges constructor.
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
            $_SESSION["filter"] = $data["s_select"];
            echo json_encode(["redirect" => url("/admin/charges/home/{$s}/1")]);
            return;
        }

        $search = null;
        $tickets = (new Ticket())->find("due_date - CURDATE() <= 3 AND situation = 'open' OR situation = 'negotiation' OR situation = 'courts' OR situation = 'protested' OR situation = 'protestedAgreed' OR situation = 'canceled' OR situation = 'defeated'");
        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $filter = $_SESSION["filter"];
            $search = str_search($data["search"]);
            if ($filter == "cpf_cnpj") {
                $client = (new Client())->find('cpf_cnpj = :s', "s={$search}")->fetch();
                $tickets = (new Ticket())->find("id_client = :idc AND (situation != 'finished' AND situation != 'lowForPayment' AND situation != 'agreed')", "idc={$client->id}");
            } elseif ($filter == "client") {
                $client = (new Client())->find("name LIKE CONCAT('%', :s, '%')", "s={$search}")->fetch();
                $tickets = (new Ticket())->find("id_client = :idc AND (situation != 'finished' AND situation != 'lowForPayment' AND situation != 'agreed')", "idc={$client->id}");
            } elseif ($filter == "ticket") {
                $tickets = (new Ticket())->find("ticket_number = :tn AND (situation != 'finished' AND situation != 'lowForPayment' AND situation != 'agreed')", "tn={$search}");
            } elseif ($filter == "order") {
                $tickets = (new Ticket())->find("request_number = :rn AND (situation != 'finished' AND situation != 'lowForPayment' AND situation != 'agreed')", "rn={$search}");
            }

            if (!$tickets->count()) {
                // unset($_SESSION["filter"]);
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/charges/home");
            }
            // unset($_SESSION["filter"]);
        }
        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/charges/home/{$all}/"));
        $pager->pager($tickets->count(), 20, (!empty($data["page"]) ? $data["page"] : 1));
        $head = $this->seo->render(
            CONF_SITE_NAME . " | Cobranças",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );
        echo $this->view->render("widgets/charges/home", [
            "app" => "charges/home",
            "head" => $head,
            "search" => $search,
            "tickets" => $tickets->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render(),
            "home" => true
        ]);
    }
    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function charge(?array $data): void
    {
        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $ticket = (new Ticket)->findById($data["ticket_id"]);
            $chargeCreate = new Charge();
            $chargeCreate->id_client = $ticket->id_client;
            $chargeCreate->id_ticket = $data["ticket_id"];
            $chargeCreate->downloaded = $data["downloaded"];
            $chargeCreate->charge_date = date_fmt_back($data["charge_date"]);
            $chargeCreate->payment_date = date_fmt_back($data["payment_date"]);
            $chargeCreate->form_payment = $data["form_payment"];
            $chargeCreate->communication_report = $data["communication_report"];
            $ticket->situation = $data["situation"];
            $ticket->discount_value = clean_mask($data["discount_value"]);
            $ticket->amount_paid = clean_mask($data["amount_paid"]);
            if (!$ticket->save()) {
                $json["message"] = $ticket->message()->render();
                echo json_encode($json);
                return;
            }

            //upload receipt
            if (!empty($_FILES["receipt"])) {
                $files = $_FILES["receipt"];
                $upload = new Upload();
                $image = $upload->image($files, $chargeCreate->getClient()->name, 600);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $chargeCreate->receipt = $image;
            }

            if (!$chargeCreate->save()) {
                $json["message"] = $chargeCreate->message()->render();
                echo json_encode($json);
                return;
            }
            $this->message->success("Cobrança cadastrada com sucesso...")->flash();
            $json["redirect"] = url("/admin/charges/charge/{$chargeCreate->id_ticket}");
            echo json_encode($json);
            return;
        }
        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $chargeUpdate = (new Charge())->find('id_ticket = :idt', "idt={$data['ticket_id']}")->fetch();
            $ticket = (new Ticket)->findById($data["ticket_id"]);
            if (!$chargeUpdate) {
                $this->message->error("Você tentou gerenciar uma cobrança que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/charges/home")]);
                return;
            }
            $chargeUpdate->id_client = $ticket->id_client;
            $chargeUpdate->id_ticket = $data["ticket_id"];
            $chargeUpdate->downloaded = $data["downloaded"];
            $chargeUpdate->charge_date = date_fmt_back($data["charge_date"]);
            $chargeUpdate->payment_date = date_fmt_back($data["payment_date"]);
            $chargeUpdate->form_payment = $data["form_payment"];
            $chargeUpdate->communication_report = $data["communication_report"];
            $ticket->situation = $data["situation"];
            $ticket->discount_value = clean_mask($data["discount_value"]);
            $ticket->amount_paid = clean_mask($data["amount_paid"]);
            if (!$ticket->save()) {
                $json["message"] = $ticket->message()->render();
                echo json_encode($json);
                return;
            }

            //upload receipt
            if (!empty($_FILES["receipt"])) {
                if ($chargeUpdate->receipt && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$chargeUpdate->receipt}")) {
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$chargeUpdate->receipt}");
                    (new Thumb())->flush($chargeUpdate->receipt);
                }

                $files = $_FILES["receipt"];
                $upload = new Upload();
                $image = $upload->image($files, $chargeUpdate->getClient()->name, 600);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $chargeUpdate->receipt = $image;
            }

            if (!$chargeUpdate->save()) {
                $json["message"] = $chargeUpdate->message()->render();
                echo json_encode($json);
                return;
            }
            $this->message->success("Cobrança atualizada com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }
        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            if ($_POST) {
                if (user()->level < 5) {
                    $this->message->warning("Você não possui permissão para essa ação!")->flash();
                    echo json_encode(["redirect" => url("/admin/charges/home")]);
                    return;
                }
            } else {
                if (user()->level < 5) {
                    $this->message->warning('Você não possui permissão para essa ação!')->flash();
                    redirect('admin/charges/home');
                    return;
                }
            }
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $chargeDelete = (new Charge())->findById($data["client_id"]);
            if (!$chargeDelete) {
                $this->message->error("Você tentnou deletar uma cobrança que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/charges/home")]);
                return;
            }
            $chargeDelete->destroy();
            $this->message->success("A cobrança foi excluída com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/charges/home")]);
            return;
        }
        $chargetEdit = null;
        if (!empty($data["ticket_id"])) {
            $ticketId = filter_var($data["ticket_id"], FILTER_VALIDATE_INT);
            $chargetEdit = (new Charge())->find('id_ticket = :idt', "idt={$ticketId}")->fetch();
        }
        $ticket = (new Ticket)->findById($data["ticket_id"]);
        $value = (date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0;

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($chargetEdit ? "Perfil de {$chargetEdit->name}" : "Novo Cobrança"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );
        echo $this->view->render("widgets/charges/charge", [
            "app" => "charges/home",
            "head" => $head,
            "charge" => $chargetEdit,
            "ticket" => $ticket,
            "value" => $value
        ]);
    }
    /**
     * @param array|null $data
     * @return void
     */
    public function filter(?array $data): void
    {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            $filter = $data['filter'];
            echo json_encode(["redirect" => url("/admin/charges/filter/{$filter}/{$s}/1")]);
            return;
        }
        $search = ($data['search']) ? str_search($data['search']) : null;
        $tickets = null;
        $query = null;
        $param = null;
        if (!$data['filter']) {
            $tickets = (new Ticket())->find("due_date - CURDATE() <= 3 AND situation = 'open' OR situation = 'negotiation' OR situation = 'courts' OR situation = 'protested' OR situation = 'protestedAgreed'");
        } else {
            if ($data['filter'] == 'due') {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                if (!$search) {
                    $tickets = (new Ticket())->find("due_date - CURDATE() < 0 AND situation = 'open' OR situation = 'defeated'");
                } elseif (!$client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->count()) {
                    $tickets = (new Ticket())->find("due_date - CURDATE() < 0 AND situation = 'open' OR situation = 'defeated'");
                } else {
                    $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
                    $query = "due_date - CURDATE() < 0 AND id_client = :idc AND (situation = 'open' OR situation = 'defeated')";
                    $param = "idc={$client->id}";
                }
            }
            if ($data['filter'] == 'towin') {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                if (!$search) {
                    $tickets = (new Ticket())->find("due_date - CURDATE() <= 3 AND due_date - CURDATE() >= 0 AND situation = 'open'");
                } elseif (!$client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->count()) {
                    $tickets = (new Ticket())->find("due_date - CURDATE() <= 3 AND due_date - CURDATE() >= 0 AND situation = 'open'");
                } else {
                    $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
                    $query = "due_date - CURDATE() <= 3 AND due_date - CURDATE() >= 0 AND id_client = :idc AND situation = 'open'";
                    $param = "idc={$client->id}";
                }
            }
            if ($data['filter'] == 'negotiation') {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                if (!$search) {
                    $tickets = (new Ticket())->find("situation = 'negotiation'");
                } elseif (!$client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->count()) {
                    $tickets = (new Ticket())->find("situation = 'negotiation'");
                } else {
                    $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
                    $query = "id_client = :idc AND situation = 'negotiation'";
                    $param = "idc={$client->id}";
                }
            }
            if ($data['filter'] == 'courts') {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                if (!$search) {
                    $tickets = (new Ticket())->find("situation = 'courts'");
                } elseif (!$client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->count()) {
                    $tickets = (new Ticket())->find("situation = 'courts'");
                } else {
                    $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
                    $query = "id_client = :idc AND situation = 'courts'";
                    $param = "idc={$client->id}";
                }
            }
            if ($data['filter'] == 'protested') {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                if (!$search) {
                    $tickets = (new Ticket())->find("situation = 'protested'");
                } elseif (!$client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->count()) {
                    $tickets = (new Ticket())->find("situation = 'protested'");
                } else {
                    $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
                    $query = "id_client = :idc AND situation = 'protested'";
                    $param = "idc={$client->id}";
                }
            }
            if ($data['filter'] == 'canceled') {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                if (!$search) {
                    $tickets = (new Ticket())->find("situation = 'canceled'");
                } elseif (!$client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->count()) {
                    $tickets = (new Ticket())->find("situation = 'canceled'");
                } else {
                    $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
                    $query = "id_client = :idc AND situation = 'canceled'";
                    $param = "idc={$client->id}";
                }
            }
            if ($data['filter'] == 'open') {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                if (!$search) {
                    $tickets = (new Ticket())->find("due_date - CURDATE() > 3 AND situation = 'open'");
                } elseif (!$client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->count()) {
                    $tickets = (new Ticket())->find("due_date - CURDATE() > 3 AND situation = 'open'");
                } else {
                    $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
                    $query = "due_date - CURDATE() > 3 AND id_client = :idc AND situation = 'open'";
                    $param = "idc={$client->id}";
                }
            }
            if ($data['filter'] == 'protested-agreed') {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                if (!$search) {
                    $tickets = (new Ticket())->find("situation = 'protestedAgreed'");
                } elseif (!$client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->count()) {
                    $tickets = (new Ticket())->find("situation = 'protestedAgreed'");
                } else {
                    $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();
                    $query = "id_client = :idc AND situation = 'protestedAgreed'";
                    $param = "idc={$client->id}";
                }
            }
        }
        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $tickets = (new Ticket())->find($query, $param);
            if (!$tickets->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/charges/home");
            }
        }
        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/charges/filter/{$data['filter']}/{$all}/"));
        $pager->pager($tickets->count(), 20, (!empty($data["page"]) ? $data["page"] : 1));
        $head = $this->seo->render(
            CONF_SITE_NAME . " | Cobranças",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );
        echo $this->view->render("widgets/charges/home", [
            "app" => "charges/home",
            "head" => $head,
            "search" => $search,
            "tickets" => $tickets->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render(),
            "filter" => $data['filter']
        ]);
    }

    /**
     * @return void
     */
    public function report(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data) {
            if ($data["status"] == "due") {
                $header = ["ID", "NOME", "CPF/CNPJ", mb_convert_encoding("NÚMERO DO BOLETO", "ISO-8859-1", "UTF-8"), mb_convert_encoding("DATA DE EMISSÃO", "ISO-8859-1", "UTF-8"), "DATA DE VENCIMENTO"];
                $file = fopen("reports/boleto-vencido.csv", "w");
                fputcsv($file, $header, ";");
                $tickets = (new Ticket())->find("due_date - CURDATE() < 0 AND situation = 'open' OR situation = 'defeated'")->fetch(true);
                foreach ($tickets as $ticket) {
                    $row = [$ticket->id, $ticket->getClient()->name, $ticket->getClient()->cpf_cnpj, $ticket->ticket_number, $ticket->issue_date, $ticket->due_date];
                    fputcsv($file, $row, ";");
                }
                $file = "reports/boleto-vencido.csv";
                $content = str_replace('"', "", file_get_contents("reports/boleto-vencido.csv"));
                $openFile = fopen($file, "w");
                fwrite($openFile, $content);
                fclose($openFile);

                echo json_encode(url() . "/reports/boleto-vencido.csv");
            } elseif ($data["status"] == "towin") {
                $header = ["ID", "NOME", "CPF/CNPJ", mb_convert_encoding("NÚMERO DO BOLETO", "ISO-8859-1", "UTF-8"), mb_convert_encoding("DATA DE EMISSÃO", "ISO-8859-1", "UTF-8"), "DATA DE VENCIMENTO"];
                $file = fopen("reports/boleto-a-vencer.csv", "w");
                fputcsv($file, $header, ";");
                $tickets = (new Ticket())->find("due_date - CURDATE() <= 3 AND due_date - CURDATE() >= 0 AND situation = 'open'")->fetch(true);
                foreach ($tickets as $ticket) {
                    $row = [$ticket->id, $ticket->getClient()->name, $ticket->getClient()->cpf_cnpj, $ticket->ticket_number, $ticket->issue_date, $ticket->due_date];
                    fputcsv($file, $row, ";");
                }
                $file = "reports/boleto-a-vencer.csv";
                $content = str_replace('"', "", file_get_contents("reports/boleto-a-vencer.csv"));
                $openFile = fopen($file, "w");
                fwrite($openFile, $content);
                fclose($openFile);

                echo json_encode(url() . "/reports/boleto-a-vencer.csv");
            } elseif ($data["status"] == "negotiation") {
                $header = ["ID", "NOME", "CPF/CNPJ", mb_convert_encoding("NÚMERO DO BOLETO", "ISO-8859-1", "UTF-8"), mb_convert_encoding("DATA DE EMISSÃO", "ISO-8859-1", "UTF-8"), "DATA DE VENCIMENTO"];
                $file = fopen("reports/em-negociacao.csv", "w");
                fputcsv($file, $header, ";");
                $tickets = (new Ticket())->find("situation = 'negotiation'")->fetch(true);
                foreach ($tickets as $ticket) {
                    $row = [$ticket->id, $ticket->getClient()->name, $ticket->getClient()->cpf_cnpj, $ticket->ticket_number, $ticket->issue_date, $ticket->due_date];
                    fputcsv($file, $row, ";");
                }
                $file = "reports/em-negociacao.csv";
                $content = str_replace('"', "", file_get_contents("reports/em-negociacao.csv"));
                $openFile = fopen($file, "w");
                fwrite($openFile, $content);
                fclose($openFile);

                echo json_encode(url() . "/reports/em-negociacao.csv");
            } elseif ($data["status"] == "courts") {
                $header = ["ID", "NOME", "CPF/CNPJ", mb_convert_encoding("NÚMERO DO BOLETO", "ISO-8859-1", "UTF-8"), mb_convert_encoding("DATA DE EMISSÃO", "ISO-8859-1", "UTF-8"), "DATA DE VENCIMENTO"];
                $file = fopen("reports/juizado.csv", "w");
                fputcsv($file, $header, ";");
                $tickets = (new Ticket())->find("situation = 'courts'")->fetch(true);
                foreach ($tickets as $ticket) {
                    $row = [$ticket->id, $ticket->getClient()->name, $ticket->getClient()->cpf_cnpj, $ticket->ticket_number, $ticket->issue_date, $ticket->due_date];
                    fputcsv($file, $row, ";");
                }
                $file = "reports/juizado.csv";
                $content = str_replace('"', "", file_get_contents("reports/juizado.csv"));
                $openFile = fopen($file, "w");
                fwrite($openFile, $content);
                fclose($openFile);

                echo json_encode(url() . "/reports/juizado.csv");
            } elseif ($data["status"] == "protested") {
                $header = ["ID", "NOME", "CPF/CNPJ", mb_convert_encoding("NÚMERO DO BOLETO", "ISO-8859-1", "UTF-8"), mb_convert_encoding("DATA DE EMISSÃO", "ISO-8859-1", "UTF-8"), "DATA DE VENCIMENTO"];
                $file = fopen("reports/protestado.csv", "w");
                fputcsv($file, $header, ";");
                $tickets = (new Ticket())->find("situation = 'protested'")->fetch(true);
                foreach ($tickets as $ticket) {
                    $row = [$ticket->id, $ticket->getClient()->name, $ticket->getClient()->cpf_cnpj, $ticket->ticket_number, $ticket->issue_date, $ticket->due_date];
                    fputcsv($file, $row, ";");
                }
                $file = "reports/protestado.csv";
                $content = str_replace('"', "", file_get_contents("reports/protestado.csv"));
                $openFile = fopen($file, "w");
                fwrite($openFile, $content);
                fclose($openFile);

                echo json_encode(url() . "/reports/protestado.csv");
            } elseif ($data["status"] == "canceled") {
                $header = ["ID", "NOME", "CPF/CNPJ", mb_convert_encoding("NÚMERO DO BOLETO", "ISO-8859-1", "UTF-8"), mb_convert_encoding("DATA DE EMISSÃO", "ISO-8859-1", "UTF-8"), "DATA DE VENCIMENTO"];
                $file = fopen("reports/cancelado.csv", "w");
                fputcsv($file, $header, ";");
                $tickets = (new Ticket())->find("situation = 'canceled'")->fetch(true);
                foreach ($tickets as $ticket) {
                    $row = [$ticket->id, $ticket->getClient()->name, $ticket->getClient()->cpf_cnpj, $ticket->ticket_number, $ticket->issue_date, $ticket->due_date];
                    fputcsv($file, $row, ";");
                }
                $file = "reports/cancelado.csv";
                $content = str_replace('"', "", file_get_contents("reports/cancelado.csv"));
                $openFile = fopen($file, "w");
                fwrite($openFile, $content);
                fclose($openFile);

                echo json_encode(url() . "/reports/cancelado.csv");
            } elseif ($data["status"] == "open") {
                $header = ["ID", "NOME", "CPF/CNPJ", mb_convert_encoding("NÚMERO DO BOLETO", "ISO-8859-1", "UTF-8"), mb_convert_encoding("DATA DE EMISSÃO", "ISO-8859-1", "UTF-8"), "DATA DE VENCIMENTO"];
                $file = fopen("reports/boletos-em-aberto.csv", "w");
                fputcsv($file, $header, ";");
                $tickets = (new Ticket())->find("due_date - CURDATE() > 3 AND situation = 'open'")->fetch(true);
                foreach ($tickets as $ticket) {
                    $row = [$ticket->id, $ticket->getClient()->name, $ticket->getClient()->cpf_cnpj, $ticket->ticket_number, $ticket->issue_date, $ticket->due_date];
                    fputcsv($file, $row, ";");
                }
                $file = "reports/boletos-em-aberto.csv";
                $content = str_replace('"', "", file_get_contents("reports/boletos-em-aberto.csv"));
                $openFile = fopen($file, "w");
                fwrite($openFile, $content);
                fclose($openFile);

                echo json_encode(url() . "/reports/boletos-em-aberto.csv");
            } elseif ($data["status"] == "protestedAgreed") {
                $header = ["ID", "NOME", "CPF/CNPJ", mb_convert_encoding("NÚMERO DO BOLETO", "ISO-8859-1", "UTF-8"), mb_convert_encoding("DATA DE EMISSÃO", "ISO-8859-1", "UTF-8"), "DATA DE VENCIMENTO"];
                $file = fopen("reports/protestado-acordado.csv", "w");
                fputcsv($file, $header, ";");
                $tickets = (new Ticket())->find("situation = 'protestedAgreed'")->fetch(true);
                foreach ($tickets as $ticket) {
                    $row = [$ticket->id, $ticket->getClient()->name, $ticket->getClient()->cpf_cnpj, $ticket->ticket_number, $ticket->issue_date, $ticket->due_date];
                    fputcsv($file, $row, ";");
                }
                $file = "reports/protestado-acordado.csv";
                $content = str_replace('"', "", file_get_contents("reports/protestado-acordado.csv"));
                $openFile = fopen($file, "w");
                fwrite($openFile, $content);
                fclose($openFile);

                echo json_encode(url() . "/reports/protestado-acordado.csv");
            }
        }
    }
}
