<?php $v->layout("_admin"); ?>
<!--App-Content-->
<?php if (!$agreement) : ?>
    <div class="app-content  my-3 my-md-5">
        <div class="side-app">
            <div class="page-header">
                <h4 class="page-title">Acordos</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/agreements/home'); ?>">Acordos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Criar Acordo</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card m-b-20">
                        <div class="card-header">
                            <h3 class="card-title">Criar Acordo</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-inline d-flex flex-column aling-items-center mb-3">
                                <div class="input-message mr-3"></div>
                                <div class="nav-search mb-3">
                                    <input type="search" class="form-control header-search" name="searchClient" placeholder="Buscar Cliente" aria-label="Search">
                                    <button class="btn btn-primary btn-search-client" type="button" data-url="<?= url('/'); ?>"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <form action="<?= url("/admin/agreements/agreement"); ?>" method="post">
                                <input type="hidden" name="action" value="create">
                                <input type="hidden" name="id_client">
                                <input type="hidden" name="id_tickets">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nome</label>
                                        <input type="text" class="form-control" name="name" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>CPF/CNPJ</label>
                                        <input type="text" class="form-control" name="cpf_cnpj" disabled>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered border-top mb-0">
                                                <thead>
                                                    <tr align="center">
                                                        <th>ID</th>
                                                        <th>Número do Boleto</th>
                                                        <th>Data de Emissão</th>
                                                        <th>Data de Vencimento</th>
                                                        <th>Valor</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Número de Parcelas</label>
                                        <select name="installments" class="form-control">
                                            <option value="">Selecione a forma de pagamento</option>
                                            <option class="opt-reset" value="1">1x</option>
                                            <option value="2">2x</option>
                                            <option value="3">3x</option>
                                            <option value="4">4x</option>
                                            <option value="5">5x</option>
                                            <option value="6">6x</option>
                                            <option value="7">7x</option>
                                            <option value="8">8x</option>
                                            <option value="9">9x</option>
                                            <option value="10">10x</option>
                                            <option value="11">11x</option>
                                            <option value="12">12x</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Forma de Pagamento</label>
                                        <select name="form_payment" class="form-control">
                                            <option value="">Selecione a forma de pagamento</option>
                                            <option value="ticked">Boleto</option>
                                            <option value="pix">Pix</option>
                                            <option value="card">Cartão</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Valor Total:</label>
                                        <input type="text" name="value" class="form-control agreement-value">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Relatório de Comunicação</label>
                                        <textarea name="communication_report" cols="30" rows="10" class="form-control"></textarea>
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
                <h4 class="page-title">Acordos</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/agreements/home'); ?>">Acordos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Acordo</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card m-b-20">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">Editar Acordo</h3>
                                <?= ($agreement->status === 'canceled') ? "<h3 class='alert alert-danger mb-0'>ACORDO CANCELADO</h3>" : ''; ?>
                                <h3 class="card-title">Data do Acordo: <?= date_fmt($agreement->creted, 'd/m/Y'); ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?= url('/admin/agreements/agreement/' . $agreement->id); ?>" method="post">
                                <input type="hidden" name="action" value="update">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nome</label>
                                        <input type="text" class="form-control" name="name" value="<?= $client->name; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>CPF/CNPJ</label>
                                        <input type="text" class="form-control" name="cpf_cnpj" value="<?= $client->cpf_cnpj; ?>" disabled>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered border-top mb-0">
                                                <thead>
                                                    <tr align="center">
                                                        <th>ID</th>
                                                        <th>Número do Boleto</th>
                                                        <th>Data de Emissão</th>
                                                        <th>Data de Vencimento</th>
                                                        <th>Valor</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($agreements) : ?>
                                                        <?php foreach ($agreements as $agreement1) : ?>
                                                            <tr align="center">
                                                                <th scope="row"><?= $agreement1->getTicket()->id; ?></th>
                                                                <th><?= $agreement1->getTicket()->ticket_number; ?></th>
                                                                <th><?= date_fmt($agreement1->getTicket()->issue_date, 'd/m/Y'); ?></th>
                                                                <th><?= date_fmt($agreement1->getTicket()->due_date, 'd/m/Y'); ?></th>
                                                                <th class="mask-money"><?= number_format((date_diff_system($agreement1->getTicket()->due_date) < 0) ? $agreement1->getTicket()->value * 0.05 + $agreement1->getTicket()->value + ($agreement1->getTicket()->value * 0.0033 * (abs(date_diff_system($agreement1->getTicket()->due_date)))) : 0, 0); ?></th>
                                                                <th><input type="checkbox" class="check-value mask-money" value="<?= number_format((date_diff_system($agreement1->getTicket()->due_date) < 0) ? $agreement1->getTicket()->value * 0.05 + $agreement1->getTicket()->value + ($agreement1->getTicket()->value * 0.0033 * (abs(date_diff_system($agreement1->getTicket()->due_date)))) : 0, 0); ?>"></th>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Número de Parcelas</label>
                                        <?php
                                        $installmentsValue = $agreement->installments;
                                        $selected = function ($value) use ($installmentsValue) {
                                            return ($installmentsValue == $value) ? 'selected' : '';
                                        };
                                        ?>
                                        <select name="installments" class="form-control">
                                            <option value="">Selecione a forma de pagamento</option>
                                            <option <?= $selected(1); ?> class="opt-reset" value="1">1x</option>
                                            <option <?= $selected(2); ?> value="2">2x</option>
                                            <option <?= $selected(3); ?> value="3">3x</option>
                                            <option <?= $selected(4); ?> value="4">4x</option>
                                            <option <?= $selected(5); ?> value="5">5x</option>
                                            <option <?= $selected(6); ?> value="6">6x</option>
                                            <option <?= $selected(7); ?> value="7">7x</option>
                                            <option <?= $selected(8); ?> value="8">8x</option>
                                            <option <?= $selected(9); ?> value="9">9x</option>
                                            <option <?= $selected(10); ?> value="10">10x</option>
                                            <option <?= $selected(11); ?> value="11">11x</option>
                                            <option <?= $selected(12); ?> value="12">12x</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Forma de Pagamento</label>
                                        <?php
                                        $formPaymentValue = $agreement->form_payment;
                                        $selected = function ($value) use ($formPaymentValue) {
                                            return ($formPaymentValue == $value) ? 'selected' : '';
                                        };
                                        ?>
                                        <select name="form_payment" class="form-control">
                                            <option value="">Selecione a forma de pagamento</option>
                                            <option <?= $selected('ticked'); ?> value="ticked">Boleto</option>
                                            <option <?= $selected('pix'); ?> value="pix">Pix</option>
                                            <option <?= $selected('card'); ?> value="card">Cartão</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Valor Total:</label>
                                        <input type="text" name="value" value="<?= $agreement->value; ?>" class="form-control mask-money agreement-value">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Relatório de Comunicação</label>
                                        <textarea name="communication_report" cols="30" rows="10" class="form-control"><?= $agreement->communication_report; ?></textarea>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= url('/admin/agreements/home'); ?>" class="btn btn-info">Voltar</a>
                                    <button type="submit" class="btn btn-danger ">Cancelar Acordo</button>
                                </div>
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
<script src="<?= theme('/assets/js/agreement.js', CONF_VIEW_ADMIN) ?>"></script>
<?php $v->end('scripts'); ?>