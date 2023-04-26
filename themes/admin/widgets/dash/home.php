<?php $v->layout("_admin"); ?>

<!--App-Content-->

<div class="app-content  my-3 my-md-5">

    <div class="side-app">

        <div class="page-header">

            <h4 class="page-title">Dashboard</h4>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>

                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>

            </ol>

        </div>

        <div class="row">

            <div class="col-md-12">

                <div class="card m-b-20">

                    <div class="card-body">

                        <div>

                            <div class="d-flex justify-content-center">

                                <form class="form-inline mb-1 ajax_off" action="<?= url('/admin/dash/home'); ?>" method="post">

                                    <input type="hidden" name="firstForm" value="true">

                                    <div class="form-group">

                                        <input type="date" name="start_date" value="<?= $fstartDate; ?>" class="form-control">

                                    </div>

                                    <div class="form-group mx-2">

                                        <input type="date" name="due_date" value="<?= $fdueDate; ?>" class="form-control">

                                    </div>

                                    <div class="form-group mr-2">

                                        <select name="situation" class="form-control">

                                            <option value="">Selecionar</option>

                                            <option <?= ($fsituation === 'finished') ? 'selected' : ''; ?> value="finished">Liquidado</option>

                                            <option <?= ($fsituation === 'due') ? 'selected' : ''; ?> value="due">Vencido</option>

                                            <option <?= ($fsituation === 'towin') ? 'selected' : ''; ?> value="towin">A Vencer</option>

                                            <option <?= ($fsituation === 'negotiation') ? 'selected' : ''; ?> value="negotiation">Acordado</option>

                                            <option <?= ($fsituation === 'lowForPayment') ? 'selected' : ''; ?> value="lowForPayment">Baixa para Pagamento</option>

                                            <option <?= ($fsituation === 'courts') ? 'selected' : ''; ?> value="courts">Juízado</option>

                                            <option <?= ($fsituation === 'protested') ? 'selected' : ''; ?> value="protested">Protestado</option>

                                            <option <?= ($fsituation === 'protestedAgreed') ? 'selected' : ''; ?> value="protestedAgreed">Protestado Porém Acordado</option>

                                            <option <?= ($fsituation === 'open') ? 'selected' : ''; ?> value="open">Em Aberto</option>

                                            <option <?= ($fsituation === 'all') ? 'selected' : ''; ?> value="all">Todas</option>

                                        </select>

                                    </div>

                                    <button type="submit" class="btn btn-danger">BUSCAR</button>

                                </form>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-2">

                                    <div class="d-flex flex-column justify-content-center align-items-center border">

                                        <h5>Quantidade de Boletos</h5>

                                        <p class="m-0"><?= $qntdTicket; ?></p>

                                    </div>

                                </div>

                                <div class="col-md-6 mb-2">

                                    <div class="d-flex flex-column justify-content-center align-items-center border">

                                        <h5>Valor Total</h5>

                                        <p class="m-0 mask-money"><?= number_format($value, 0); ?></p>

                                    </div>

                                </div>

                            </div>

                            <div class="table-responsive mb-3">

                                <table class="table table-bordered border-top mb-0">

                                    <thead>

                                        <tr align="center">

                                            <th>ID</th>

                                            <th>Cliente</th>

                                            <th>Número do Boleto</th>

                                            <th>Número do Banco</th>

                                            <th>Número do Pedido</th>

                                            <th>Data de Emissão</th>

                                            <th>Data de Vencimento</th>

                                            <th>Valor</th>
                                            <th>Valor com Multa/Juros</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php if ($selectedTickets) : ?>

                                            <?php foreach ($selectedTickets as $selectedTicket) : ?>

                                                <?php if ($fsituation === 'finished') : ?>

                                                    <?php if ($selectedTicket->getTicket()->situation === 'finished') : ?>

                                                        <tr align="center">

                                                            <td scope="row"><?= $selectedTicket->getTicket()->id; ?></td>

                                                            <td><?= $selectedTicket->getClient()->name; ?></td>

                                                            <td><?= $selectedTicket->getTicket()->ticket_number; ?></td>

                                                            <td><?= $selectedTicket->getTicket()->bank_number; ?></td>

                                                            <td><?= $selectedTicket->getTicket()->request_number; ?></td>

                                                            <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                            <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                            <td class="mask-money"><?= $selectedTicket->getTicket()->value; ?></td>

                                                            <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->getTicket()->due_date) < 0) ? $selectedTicket->getTicket()->value * 0.05 + $selectedTicket->getTicket()->value + ($selectedTicket->getTicket()->value * 0.0033 * abs(date_diff_system($selectedTicket->getTicket()->due_date))) : 0, 0); ?></td>

                                                        </tr>

                                                    <?php endif; ?>

                                                <?php elseif ($fsituation === 'due') : ?>

                                                    <tr align="center">

                                                        <td scope="row"><?= $selectedTicket->id; ?></td>

                                                        <td><?= $selectedTicket->getClient()->name; ?></td>

                                                        <td><?= $selectedTicket->ticket_number; ?></td>

                                                        <td><?= $selectedTicket->bank_number; ?></td>

                                                        <td><?= $selectedTicket->request_number; ?></td>

                                                        <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                        <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                        <td class="mask-money"><?= $selectedTicket->value; ?></td>

                                                        <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->due_date) < 0) ? $selectedTicket->value * 0.05 + $selectedTicket->value + ($selectedTicket->value * 0.0033 * abs(date_diff_system($selectedTicket->due_date))) : 0, 0); ?></td>

                                                    </tr>

                                                <?php elseif ($fsituation === 'towin') : ?>

                                                    <tr align="center">

                                                        <td scope="row"><?= $selectedTicket->id; ?></td>

                                                        <td><?= $selectedTicket->getClient()->name; ?></td>

                                                        <td><?= $selectedTicket->ticket_number; ?></td>

                                                        <td><?= $selectedTicket->bank_number; ?></td>

                                                        <td><?= $selectedTicket->request_number; ?></td>

                                                        <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                        <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                        <td class="mask-money"><?= $selectedTicket->value; ?></td>

                                                        <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->due_date) < 0) ? $selectedTicket->value * 0.05 + $selectedTicket->value + ($selectedTicket->value * 0.0033 * abs(date_diff_system($selectedTicket->due_date))) : 0, 0); ?></td>

                                                    </tr>

                                                <?php elseif ($fsituation === 'negotiation') : ?>

                                                    <tr align="center">

                                                        <td scope="row"><?= $selectedTicket->id; ?></td>

                                                        <td><?= $selectedTicket->getClient()->name; ?></td>

                                                        <td><?= $selectedTicket->ticket_number; ?></td>

                                                        <td><?= $selectedTicket->bank_number; ?></td>

                                                        <td><?= $selectedTicket->request_number; ?></td>

                                                        <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                        <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                        <td class="mask-money"><?= $selectedTicket->value; ?></td>

                                                        <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->due_date) < 0) ? $selectedTicket->value * 0.05 + $selectedTicket->value + ($selectedTicket->value * 0.0033 * abs(date_diff_system($selectedTicket->due_date))) : 0, 0); ?></td>

                                                    </tr>
                                                <?php elseif ($fsituation === 'lowForPayment') : ?>

                                                    <?php if ($selectedTicket->getTicket()->situation === 'lowForPayment') : ?>

                                                        <tr align="center">

                                                            <td scope="row"><?= $selectedTicket->getTicket()->id; ?></td>

                                                            <td><?= $selectedTicket->getClient()->name; ?></td>

                                                            <td><?= $selectedTicket->getTicket()->ticket_number; ?></td>

                                                            <td><?= $selectedTicket->getTicket()->bank_number; ?></td>

                                                            <td><?= $selectedTicket->getTicket()->request_number; ?></td>

                                                            <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                            <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                            <td class="mask-money"><?= $selectedTicket->getTicket()->value; ?></td>
                                                            <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->getTicket()->due_date) < 0) ? $selectedTicket->getTicket()->value * 0.05 + $selectedTicket->getTicket()->value + ($selectedTicket->getTicket()->value * 0.0033 * abs(date_diff_system($selectedTicket->getTicket()->due_date))) : 0, 0); ?></td>

                                                        </tr>

                                                    <?php endif; ?>

                                                <?php elseif ($fsituation === 'courts') : ?>

                                                    <tr align="center">

                                                        <td scope="row"><?= $selectedTicket->id; ?></td>

                                                        <td><?= $selectedTicket->getClient()->name; ?></td>

                                                        <td><?= $selectedTicket->ticket_number; ?></td>

                                                        <td><?= $selectedTicket->bank_number; ?></td>

                                                        <td><?= $selectedTicket->request_number; ?></td>

                                                        <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>
                                                        <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                        <td class="mask-money"><?= $selectedTicket->value; ?></td>

                                                        <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->due_date) < 0) ? $selectedTicket->value * 0.05 + $selectedTicket->value + ($selectedTicket->value * 0.0033 * abs(date_diff_system($selectedTicket->due_date))) : 0, 0); ?></td>

                                                    </tr>

                                                <?php elseif ($fsituation === 'protested') : ?>

                                                    <tr align="center">

                                                        <td scope="row"><?= $selectedTicket->id; ?></td>

                                                        <td><?= $selectedTicket->getClient()->name; ?></td>

                                                        <td><?= $selectedTicket->ticket_number; ?></td>

                                                        <td><?= $selectedTicket->bank_number; ?></td>

                                                        <td><?= $selectedTicket->request_number; ?></td>
                                                        <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                        <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                        <td class="mask-money"><?= $selectedTicket->value; ?></td>

                                                        <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->due_date) < 0) ? $selectedTicket->value * 0.05 + $selectedTicket->value + ($selectedTicket->value * 0.0033 * abs(date_diff_system($selectedTicket->due_date))) : 0, 0); ?></td>

                                                    </tr>

                                                <?php elseif ($fsituation === 'open') : ?>

                                                    <tr align="center">

                                                        <td scope="row"><?= $selectedTicket->id; ?></td>

                                                        <td><?= $selectedTicket->getClient()->name; ?></td>

                                                        <td><?= $selectedTicket->ticket_number; ?></td>

                                                        <td><?= $selectedTicket->bank_number; ?></td>

                                                        <td><?= $selectedTicket->request_number; ?></td>

                                                        <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                        <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                        <td class="mask-money"><?= $selectedTicket->value; ?></td>

                                                        <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->due_date) < 0) ? $selectedTicket->value * 0.05 + $selectedTicket->value + ($selectedTicket->value * 0.0033 * abs(date_diff_system($selectedTicket->due_date))) : 0, 0); ?></td>

                                                    </tr>

                                                <?php elseif ($fsituation === 'protestedAgreed') : ?>

                                                    <tr align="center">

                                                        <td scope="row"><?= $selectedTicket->id; ?></td>

                                                        <td><?= $selectedTicket->getClient()->name; ?></td>

                                                        <td><?= $selectedTicket->ticket_number; ?></td>

                                                        <td><?= $selectedTicket->bank_number; ?></td>

                                                        <td><?= $selectedTicket->request_number; ?></td>
                                                        <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                        <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                        <td class="mask-money"><?= $selectedTicket->value; ?></td>

                                                        <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->due_date) < 0) ? $selectedTicket->value * 0.05 + $selectedTicket->value + ($selectedTicket->value * 0.0033 * abs(date_diff_system($selectedTicket->due_date))) : 0, 0); ?></td>

                                                    </tr>

                                                <?php elseif ($fsituation === 'all') : ?>

                                                    <tr align="center">

                                                        <td scope="row"><?= $selectedTicket->id; ?></td>

                                                        <td><?= $selectedTicket->getClient()->name; ?></td>

                                                        <td><?= $selectedTicket->ticket_number; ?></td>

                                                        <td><?= $selectedTicket->bank_number; ?></td>

                                                        <td><?= $selectedTicket->request_number; ?></td>

                                                        <td><?= date_fmt($selectedTicket->issue_date, 'd/m/Y'); ?></td>

                                                        <td><?= date_fmt($selectedTicket->due_date, 'd/m/Y'); ?></td>

                                                        <td class="mask-money"><?= $selectedTicket->value; ?></td>

                                                        <td class="mask-money"><?= number_format((date_diff_system($selectedTicket->due_date) < 0) ? $selectedTicket->value * 0.05 + $selectedTicket->value + ($selectedTicket->value * 0.0033 * abs(date_diff_system($selectedTicket->due_date))) : 0, 0); ?></td>

                                                    </tr>

                                                <?php endif; ?>

                                            <?php endforeach; ?>

                                        <?php endif; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        <?php if ($fsituation === 'all') : ?>

                            <div class="mx-auto col-8">

                                <div class="card" id="chart"></div>

                            </div>

                        <?php endif; ?>

                        <div class="d-flex justify-content-center">

                            <form class="form-inline mb-1" action="<?= url('/admin/dash/home'); ?>" method="post">

                                <input type="hidden" name="secondForm" value="true">

                                <div class="form-group">

                                    <input type="date" name="start_date" value="<?= $sstartDate; ?>" class="form-control">

                                </div>

                                <div class="form-group mx-2">

                                    <input type="date" name="due_date" value="<?= $sdueDate; ?>" class="form-control">

                                </div>

                                <button type="submit" class="btn btn-danger">BUSCAR</button>

                            </form>

                        </div>

                        <div class="table-responsive mb-3">

                            <table class="table table-bordered border-top mb-0">

                                <thead>

                                    <tr align="center">

                                        <th>ID</th>

                                        <th>Número do Boleto</th>

                                        <th>Número do Banco</th>

                                        <th>Número do Pedido</th>

                                        <th>Data de Emissão</th>

                                        <th>Data de Vencimento</th>

                                        <th>Valor</th>

                                        <th>Valor com Multa/Juros</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if ($tickets) : ?>

                                        <?php foreach ($tickets as $ticket) : ?>

                                            <?php if ($ticket->situation == 'finished') : ?>

                                                <tr align="center" class="bg-success">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php elseif (date_diff_system($ticket->due_date) < 0 && $ticket->situation == 'open') : ?>

                                                <tr align="center" class="bg-danger text-white">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php elseif ($ticket->situation == 'agreed') : ?>

                                                <tr align="center" class="bg-info text-white">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php elseif ($ticket->situation == 'lowForPayment') : ?>

                                                <tr align="center" style="background-color: #4ce437; color: white;">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php elseif ($ticket->situation == 'courts') : ?>

                                                <tr align="center" style="background-color: #ff4500; color: white;">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php elseif ($ticket->situation == 'protested') : ?>

                                                <tr align="center" style="background-color: #4e012d; color: white">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php elseif (date_diff_system($ticket->due_date) >= 0 && $ticket->situation == 'open') : ?>

                                                <tr align="center" style="background-color: #ddd30c; color: white">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php elseif ($ticket->situation == 'canceled') : ?>

                                                <tr align="center" style="background-color: #000; color: white">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php elseif ($ticket->situation == 'protestedAgreed') : ?>

                                                <tr align="center" style="background-color: brown; color: white">

                                                    <td scope="row"><?= $ticket->id; ?></td>

                                                    <td><?= $ticket->ticket_number; ?></td>

                                                    <td><?= $ticket->bank_number; ?></td>

                                                    <td><?= $ticket->request_number; ?></td>

                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>

                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>

                                                    <td class="mask-money"><?= $ticket->value; ?></td>

                                                    <td class="mask-money"><?= number_format((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0, 0); ?></td>

                                                </tr>

                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!--/App-Content-->

<?php $v->start('scripts'); ?>

<script>
    var options = {

        series: [<?= $allCount['finishedCount']; ?>, <?= $allCount['dueCount']; ?>, <?= $allCount['openCount']; ?>, <?= $allCount['agreedCount']; ?>, <?= $allCount['lowForPaymentCount']; ?>, <?= $allCount['courtsCount']; ?>, <?= $allCount['protestedCount']; ?>],

        chart: {

            type: 'donut',

        },

        labels: ['Liquidado', 'Vencido', 'A Vencer', 'Acordado', 'Baixa para Pagamento', 'Juízado', 'Protestado'],

        responsive: [{

            breakpoint: 480,

            options: {

                chart: {

                    width: 200

                },

                legend: {

                    position: 'bottom'

                }

            }

        }],

        colors: ['#008000', '#FF0000', '#ddd30c', '#1da1f3', '#4ce437', '#ff4500', '#4e012d']

    };



    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
</script>

<?php $v->end('scripts'); ?>