<?php $v->layout("_admin"); ?>
<!--App-Content-->
<?php if (!$ticket) : ?>
    <div class="app-content  my-3 my-md-5">
        <div class="side-app">
            <div class="page-header">
                <h4 class="page-title">Boletos</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/tickets/home'); ?>">Boletos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Criar Boleto</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card m-b-20">
                        <div class="card-header">
                            <h3 class="card-title">Criar Boleto</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-inline d-flex flex-column aling-items-center mb-3">
                                <div class="input-message mr-3"></div>
                                <div class="nav-search mb-3">
                                    <input type="search" class="form-control header-search" name="searchClient" placeholder="Buscar Cliente" aria-label="Search">
                                    <button class="btn btn-primary btn-search-client" type="button" data-url="<?= url('/'); ?>"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <form action="<?= url('/admin/tickets/ticket'); ?>" method="post">
                                <input type="hidden" name="action" value="create">
                                <input type="hidden" name="id_client">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nome</label>
                                        <input type="text" class="form-control" name="name" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>CPF/CNPJ</label>
                                        <input type="text" class="form-control" name="cpf_cnpj" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Número do Boleto</label>
                                        <input type="number" class="form-control" name="ticket_number">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Número do Banco</label>
                                        <input type="number" class="form-control" name="bank_number">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Número do Pedido</label>
                                        <input type="number" class="form-control" name="request_number">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Acordo</label>
                                        <select name="id_agreement" class="form-control">
                                            <option value="">Selecione o acordo</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor</label>
                                        <input type="text" class="form-control mask-money" name="value">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Data de Emissão</label>
                                        <input type="date" class="form-control" name="issue_date">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Data de Vencimento</label>
                                        <input type="date" class="form-control" name="due_date">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success ">Criar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="app-content  my-3 my-md-5">
        <div class="side-app">
            <div class="page-header">
                <h4 class="page-title">Boletos</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/tickets/home'); ?>">Boletos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Boleto</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card m-b-20">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">Editar Boleto</h3>
                                <?= ($ticket->status === 'canceled') ? "<h3 class='alert alert-danger mb-0'>ACORDO CANCELADO</h3>" : ''; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?= url('/admin/tickets/ticket/' . $ticket->id); ?>" method="post" data-id="<?= $ticket->id ?>">
                                <input type="hidden" name="action" value="update">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nome</label>
                                        <input type="text" class="form-control" name="name" value="<?= $ticket->getClient()->name; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>CPF/CNPJ</label>
                                        <input type="text" class="form-control" name="cpf_cnpj" value="<?= $ticket->getClient()->cpf_cnpj; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Número do Boleto</label>
                                        <input type="number" class="form-control" name="ticket_number" value="<?= $ticket->ticket_number; ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Número do Banco</label>
                                        <input type="number" class="form-control" name="bank_number" value="<?= $ticket->bank_number; ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Número do Pedido</label>
                                        <input type="number" class="form-control" name="request_number" value="<?= $ticket->request_number; ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Acordo</label>
                                        <select name="id_agreement" class="form-control">
                                            <option value="">Selecione o acordo</option>
                                            <?php
                                            $agreementID = $ticket->id_agreement;
                                            $selected = function ($id) use ($agreementID) {
                                                return ($agreementID == $id) ? 'selected' : '';
                                            };
                                            ?>
                                            <?php if ($agreements) : ?>
                                                <?php foreach ($agreements as $agreement) : ?>
                                                    <option <?= $selected($agreement->id); ?> value="<?= $agreement->id; ?>"><?= $agreement->id . ' - ' . date_fmt($agreement->created, 'd/m/Y'); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Valor</label>
                                        <input type="text" class="form-control mask-money" name="value" value="<?= $ticket->value; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data de Emissão</label>
                                        <input type="date" class="form-control" name="issue_date" value="<?= $ticket->issue_date; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data de Vencimento</label>
                                        <input type="date" class="form-control" name="due_date" value="<?= $ticket->due_date; ?>">
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>Número do Boleto</label>
                                                <input type="number" class="form-control" name="other_ticket_number">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Data de Vencimento</label>
                                                <input type="date" class="form-control" name="other_due_date">
                                            </div>
                                            <div class="form-group d-flex justify-content-start align-items-end col-md-2">
                                                <button type="button" class="btn-ticket-create btn btn-light" data-id="<?= $ticket->id ?>">
                                                    <span>
                                                        <i class="fa fa-upload"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success ">Atualizar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!--/App-Content-->
<?php $v->start('scripts'); ?>
<script src="<?= theme('/assets/js/ticket.js', CONF_VIEW_ADMIN) ?>"></script>
<?php $v->end('scripts'); ?>