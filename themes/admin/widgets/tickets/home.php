<?php $v->layout("_admin"); ?>
<!--App-Content-->
<div class="app-content  my-3 my-md-5">
    <div class="side-app">
        <div class="page-header">
            <h4 class="page-title">Boletos</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Boletos</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title">Boletos</h3>
                            <div>
                                <a href="<?= url('/admin/tickets/ticket'); ?>" class="btn btn-pill btn-success"><i
                                        class="fa fa-plus"></i> Adicionar Boleto</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form class="form-inline mb-1" action="<?= url('/admin/tickets/home'); ?>" method="post">
                                <div class="nav-search">
                                    <input type="search" class="form-control header-search" name="s"
                                        value="<?= $search; ?>" placeholder="Buscar…" aria-label="Search">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                            <table class="table table-bordered border-top mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>CPF/CNPJ</th>
                                        <th>Número do Boleto</th>
                                        <th>Número do Pedido</th>
                                        <th>Valor</th>
                                        <th>Data de Emissão</th>
                                        <th>Data de Vencimento</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($tickets): ?>
                                        <?php foreach ($tickets as $ticket): ?>
                                            <tr>
                                                <th scope="row"><?= $ticket->id; ?></th>
                                                <td><?= $ticket->getClient()->name; ?></td>
                                                <td class=""><?= $ticket->getClient()->cpf_cnpj; ?></td>
                                                <td><?= $ticket->ticket_number; ?></td>
                                                <td><?= $ticket->request_number; ?></td>
                                                <td class="mask-money"><?= $ticket->value; ?></td>
                                                <td><?= date_fmt($ticket->issue_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($ticket->due_date, 'd/m/Y'); ?></td>
                                                <td align="center">
                                                    <a href="<?= url('/admin/tickets/ticket/'.$ticket->id); ?>"
                                                        class="btn btn-info btn-sm" title="Editar"><i
                                                            class="fa fa-pencil"></i></a>

                                                    <a href="#" class="btn btn-danger btn-sm"
                                                        data-post="<?= url("/admin/tickets/ticket/{$ticket->id}"); ?>"
                                                        data-action="delete"
                                                        data-confirm="ATENÇÃO: Tem certeza que deseja excluir o boleto e todos os dados relacionados a ele? Essa ação não pode ser feita!"
                                                        data-user_id="<?= $ticket->id; ?>" title="Excluir"><i
                                                            class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
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