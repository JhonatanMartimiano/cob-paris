<?php

namespace Source\App\Admin;

use Source\Models\Auth;
use Source\Models\Charge;
use Source\Models\Client;
use Source\Models\Log;
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
        $selectedTickets = null;
        $value = null;
        $qntdTicket = null;
        $allCount = null;
        if (!empty($data)) {
            if ($data['firstForm']) {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                $_SESSION['firstFilter'] = [$data['start_date'], $data['due_date'], $data['situation']];
                if ($data['situation'] === 'finished') {
                    $finished = (new Charge())->find('payment_date BETWEEN :sd AND :dd', "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);

                    for ($h = 0; $h < count($finished); $h++) {
                        if ($finished[$h]->getTicket()->situation === 'finished') {
                            $selectedTickets[] = $finished[$h];
                        }
                    }

                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->getTicket()->value;
                        }
                    }
                } elseif ($data['situation'] === 'due') {
                    $selectedTickets = (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (due_date - CURDATE() < 0 AND situation = 'open' OR situation = 'defeated')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->value;
                        }
                    }
                } elseif ($data['situation'] === 'towin') {
                    $selectedTickets = (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (due_date - CURDATE() <= 3 AND due_date - CURDATE() >= 0 AND situation = 'open')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->value;
                        }
                    }
                } elseif ($data['situation'] === 'negotiation') {
                    $selectedTickets = (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'negotiation')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->value;
                        }
                    }
                } elseif ($data['situation'] === 'lowForPayment') {
                    $lowForPayment = (new Charge())->find('payment_date BETWEEN :sd AND :dd', "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);

                    for ($h = 0; $h < count($lowForPayment); $h++) {
                        if ($lowForPayment[$h]->getTicket()->situation === 'lowForPayment') {
                            $selectedTickets[] = $lowForPayment[$h];
                        }
                    }

                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->getTicket()->value;
                        }
                    }
                } elseif ($data['situation'] === 'courts') {
                    $selectedTickets = (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'courts')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->value;
                        }
                    }
                } elseif ($data['situation'] === 'protested') {
                    $selectedTickets = (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'protested')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->value;
                        }
                    }
                } elseif ($data['situation'] === 'open') {
                    $nowDate = date('Y-m-d');
                    $selectedTickets = (new Ticket())->find("due_date - '2022-06-07' >= 3 AND situation = 'open' AND (due_date BETWEEN :sd AND :dd)", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->value;
                        }
                    }
                } elseif ($data['situation'] === 'protestedAgreed') {
                    $selectedTickets = (new Ticket())->find("due_date BETWEEN :sd AND :dd AND situation = 'protestedAgreed'", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->value;
                        }
                    }
                } elseif ($data['situation'] === 'all') {
                    $selectedTickets = (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation != 'agreed')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                    $qntdTicket = ($selectedTickets) ? count($selectedTickets) : 0;
                    if ($selectedTickets) {
                        for ($i = 0; $i < count($selectedTickets); $i++) {
                            $value += $selectedTickets[$i]->value;
                        }

                        $nowDate = date('Y-m-d');
                        $finished = (new Charge())->find('payment_date BETWEEN :sd AND :dd', "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                        $finishedCount = 0;
                        for ($j = 0; $j < count($finished); $j++) {
                            if ($finished[$j]->getTicket()->situation == 'finished') {
                                $finishedCount += 1;
                            }
                        }

                        $lowForPayment = (new Charge())->find('payment_date BETWEEN :sd AND :dd', "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->fetch(true);
                        $lowForPaymentCount = 0;

                        for ($k = 0; $k < count($lowForPayment); $k++) {
                            if ($lowForPayment[$k]->getTicket()->situation == 'lowForPayment') {
                                $lowForPaymentCount += 1;
                            }
                        }

                        $allCount = [
                            'finishedCount' => ($finishedCount) ? $finishedCount : 0,
                            'dueCount' => ((new Ticket())->find("due_date BETWEEN :sd AND :dd AND (due_date - CURDATE() < 0 AND situation = 'open' OR situation = 'defeated')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count()) ? (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (due_date - CURDATE() < 0 AND situation = 'open' OR situation = 'defeated')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count() : 0,
                            'openCount' => ((new Ticket())->find("due_date BETWEEN :sd AND :dd AND (due_date - {$nowDate} >= 3 AND situation = 'open')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count()) ? (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (due_date - {$nowDate} >= 3 AND situation = 'open')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count() : 0,
                            'agreedCount' => ((new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'negotiation')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count()) ? (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'negotiation')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count() : 0,
                            'lowForPaymentCount' => ($lowForPaymentCount) ? $lowForPaymentCount : 0,
                            'courtsCount' => ((new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'courts')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count()) ? (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'courts')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count() : 0,
                            'protestedCount' => ((new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'protested')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count()) ? (new Ticket())->find("due_date BETWEEN :sd AND :dd AND (situation = 'protested')", "sd={$_SESSION['firstFilter'][0]}&dd={$_SESSION['firstFilter'][1]}")->count() : 0
                        ];

                        // echo '<pre>';
                        // var_dump($allCount);
                        // exit;
                        // echo '<pre/>';
                    }
                }
            } elseif ($data['secondForm']) {
                $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
                $_SESSION['secondFilter'] = [$data['start_date'], $data['due_date']];
                echo json_encode(["redirect" => url("/admin/dash/home")]);
                return;
            }
        }

        if (empty($_SESSION['secondFilter'][0]) && empty($_SESSION['secondFilter'][1])) {
            unset($_SESSION['secondFilter']);
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
        $tickets = null;
        for ($i = 0; $i < $numberMonth; $i++) {
            $monthsNameArr[] = $monthsName[$i];
            $month = $i + 1;
            $monthDays = days_in_month($month, $year);
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
            "selectedTickets" => $selectedTickets,
            "tickets" => ($_SESSION['secondFilter']) ? (new Ticket())->find('due_date BETWEEN :sd AND :dd', "sd={$_SESSION['secondFilter'][0]}&dd={$_SESSION['secondFilter'][1]}")->fetch(true) : $tickets,
            "fstartDate" => $_SESSION['firstFilter'][0],
            "fdueDate" => $_SESSION['firstFilter'][1],
            "fsituation" => $_SESSION['firstFilter'][2],
            "value" => $value,
            "qntdTicket" => ($qntdTicket) ? $qntdTicket : 0,
            "sstartDate" => $_SESSION['secondFilter'][0],
            "sdueDate" => $_SESSION['secondFilter'][1],
            "allCount" => $allCount
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
