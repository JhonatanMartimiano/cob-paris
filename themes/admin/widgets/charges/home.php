<?php $v->layout("_admin"); ?>
<!--App-Content-->
<div class="app-content my-3 my-md-5">
    <div class="side-app">
        <div class="page-header">
            <h4 class="page-title">Cobranças</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cobranças</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title">Cobranças</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php if ($home) : ?>
                                <form class="form-inline mb-1" action="<?= url('/admin/charges/home'); ?>" method="post">
                                    <div class="nav-search">
                                        <input type="search" class="form-control header-search" name="s" value="<?= $search; ?>" placeholder="Buscar…" aria-label="Search">
                                        <select name="s_select" class="form-control mx-1">
                                            <option selected disabled value="">Selecionar</option>
                                            <option value="cpf_cnpj">CPF/CNPJ</option>
                                            <option value="client">Cliente</option>
                                            <option value="ticket">Número do Boleto</option>
                                            <option value="order">Número do Pedido</option>
                                        </select>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            <?php else : ?>
                                <form class="form-inline mb-1" action="<?= url('/admin/charges/filter'); ?>" method="post">
                                    <div class="nav-search">
                                        <input type="search" class="form-control header-search" name="s" value="<?= $search; ?>" placeholder="Buscar…" aria-label="Search">
                                        <input type="hidden" name="filter" value="<?= $filter; ?>">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            <?php endif; ?>
                            <div class="mb-2">
                                <a href="<?= url('/admin/charges/filter/due'); ?>" class="btn btn-danger">BOLETO VENCIDO</a>
                                <a class="btn-due btn btn-danger" title="Gerar Relatório">
                                    <span><i class="fa fa-download text-white"></i></span>
                                </a>

                                <a href="<?= url('/admin/charges/filter/towin'); ?>" class="btn" style="background-color: #ddd30c; color: white;">BOLETO A VENCER</a>
                                <a class="btn-towin btn" style="background-color: #ddd30c;" title="Gerar Relatório">
                                    <span><i class="fa fa-download text-white"></i></span>
                                </a>


                                <a href="<?= url('/admin/charges/filter/negotiation'); ?>" class="btn btn-info">EM NEGOCIAÇÃO</a>
                                <a class="btn-negotiation btn btn-info" title="Gerar Relatório">
                                    <span><i class="fa fa-download text-white"></i></span>
                                </a>

                                <a href="<?= url('/admin/charges/filter/courts'); ?>" class="btn" style="background-color: #ff0084; color: white;">JUÍZADO</a>
                                <a class="btn-courts btn" style="background-color: #ff0084;" title="Gerar Relatório">
                                    <span><i class="fa fa-download text-white"></i></span>
                                </a>

                                <a href="<?= url('/admin/charges/filter/protested'); ?>" class="btn" style="background-color: #4e012d; color: white;">PROTESTADO</a>
                                <a class="btn-protested btn" style="background-color: #4e012d;" title="Gerar Relatório">
                                    <span><i class="fa fa-download text-white"></i></span>
                                </a>

                                <a href="<?= url('/admin/charges/filter/canceled'); ?>" class="btn" style="background-color: #000; color: white;">CANCELADO</a>
                                <a class="btn-canceled btn" style="background-color: #000;" title="Gerar Relatório">
                                    <span><i class="fa fa-download text-white"></i></span>
                                </a>

                                <a href="<?= url('/admin/charges/filter/open'); ?>" class="btn" style="background-color: #fff000; color: white;">BOLETOS EM ABERTO</a>
                                <a class="btn-open btn" style="background-color: #fff000;" title="Gerar Relatório">
                                    <span><i class="fa fa-download text-white"></i></span>
                                </a>

                                <a href="<?= url('/admin/charges/filter/protested-agreed'); ?>" class="btn" style="background-color: brown; color: white;">PROTESTADO PORÉM ACORDADO</a>
                                <a class="btn-protested-agreed btn" style="background-color: brown;" title="Gerar Relatório">
                                    <span><i class="fa fa-download text-white"></i></span>
                                </a>
                            </div>
                            <table class="table table-bordered border-top mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>CPF/CNPJ</th>
                                        <th>Número do Boleto</th>
                                        <th>Data de Emissão</th>
                                        <th>Data de Vencimento</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($tickets) : ?>
                                        <?php foreach ($tickets as $ticket) : ?>
                                            <?php if ($ticket->situation == 'negotiation') : ?>
                                                <tr align="center" class="bg-info text-white">
                                                    <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                    <td><?= $ticket->getClient()->name; ?></td>
                                                    <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td align="center">
                                                        <a href="<?= url('/admin/charges/charge/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php elseif (date_diff_system($ticket->due_date) < 0 && $ticket->situation == 'open' || $ticket->situation == 'defeated') : ?>
                                                <tr align="center" class="bg-danger text-white">
                                                    <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                    <td><?= $ticket->getClient()->name; ?></td>
                                                    <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td align="center">
                                                        <a href="<?= url('/admin/charges/charge/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php elseif (date_diff_system($ticket->due_date, date_fmt('now', 'Y-m-d')) <= 3 && $ticket->situation == 'open') : ?>
                                                <tr align="center" style="background-color: #ddd30c; color: white">
                                                    <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                    <td><?= $ticket->getClient()->name; ?></td>
                                                    <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td align="center">
                                                        <a href="<?= url('/admin/charges/charge/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'courts') : ?>
                                                <tr align="center" style="background-color: #ff0084; color: white;">
                                                    <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                    <td><?= $ticket->getClient()->name; ?></td>
                                                    <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td align="center">
                                                        <a href="<?= url('/admin/charges/charge/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'protested') : ?>
                                                <tr align="center" style="background-color: #4e012d; color: white;">
                                                    <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                    <td><?= $ticket->getClient()->name; ?></td>
                                                    <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td align="center">
                                                        <a href="<?= url('/admin/charges/charge/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'canceled') : ?>
                                                <tr align="center" style="background-color: #000; color: white;">
                                                    <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                    <td><?= $ticket->getClient()->name; ?></td>
                                                    <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td align="center">
                                                        <a href="<?= url('/admin/charges/charge/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php elseif (date_diff_system($ticket->due_date) >= 3 && $ticket->situation == 'open') : ?>
                                                <tr align="center" style="background-color: #fff000; color: white;">
                                                    <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                    <td><?= $ticket->getClient()->name; ?></td>
                                                    <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td align="center">
                                                        <a href="<?= url('/admin/charges/charge/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php elseif ($ticket->situation == 'protestedAgreed') : ?>
                                                <tr align="center" style="background-color: brown; color: white;">
                                                    <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                    <td><?= $ticket->getClient()->name; ?></td>
                                                    <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                    <td><?= $ticket->ticket_number; ?></td>
                                                    <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                    <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                    <td align="center">
                                                        <a href="<?= url('/admin/charges/charge/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?= $paginator; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/App-Content-->
<?php $v->start("scripts") ?>
<script src="<?= theme("/assets/js/report-excel.js", CONF_VIEW_ADMIN) ?>"></script>
<?php $v->end("scripts") ?>