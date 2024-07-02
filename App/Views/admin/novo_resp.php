<div class="modal-status">
    <h1>Cadastrar novo responsável</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info"><?= validation_list_errors() ?></div>
    </div>

</div>

<form action="/admin/novo_resp" method="post">
    <?= csrf_field() ?>
    <p>Certifique-se de associar alunos ao novo responsável após sua criação, 
        no próximo passo ou posteriormente clicando em "Alterar assoc." na 
        tabela de responsáveis</p>

    <label for="CPF">CPF</label>
    <input type="text" id="CPF" name="CPF" value="<?= set_value('CPF') ?>" minlength="11" maxlength="11">

    <label for="NomeCompleto">Nome</label>
    <input type="text" id="NomeCompleto"  value="<?= set_value('NomeCompleto') ?>" minlength="6" maxlength="80">
    
    <label for="Email">E-mail</label>
    <input type="email" id="Email" name="Email" value="<?= set_value('Email') ?>" minlength="4" maxlength="254">

    <label for="pw">Senha</label>
    <input type="password" id="pw"  name="pw" value="<?= set_value('pw') ?>" >

    <label for="pw2">Confirmar Senha</label>
    <input type="password" id="pw2"  name="pw2" value="<?= set_value('pw2') ?>" >

    <input type="submit" name="submit" value="Salvar e associar alunos">
</form>
