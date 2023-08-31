<?php $v->layout("_admin"); ?>
    <!--App-Content-->
    <div class="app-content  my-3 my-md-5">
        <div class="side-app">
            <div class="page-header">
                <h4 class="page-title">Relatórios</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Relatórios</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">Relatórios</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form>
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <label for="client">Nome Completo</label>
                                            <input type="text" value="<?= $client->name; ?>" id="client"
                                                   class="form-control" disabled>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="document">CPF/CNPJ</label>
                                            <input type="text" value="<?= $client->cpf_cnpj; ?>" id="document"
                                                   class="form-control" disabled>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-center align-items-center p-2">
                                    <div class="d-flex justify-content-center align-items-center mr-5">
                                        <span class="fa fa-circle text-success mr-1"></span>
                                        <p class="m-0">Liquidado</p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center mr-5">
                                        <span class="fa fa-circle mr-1" style="color: #fff000;"></span>
                                        <p class="m-0">Em Aberto</p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center mr-5">
                                        <span class="fa fa-circle text-danger mr-1"></span>
                                        <p class="m-0">Vencido</p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center mr-5">
                                        <span class="fa fa-circle text-warning mr-1"></span>
                                        <p class="m-0">A Vencer</p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center mr-5">
                                        <span class="fa fa-circle text-info mr-1"></span>
                                        <p class="m-0">Acordado</p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center mr-5">
                                        <span class="fa fa-circle mr-1" style="color: #4ce437;"></span>
                                        <p class="m-0">Baixa para Pagamento</p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center mr-5">
                                        <span class="fa fa-circle mr-1" style="color: #ff0084;"></span>
                                        <p class="m-0">Juizado</p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center mr-5">
                                        <span class="fa fa-circle mr-1" style="color: #4e012d;"></span>
                                        <p class="m-0">Protestado</p>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="fa fa-circle mr-1" style="color: #000;"></span>
                                        <p class="m-0">Cancelado</p>
                                    </div>
                                </div>
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
                                                <tr align="center" class="bg-success text-white">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'lowForPayment') : ?>
                                                <tr align="center" style="background-color: #4ce437; color: white;">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'negotiation') : ?>
                                                <tr align="center" class="bg-info text-white">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif (date_diff_system($ticket->due_date) < 0 && $ticket->situation == 'open' || $ticket->situation == 'defeated') : ?>
                                                <tr align="center" class="bg-danger text-white">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif (date_diff_system($ticket->due_date, date_fmt('now', 'Y-m-d')) <= 3 && $ticket->situation == 'open') : ?>
                                                <tr align="center" style="background-color: #ffa22b; color: white">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'courts') : ?>
                                                <tr align="center" style="background-color: #ff0084; color: white;">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'protested') : ?>
                                                <tr align="center" style="background-color: #4e012d; color: white;">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'canceled') : ?>
                                                <tr align="center" style="background-color: #000; color: white;">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif (date_diff_system($ticket->due_date) >= 3 && $ticket->situation == 'open') : ?>
                                                <tr align="center" style="background-color: #fff000; color: white;">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'protestedAgreed') : ?>
                                                <tr align="center" style="background-color: brown; color: white;">
                                                    <td><?= $ticket->id; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= $ticket->bank_number; ?></td>
                                                    <td><?= $ticket->request_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td class="mask-money"><?= $ticket->value; ?></td>
                                                    <td class="mask-money"><?= intval((date_diff_system($ticket->due_date) < 0) ? $ticket->value * 0.05 + $ticket->value + ($ticket->value * 0.0033 * abs(date_diff_system($ticket->due_date))) : 0) ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?= $paginator; ?>
                        </div>
                        <?php if ($tickets): ?>
                            <div class="col-12 d-flex justify-content-center mb-2">
                                <div class="border" style="height: 400px; width: 500px;">
                                    <div id="donut-chart"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/App-Content-->
<?php $v->start("scripts"); ?>
    <script>
        let donutChartDiv = document.getElementById("donut-chart");

        let options = {
            chart: {
                type: 'donut',
            },
            series: [<?= $finished ?>, <?= $open ?>, <?= $due ?>, <?= $toWin ?>, <?= $agreed ?>, <?= $lowForPayment ?>, <?= $courts ?>, <?= $protested ?>, <?= $canceled ?>],
            labels: ['Liquidado', 'Em Aberto', 'Vencido', 'A Vencer', 'Acordado', 'Baixa para Pagamento', 'Juizado', 'Protestado', 'Cancelado'],
            colors: ['#05a01f', '#fff000', '#ff382b', '#ffa22b', '#1da1f3', '#4ce437', '#ff0084', '#4e012d', '#000000'],
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
            }]
        };

        let donutChart = new ApexCharts(donutChartDiv, options);
        donutChart.render();
    </script>
<?php $v->end("scripts"); ?>