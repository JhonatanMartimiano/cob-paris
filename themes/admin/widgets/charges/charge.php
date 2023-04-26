<?php $v->layout("_admin"); ?>
<!--App-Content-->
<?php if (!$charge) : ?>
    <div class="app-content  my-3 my-md-5">
        <div class="side-app">
            <div class="page-header">
                <h4 class="page-title">Cobranças</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/charges/home'); ?>">Cobranças</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Criar Cobrança</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card m-b-20">
                        <div class="card-header">
                            <h3 class="card-title">Criar Cobrança</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?= url("/admin/charges/charge/{$ticket->id}"); ?>" method="post">
                                <input type="hidden" name="action" value="create">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nome</label>
                                        <input type="text" class="form-control" name="name" value="<?= $ticket->getClient()->name; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>CPF/CNPJ</label>
                                        <input type="tel" class="form-control" name="cpf_cnpj" value="<?= $ticket->getClient()->cpf_cnpj; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Número do Boleto</label>
                                        <input type="number" class="form-control" name="ticket_number" value="<?= $ticket->ticket_number; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Número do Banco</label>
                                        <input type="number" class="form-control" name="bank_number" value="<?= $ticket->bank_number; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Número do Pedido</label>
                                        <input type="number" class="form-control" name="request_number" value="<?= $ticket->request_number; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Data de Emissão</label>
                                        <input type="date" class="form-control" name="issue_date" value="<?= $ticket->issue_date; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Data de Vencimento</label>
                                        <input type="date" class="form-control" name="due_date" value="<?= $ticket->due_date; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor</label>
                                        <input type="text" class="form-control mask-money" name="value" value="<?= $ticket->value; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor Pago</label>
                                        <input type="text" class="form-control mask-money" name="amount_paid">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor Multa/Juros</label>
                                        <input type="text" class="form-control mask-money" value="<?= number_format($value, 0); ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor Desconto</label>
                                        <input type="text" class="form-control mask-money" name="discount_value">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Situação</label>
                                        <select name="situation" class="form-control">
                                            <option value="">Selecione a situação</option>
                                            <option value="defeated">Vencido</option>
                                            <option value="finished">Liquidado</option>
                                            <option value="negotiation">Negociação</option>
                                            <option value="lowForPayment">Baixa para Pagamento</option>
                                            <option value="courts">Juízado</option>
                                            <option value="protested">Protestado</option>
                                            <option value="protestedAgreed">Protestado porém Acordado</option>
                                            <option value="canceled">Cancelado</option>
                                            <option value="open">Em Aberto</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Baixado</label>
                                        <select name="downloaded" class="form-control">
                                            <option value="">Selecione a resposta</option>
                                            <option value="yes">Sim</option>
                                            <option value="no">Não</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Dias Vencidos</label>
                                        <input type="text" class="form-control" value="<?= (date_diff_system($ticket->due_date) < 0) ? abs(date_diff_system($ticket->due_date)) : ''; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Data da Cobrança</label>
                                        <input type="date" class="form-control" name="charge_date">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data do Pagamento</label>
                                        <input type="date" class="form-control" name="payment_date">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Forma de Pagamento</label>
                                        <select name="form_payment" class="form-control">
                                            <option value="">Selecione a forma de pagamento</option>
                                            <option value="ticked">Boleto</option>
                                            <option value="pix">Pix</option>
                                            <option value="card">Cartão</option>
                                            <option value="transfer">Transferência</option>
                                            <option value="installmentPlan">Crediário</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Anexo</label>
                                        <input type="file" name="receipt" class="form-control">
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
                <h4 class="page-title">Cobranças</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/charges/home'); ?>">Cobranças</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Cobrança</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card m-b-20">
                        <div class="card-header">
                            <h3 class="card-title">Editar Cobrança</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?= url('/admin/charges/charge/' . $ticket->id); ?>" method="post">
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
                                    <div class="form-group col-md-4">
                                        <label>Número do Boleto</label>
                                        <input type="number" class="form-control" name="ticket_number" value="<?= $ticket->ticket_number; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Número do Banco</label>
                                        <input type="number" class="form-control" name="bank_number" value="<?= $ticket->bank_number; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Número do Pedido</label>
                                        <input type="number" class="form-control" name="request_number" value="<?= $ticket->request_number; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Data de Emissão</label>
                                        <input type="date" class="form-control" name="issue_date" value="<?= $ticket->issue_date; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Data de Vencimento</label>
                                        <input type="date" class="form-control" name="due_date" value="<?= $ticket->due_date; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor</label>
                                        <input type="text" class="form-control mask-money" name="value" value="<?= $ticket->value; ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor Pago</label>
                                        <input type="text" class="form-control mask-money" name="amount_paid" value="<?= $ticket->amount_paid; ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor Multa/Juros</label>
                                        <input type="text" class="form-control mask-money" value="<?= number_format($value, 0); ?>" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Valor Desconto</label>
                                        <input type="text" class="form-control mask-money" name="discount_value" value="<?= $ticket->discount_value; ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Situação</label>
                                        <select name="situation" class="form-control">
                                            <?php
                                            $situationValue = $ticket->situation;
                                            $selected = function ($value) use ($situationValue) {
                                                return ($situationValue == $value ? 'selected' : '');
                                            }
                                            ?>
                                            <option value="">Selecione a situação</option>
                                            <option value="defeated" <?= $selected('defeated'); ?>>Vencido</option>
                                            <option value="finished" <?= $selected('finished'); ?>>Liquidado</option>
                                            <option value="negotiation" <?= $selected('negotiation'); ?>>Negociação</option>
                                            <option value="lowForPayment" <?= $selected('lowForPayment'); ?>>Baixa para Pagamento</option>
                                            <option value="courts" <?= $selected('courts'); ?>>Juízado</option>
                                            <option value="protested" <?= $selected('protested'); ?>>Protestado</option>
                                            <option value="protestedAgreed" <?= $selected('protestedAgreed'); ?>>Protestado porém Acordado</option>
                                            <option value="canceled" <?= $selected('canceled'); ?>>Cancelado</option>
                                            <option value="open" <?= $selected('open'); ?>>Em Aberto</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Baixado</label>
                                        <select name="downloaded" class="form-control">
                                            <?php
                                            $downloadedValue = $charge->downloaded;
                                            $selected = function ($value) use ($downloadedValue) {
                                                return ($downloadedValue == $value ? 'selected' : '');
                                            }
                                            ?>
                                            <option value="">Selecione a resposta</option>
                                            <option value="yes" <?= $selected('yes'); ?>>Sim</option>
                                            <option value="no" <?= $selected('no'); ?>>Não</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Dias Vencidos</label>
                                        <input type="text" class="form-control" value="<?= (date_diff_system($ticket->due_date) < 0) ? abs(date_diff_system($ticket->due_date)) : ''; ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Data da Cobrança</label>
                                        <input type="date" class="form-control" name="charge_date" value="<?= $charge->charge_date; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data do Pagamento</label>
                                        <input type="date" class="form-control" name="payment_date" value="<?= $charge->payment_date; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Forma de Pagamento</label>
                                        <select name="form_payment" class="form-control">
                                            <?php
                                            $formPaymentValue = $charge->form_payment;
                                            $selected = function ($value) use ($formPaymentValue) {
                                                return ($formPaymentValue == $value ? 'selected' : '');
                                            }
                                            ?>
                                            <option value="">Selecione a forma de pagamento</option>
                                            <option value="ticked" <?= $selected('ticked'); ?>>Boleto</option>
                                            <option value="pix" <?= $selected('pix'); ?>>Pix</option>
                                            <option value="card" <?= $selected('card'); ?>>Cartão</option>
                                            <option value="transfer" <?= $selected('transfer'); ?>>Transferência</option>
                                            <option value="installmentPlan" <?= $selected('installmentPlan'); ?>>Crediário</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Anexo</label>
                                        <input type="file" name="receipt" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-center">
                                            <a href="<?= url("/storage/{$charge->receipt}") ?>" class="btn btn-info" download>Baixar Anexo</a>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Relatório de Comunicação</label>
                                        <textarea name="communication_report" cols="30" rows="10" class="form-control"><?= $charge->communication_report; ?></textarea>
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