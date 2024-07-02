<div class="modal-status">
    <h1>Cadastrar em lote</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info"><?= validation_list_errors() ?></div>
    </div>
</div>
<!-- enctype="multipart/form-data" necessário para upload de arquivo: -->
<form action="/admin/cad_csv" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <p>A implementação deste recurso é preliminar e deve sofrer alterações posteriores.</p>
    <p>São previstas funcionalidades para <strong>adicionar</strong> responsáveis em lote, criar <strong>associações</strong>  em lote e <strong>remover</strong>  alunos e responsáveis em lote. Atualmente, apenas a criação de novos cadastros de aluno em lote é suportada.</p>
    <p>Envie um arquivo CSV com um aluno por linha, no formato <em>'aluno,matrícula,turma'</em>, aqui:</p>
    <label for="csv_file">Arquivo: </label>
    <input type="file" name="csv_file" id="csv_file">

    <input type="submit" name="submit" value="Enviar">
</form>
