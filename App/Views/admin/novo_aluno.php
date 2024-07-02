<div class="modal-status">
    <h1>Cadastrar novo aluno</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info"><?= validation_list_errors() ?></div>
    </div>
</div>

<form action="/admin/novo_aluno" method="post">
    <?= csrf_field() ?>

    <p>Cadastre um aluno por vez aqui. Certifique-se de gravar uma nova tag RFID para o novo aluno após a criação do cadastro.</p>
    <p>Para cadastrar múltiplos alunos em lote a partir de um arquivo CSV, 
    selecione "Cadastrar em lote" no topo do painel administrativo em vez disso.</p>

    <label for="Matr">Matrícula</label>
    <input type="text" id="Matr" name="Matr" value="<?= set_value('Matr') ?>" minlength="6" maxlength="16">

    <label for="Aluno">Nome do aluno</label>
    <input type="text" id="Aluno" name="Aluno" value="<?= set_value('Aluno') ?>" minlength="6" maxlength="80">
    
    <label for="Turma">Turma</label>
    <input type="text" id="Turma" name="Turma" value="<?= set_value('Turma') ?>" minlength="6" maxlength="16">

    <input type="submit" name="submit" value="Salvar">
</form>
