<?php



namespace Source\App\Admin;



use Source\Models\Charge;

use Source\Models\Client;

use Source\Models\Ticket;

use Source\Support\Pager;



/**

 * Class Finisheds

 * @package Source\App\Admin

 */

class Finisheds extends Admin

{

    /**

     * Finisheds constructor.

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

        $ticketsDate = null;

        if (!empty($data) && !empty($data['start_date']) && !empty($data['due_date'])) {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $ticketsDate = (new Charge())->find('payment_date BETWEEN :sd AND :dd', "sd={$data['start_date']}&dd={$data['due_date']}")->fetch(true);

        }



        //search redirect

        if (!empty($data["s"])) {

            $s = str_search($data["s"]);

            echo json_encode(["redirect" => url("/admin/finisheds/home/{$s}/1")]);

            return;

        }



        $search = null;

        $tickets = (new Ticket())->find("situation = 'finished' OR situation = 'lowForPayment'");



        if (!empty($data["search"]) && str_search($data["search"]) != "all") {

            $search = str_search($data["search"]);

            $client = (new Client)->find('cpf_cnpj = :doc', "doc={$search}")->fetch();

            $tickets = (new Ticket())->find("id_client = :idc AND (situation = 'finished' OR situation = 'lowForPayment')", "idc={$client->id}");

            if (!$tickets->count()) {

                $this->message->info("Sua pesquisa não retornou resultados")->flash();

                redirect("/admin/finisheds/home");

            }

        }



        $all = ($search ?? "all");

        $pager = new Pager(url("/admin/finisheds/home/{$all}/"));

        $pager->pager($tickets->count(), 20, (!empty($data["page"]) ? $data["page"] : 1));



        $head = $this->seo->render(

            CONF_SITE_NAME . " | Pagos",

            CONF_SITE_DESC,

            url("/admin"),

            url("/admin/assets/images/image.jpg"),

            false

        );



        echo $this->view->render("widgets/finisheds/home", [

            "app" => "finisheds/home",

            "head" => $head,

            "search" => $search,

            "tickets" => (!$ticketsDate) ? $tickets->limit($pager->limit())->offset($pager->offset())->fetch(true) : null,

            "ticketsDate" => $ticketsDate,

            "date" => $data,

            "paginator" => $pager->render()

        ]);

    }



    /**

     * @param array|null $data

     * @throws \Exception

     */

    public function finished(?array $data): void

    {

        if ($_POST) {

            if (user()->level < 5) {

                $this->message->warning("Você não possui permissão para essa ação!")->flash();

                echo json_encode(["redirect" => url("/admin/finisheds/home")]);

                return;

            }

        } else {

            if (user()->level < 5) {

                $this->message->warning('Você não possui permissão para essa ação!')->flash();

                redirect('admin/finisheds/home');

                return;

            }

        }



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



            if (!$chargeCreate->save()) {

                $json["message"] = $chargeCreate->message()->render();

                echo json_encode($json);

                return;

            }



            $this->message->success("Cobrança cadastrada com sucesso...")->flash();

            $json["redirect"] = url("/admin/finisheds/finished/{$chargeCreate->id_ticket}");



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

                echo json_encode(["redirect" => url("/admin/finisheds/home")]);

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

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $chargeDelete = (new Charge())->findById($data["client_id"]);



            if (!$chargeDelete) {

                $this->message->error("Você tentnou deletar uma cobrança que não existe")->flash();

                echo json_encode(["redirect" => url("/admin/finisheds/home")]);

                return;

            }



            $chargeDelete->destroy();



            $this->message->success("A cobrança foi excluída com sucesso...")->flash();

            echo json_encode(["redirect" => url("/admin/finisheds/home")]);



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

            CONF_SITE_NAME . " | " . ($chargetEdit ? "Perfil de {$chargetEdit->name}" : "Novo Pago"),

            CONF_SITE_DESC,

            url("/admin"),

            url("/admin/assets/images/image.jpg"),

            false

        );



        echo $this->view->render("widgets/finisheds/finished", [

            "app" => "finisheds/home",

            "head" => $head,

            "charge" => $chargetEdit,

            "ticket" => $ticket,

            "value" => $value

        ]);

    }

}