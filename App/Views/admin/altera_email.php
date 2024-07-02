<div class="modal-status">
    <h1>Alterar e-mail de responsável</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info"><?= validation_list_errors() ?></div>
    </div>
</div>

<form action="/admin/email_reset/<?= esc($CPF) ?>" method="post">
    <?= csrf_field() ?>

    <label for="CPF">CPF</label>
    <input type="text" name="CPF" value="<?= esc($CPF) ?>" minlength="11" maxlength="11" disabled>

    <label for="NomeCompleto">Nome</label>
    <input type="text" name="NomeCompleto" value="<?= esc($NomeCompleto) ?>"disabled>
    
    <label for="Email">E-mail</label>
    <input type="email" name="Email" value="<?= esc($Email) ?>">

    <input type="submit" name="submit" value="Salvar novo e-mail">
</form>
