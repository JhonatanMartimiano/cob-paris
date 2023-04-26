<?php

namespace Source\App\Admin;

use Source\Models\Auth;
use Source\Models\Client;
use Source\Models\Ticket;

/**
 * Class Dash
 * @package Source\App\Admin
 */
class Dash extends Admin
{
    /**
     * Dash constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function dash(): void
    {
        redirect("/admin/dash/home");
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function home(?array $data): void
    {
        if (!empty($data)) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $_SESSION['filter'] = [$data['start_date'], $data['due_date']];
            echo json_encode(["redirect" => url("/admin/dash/home")]);
            return;
        }

        if (empty($_SESSION['filter'][0]) && empty($_SESSION['filter'][1])) {
            unset($_SESSION['filter']);
        }

        $numberMonth = intval(date('m'));

        $monthsName = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        function days_in_month($month, $year)
        {
            return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
        }

        $year = date('Y');
        $monthsNameArr = null;
        $clients = null;
        $finished = null;
        $finishedValue = null;
        $due = null;
        $dueValue = null;
        $open = null;
        $openValue = null;
        $agreed = null;
        $agreedValue = null;
        $lowForPayment = null;
        $lowForPaymentValue = null;
        $courts = null;
        $courtsValue = null;
        $protested = null;
        $protestedValue = null;
        $currentDebt = null;
        $tickets = null;
        for ($i = 0; $i < $numberMonth; $i++) {
            $monthsNameArr[] = $monthsName[$i];
            $month = $i + 1;
            $monthDays = days_in_month($month, $year);
            $clients[] = (new Client)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}'")->count();
            $finished[] = (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'finished'")->count();
            $due[] = (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND due_date - CURDATE() < 0 AND situation = 'open'")->count();
            $open[] = (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND due_date - CURDATE() >= 0 AND situation = 'open'")->count();
            $agreed[] = (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'agreed'")->count();
            $lowForPayment[] = (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'lowForPayment'")->count();
            $courts[] = (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'courts'")->count();
            $protested[] = (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'protested'")->count();

            $finishedValue[] = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'finished'", "", "SUM(amount_paid) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'finished'", "", "SUM(amount_paid) as total")->fetch(true)[0]->total;
            $dueValue[] = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND due_date - CURDATE() < 0 AND situation = 'open'", "", "SUM(value) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND due_date - CURDATE() < 0 AND situation = 'open'", "", "SUM(value) as total")->fetch(true)[0]->total;
            $openValue[] = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND due_date - CURDATE() >= 0 AND situation = 'open'", "", "SUM(value) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND due_date - CURDATE() >= 0 AND situation = 'open'", "", "SUM(value) as total")->fetch(true)[0]->total;
            $agreedValue[] = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'agreed'", "", "SUM(value) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'agreed'", "", "SUM(value) as total")->fetch(true)[0]->total;
            $lowForPaymentValue[] = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'lowForPayment'", "", "SUM(amount_paid) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'lowForPayment'", "", "SUM(amount_paid) as total")->fetch(true)[0]->total;
            $courtsValue[] = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'courts'", "", "SUM(value) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'courts'", "", "SUM(value) as total")->fetch(true)[0]->total;
            $protestedValue[] = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'protested'", "", "SUM(value) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'protested'", "", "SUM(value) as total")->fetch(true)[0]->total;

            $lowForPaymentSum = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'lowForPayment'", "", "SUM(amount_paid) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'lowForPayment'", "", "SUM(amount_paid) as total")->fetch(true)[0]->total;
            $finishedSum = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'finished'", "", "SUM(amount_paid) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'finished'", "", "SUM(amount_paid) as total")->fetch(true)[0]->total;
            $dueSum = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND due_date - CURDATE() < 0 AND situation = 'open'", "", "SUM(value) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND due_date - CURDATE() < 0 AND situation = 'open'", "", "SUM(value) as total")->fetch(true)[0]->total;
            $agreedSum = ((new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'agreed'", "", "SUM(value) as total")->fetch(true)[0]->total == null) ? 0 : (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}' AND situation = 'agreed'", "", "SUM(value) as total")->fetch(true)[0]->total;
            $currentDebt[] =  $lowForPaymentSum + $finishedSum - $dueSum - $agreedSum;
            $tickets = (new Ticket)->find("created_at BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-{$monthDays}'")->fetch(true);
        }
        
        $head = $this->seo->render(
            CONF_SITE_NAME . " | Dashboard",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
            false
        );

        echo $this->view->render("widgets/dash/home", [
            "app" => "dash",
            "head" => $head,
            "numberMonth" => $numberMonth,
            "monthName" => $monthsName,
            "client" => $clients,
            "finished" => $finished,
            "finishedValue" => $finishedValue,
            "due" => $due,
            "dueValue" => $dueValue,
            "open" => $open,
            "openValue" => $openValue,
            "agreed" => $agreed,
            "agreedValue" => $agreedValue,
            "lowForPayment" => $lowForPayment,
            "lowForPaymentValue" => $lowForPaymentValue,
            "courts" => $courts,
            "courtsValue" => $courtsValue,
            "protested" => $protested,
            "protestedValue" => $protestedValue,
            "currentDebt" => $currentDebt,
            "finishedCount" => (new Ticket)->find("situation = 'finished'")->count(),
            "dueCount" => (new Ticket)->find("due_date - CURDATE() < 0 AND situation = 'open' OR situation = 'defeated'")->count(),
            "openCount" => (new Ticket)->find("due_date - CURDATE() >= 0 AND situation = 'open'")->count(),
            "agreedCount" => (new Ticket)->find("situation = 'agreed'")->count(),
            "lowForPaymentCount" => (new Ticket)->find("situation = 'lowForPayment'")->count(),
            "courtsCount" => (new Ticket)->find("situation = 'courts'")->count(),
            "protestedCount" => (new Ticket)->find("situation = 'protested'")->count(),
            "tickets" => ($_SESSION['filter']) ? (new Ticket())->find('due_date BETWEEN :sd AND :dd', "sd={$_SESSION['filter'][0]}&dd={$_SESSION['filter'][1]}")->fetch(true) : $tickets,
            "start_date" => $_SESSION['filter'][0],
            "due_date" => $_SESSION['filter'][1]
        ]);
    }

    /**
     * @return void
     */
    public function logoff(): void
    {
        $this->message->success("Você saiu com sucesso {$this->user->first_name}.")->flash();

        Auth::logout();
        redirect("/admin/login");
    }
}
