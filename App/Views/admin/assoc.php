<style type="text/css"> 
    @import url("/choices.min.css"); 
    @import url("/style_choices.css");  /** importa personalizações após estilo base de Choices.js */
</style>

<div class="modal-status">
    <h1>Associar responsável com alunos</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info"><?= validation_list_errors() ?></div>
    </div>
</div>
<form action="/admin/assoc/<?= esc($CPF) ?>" method="post">
    <?= csrf_field() ?>

    <label for="CPF">CPF</label>
    <input type="text" name="CPF" value="<?= esc($CPF) ?>" minlength="11" maxlength="11" disabled>

    <label for="NomeCompleto">Nome</label>
    <input type="text" id="NomeCompleto" name="NomeCompleto" value="<?= esc($NomeCompleto) ?>"disabled>

    <label for="Alunos">Alunos</label>
    <select name="Alunos[]" multiple>
    <?php foreach ($AllAlunos as $a): ?>
        <option value="<?= $a['Matr']; ?>" <?= in_array($a['Matr'], array_column($Alunos, 'AlunoID')) ? 'selected' : ''; ?>>
            <?= $a['Turma']; ?> - <?= $a['Aluno']; ?>
        </option>
    <?php endforeach; ?>
    </select>
    <p> Você pode pesquisar por aluno ou turma ao digitar no campo de alunos. </p>
    <p> Selecione um ou mais alunos para associar com o responsável.
        O responsável terá acesso direto ao status dos alunos selecionados.</p>
    <p>Para conta de usuário do SOE, associe a todos os estudantes aqui.</p>
    <input type="submit" name="submit" value="Salvar">
</form>
