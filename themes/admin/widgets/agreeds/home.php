<?php $v->layout("_admin"); ?>
<!--App-Content-->
<div class="app-content  my-3 my-md-5">
    <div class="side-app">
        <div class="page-header">
            <h4 class="page-title">Acordados</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('/admin/dash/home') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Acordados</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title">Acordados</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form class="form-inline mb-1" action="<?= url('/admin/agreeds/home'); ?>" method="post">
                                <div class="nav-search">
                                    <input type="search" class="form-control header-search" name="s" value="<?= $search; ?>" placeholder="Buscar…" aria-label="Search">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                            <table class="table table-bordered border-top mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NOME</th>
                                        <th>CPF/CNPJ</th>
                                        <th>NÚMERO DO BOLETO</th>
                                        <th>VALOR</th>
                                        <th>DATA DE EMISSÃO</th>
                                        <th>DATA DE VENCIMENTO</th>
                                        <th>NÚMERO DO ACORDO</th>
                                        <th>AÇÕES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($agreeds) : ?>
                                        <?php foreach ($agreeds as $agreed) : ?>
                                            <tr>
                                                <th scope="row"><?= $agreed->id; ?></th>
                                                <td><?= $agreed->getClient()->name; ?></td>
                                                <td class=""><?= $agreed->getClient()->cpf_cnpj; ?></td>
                                                <td><?= $agreed->ticket_number; ?></td>
                                                <td class="mask-money"><?= number_format($agreed->value * 0.05 + $agreed->value + ($agreed->value * 0.0033 * (abs(date_diff_system($agreed->due_date)))), 0); ?></td>
                                                <td><?= date_fmt($agreed->issue_date, 'd/m/Y'); ?></td>
                                                <td><?= date_fmt($agreed->due_date, 'd/m/Y'); ?></td>
                                                <td><?= $agreed->number_agreement ?></td>
                                                <td align="center">
                                                    <button class="btn btn-danger remove-agreed" data-url="<?= url('/'); ?>" data-id="<?= $agreed->id; ?>">REMOVER DO ACORDO</button>
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
<?php $v->start('scripts'); ?>
<script>
    let removeAgreed = document.querySelectorAll('.remove-agreed')

    for (let i = 0; i < removeAgreed.length; i++) {
        removeAgreed[i].addEventListener('click', () => {
            let url = removeAgreed[i].getAttribute('data-url')
            axios.post(`${url}admin/agreeds/remove-agreed/${removeAgreed[i].getAttribute('data-id')}`).then((response) => {
                if (response.data.reload) {
                    window.location.href = `${url}admin/agreeds/home`
                }
            })
        })
    }
</script>
<?php $v->end('scripts'); ?>