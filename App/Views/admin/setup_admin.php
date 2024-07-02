<div class="modal-status">
    <h1>Configurações do administrador</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info"><?= validation_list_errors() ?></div>
    </div>
</div>

<form action="/admin/setup_admin" method="post">
    <?= csrf_field() ?>
    <p>As alterações abaixo serão percebidas no próximo login.</p>
    
    <label for="Email">E-mail anterior</label>
    <input type="email" name="Email" value="<?= $email ?>" minlength="4" maxlength="254" disabled>
    <label for="Email">Novo E-mail</label>
    <input type="email" name="novoEmail" value="<?= set_value('Email') ?>" minlength="4" maxlength="254">

    <label for="pw">Senha</label>
    <input type="password" name="pw" value="<?= set_value('pw') ?>" >

    <label for="pw2">Confirmar Senha</label>
    <input type="password" name="pw2" value="<?= set_value('pw2') ?>" >

    <input type="submit" name="submit" value="Salvar credenciais">
</form>