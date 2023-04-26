<?php $v->layout("_admin"); ?>
<!--App-Content-->
<div class="app-content  my-3 my-md-5">
    <div class="side-app">
        <div class="page-header">
            <h4 class="page-title">Acordos</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Acordos</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title">Acordos</h3>
                            <div>
                                <a href="<?= url('/admin/agreements/agreement'); ?>" class="btn btn-pill btn-success"><i
                                        class="fa fa-plus"></i> Adicionar Acordo</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered border-top mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>CPF/CNPJ</th>
                                        <th>Data do Acordo</th>
                                        <th>Parcelas</th>
                                        <th>Valor</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($agreements) : ?>
                                        <?php foreach ($agreements as $agreement) : ?>
                                            <tr>
                                                <th scope="row"><?= $agreement->id; ?></th>
                                                <td><?= $agreement->getClient()->name; ?></td>
                                                <td class=""><?= $agreement->getClient()->cpf_cnpj; ?></td>
                                                <td><?= date_fmt($agreement->created_at, 'd/m/Y'); ?></td>
                                                <th><?= $agreement->installments; ?></th>
                                                <th class="mask-money"><?= $agreement->value; ?></th>
                                                <td align="center">
                                                    <a href="<?= url('/admin/agreements/agreement/'.$agreement->id); ?>"
                                                        class="btn btn-info btn-sm" title="Editar"><i
                                                            class="fa fa-pencil"></i></a>

                                                    <a href="#" class="btn btn-danger btn-sm"
                                                        data-post="<?= url("/admin/agreements/agreement/{$agreement->id}"); ?>"
                                                        data-action="delete"
                                                        data-confirm="ATENÇÃO: Tem certeza que deseja excluir o acordo e todos os dados relacionados a ele? Essa ação não pode ser feita!"
                                                        data-agreement_id="<?= $agreement->id; ?>" title="Excluir"><i
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