<div class="modal-status">
    <h1>Excluir aluno</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info"><?= validation_list_errors() ?></div>
    </div>
    
    <p><strong>Aluno:</strong> <?= esc($aluno->Aluno) ?></p>
        <p><strong>Turma:</strong> <?= esc($aluno->Turma) ?></p>
        <p><strong>Status:</strong> 
        <?php 
            if (!empty($eventos) && $eventos[0]->Tipo) {       //eventos são reposicionados na query da DB, logo eventos[0] é o primeiro evento
                if ($eventos[0]->Tipo == 'Entrada') {
                    echo 'Presente';
                } else {
                    $now = new DateTime();
                    $lastExit = DateTime::createFromFormat('Y-m-d H:i:s', $eventos[0]->DataHora);
                    $interval = date_diff($now, $lastExit);
                    $days = $interval->format('%a');
                    echo $days >= 2 ? 'Ausente há ' . $days . ' dias' : 'Ausente';
                }
            } else {
                echo 'Nenhum evento de entrada ou saída';
            }
        ?>
        </p>  <!-- alterar para indicar tempo de ausência -->  
</div>
<form action="/admin/cad_ban/<?= esc($aluno->Matr) ?>" method="post">
    <?= csrf_field() ?>

    <p>Para excluir o aluno <?= esc($aluno->Aluno) ?>, repita abaixo o nome completo do aluno selecionado:</p>
    <label for="nome">Aluno</label>
    <input type="text" name="nome">

    <input type="submit" name="submit" class="delete-submit" value="Excluir aluno">
</form>

