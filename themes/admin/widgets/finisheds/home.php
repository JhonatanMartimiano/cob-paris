<?php $v->layout("_admin"); ?>
<!--App-Content-->
<div class="app-content  my-3 my-md-5">
    <div class="side-app">
        <div class="page-header">
            <h4 class="page-title">Pagos</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pagos</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title">Pagos</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form class="form-inline mb-1" action="<?= url('/admin/finisheds/home'); ?>" method="post">
                                <div class="nav-search">
                                    <input type="search" class="form-control header-search" name="s" value="<?= $search; ?>" placeholder="Buscar…" aria-label="Search">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                            <form class="form-inline ajax_off mb-1" action="<?= url('/admin/finisheds/home'); ?>" method="post">
                                <div class="form-group">
                                    <input type="date" name="start_date" value="<?= $date['start_date']; ?>" class="form-control">
                                </div>
                                <div class="form-group mx-2">
                                    <input type="date" name="due_date" value="<?= $date['due_date']; ?>" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-danger">BUSCAR</button>
                            </form>
                            <div class="mb-2">
                                <button type="button" class="btn btn-success">Liquidado</button>

                                <button type="button" class="btn" style="background-color: #4ce437; color: white;">Baixa para Pagamento</button>
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
                                    <th>Data do Pagamento</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($tickets) : ?>
                                    <?php foreach ($tickets as $ticket) : ?>
                                        <?php if ($ticket->situation == 'finished') : ?>
                                            <tr align="center" class="bg-success text-white">
                                                <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                <td><?= $ticket->getClient()->name; ?></td>
                                                <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                <td><?= $ticket->ticket_number; ?></td>
                                                <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticket->getCharge()->payment_date, 'd/m/Y'); ?></td>
                                                <td align="center">
                                                    <a href="<?= url('/admin/finisheds/finished/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php elseif ($ticket->situation == 'lowForPayment') : ?>
                                            <tr align="center" style="background-color: #4ce437; color: white;">
                                                <th scope="row" class="text-white"><?= $ticket->id; ?></th>
                                                <td><?= $ticket->getClient()->name; ?></td>
                                                <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                <td><?= $ticket->ticket_number; ?></td>
                                                <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticket->getCharge()->payment_date, 'd/m/Y'); ?></td>
                                                <td align="center">
                                                    <a href="<?= url('/admin/finisheds/finished/' . $ticket->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if ($ticketsDate) : ?>
                                    <?php foreach ($ticketsDate as $ticketDate) : ?>
                                        <?php if ($ticketDate->getTicket()->situation == 'finished') : ?>
                                            <tr align="center" class="bg-success text-white">
                                                <th scope="row" class="text-white"><?= $ticketDate->getTicket()->id; ?></th>
                                                <td><?= $ticketDate->getClient()->name; ?></td>
                                                <td class=""><?= $ticketDate->getClient()->cpf_cnpj; ?></td>
                                                <td><?= $ticketDate->getTicket()->ticket_number; ?></td>
                                                <td><?= date_fmt($ticketDate->getTicket()->issue_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticketDate->getTicket()->due_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticketDate->payment_date, 'd/m/Y'); ?></td>
                                                <td align="center">
                                                    <a href="<?= url('/admin/finisheds/finished/' . $ticketDate->getTicket()->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php elseif ($ticketDate->getTicket()->situation == 'lowForPayment') : ?>
                                            <tr align="center" style="background-color: #4ce437; color: white;">
                                                <th scope="row" class="text-white"><?= $ticketDate->getTicket()->id; ?></th>
                                                <td><?= $ticketDate->getClient()->name; ?></td>
                                                <td class=""><?= $ticketDate->getClient()->cpf_cnpj; ?></td>
                                                <td><?= $ticketDate->getTicket()->ticket_number; ?></td>
                                                <td><?= date_fmt($ticketDate->getTicket()->issue_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticketDate->getTicket()->due_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticketDate->payment_date, 'd/m/Y'); ?></td>
                                                <td align="center">
                                                    <a href="<?= url('/admin/finisheds/finished/' . $ticketDate->getTicket()->id); ?>" class="btn btn-dark btn-sm" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?= (!$date['start_date']) ? $paginator : null; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/App-Content-->