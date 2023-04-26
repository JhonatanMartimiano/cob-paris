<?php $v->layout("_admin"); ?>
<!--App-Content-->
<?php if (!$client): ?>
<div class="app-content  my-3 my-md-5">
    <div class="side-app">
        <div class="page-header">
            <h4 class="page-title">Clientes</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home'); ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= url('/admin/clients/home'); ?>">Clientes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Criar Cliente</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card m-b-20">
                    <div class="card-header">
                        <h3 class="card-title">Criar Cliente</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= url('/admin/clients/client'); ?>" method="post">
                            <input type="hidden" name="action" value="create">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>CPF/CNPJ</label>
                                    <input type="text" class="form-control" name="cpf_cnpj">
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
<?php else: ?>
<div class="app-content  my-3 my-md-5">
    <div class="side-app">
        <div class="page-header">
            <h4 class="page-title">Clientes</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home'); ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= url('/admin/clients/home'); ?>">Clientes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Cliente</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card m-b-20">
                    <div class="card-header">
                        <h3 class="card-title">Editar Cliente</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= url('/admin/clients/client/'.$client->id); ?>" method="post">
                            <input type="hidden" name="action" value="update">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" name="name" value="<?= $client->name; ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>CPF/CNPJ</label>
                                    <input type="text" class="form-control" name="cpf_cnpj" value="<?= $client->cpf_cnpj; ?>">
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