<div class="modal-status">
    <h1>Remover responsável</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info">  <?= validation_list_errors() ?>             </div>
    </div>
        
    <p><strong>Responsável:</strong> <?= esc($NomeCompleto) ?></p>
    <p><strong>CPF:</strong> <?= esc($CPF) ?></p>
    <p><strong>Alunos associados:</strong>
    <?php 
        $associatedAlunos = [];     //alunos atualmente associados, inicia assumindo que não há nenhum
        foreach ($AllAlunos as $a): 
            if(in_array($a['Matr'], array_column($Alunos, 'AlunoID'))): 
                $associatedAlunos[] = $a['Aluno'];      //anexa 'Aluno' ao fim do array (notação PHP é assim mesmo)
            endif; 
        endforeach; 

        if(!empty($associatedAlunos)):  
            echo implode(', ', $associatedAlunos);
        else:
            echo 'Nenhum aluno associado.';
        endif;
    ?>

</div>

<form action="/admin/resp_ban/<?= esc($CPF) ?>" method="post">
    <?= csrf_field() ?>
  
    <p>Para excluir o responsável <?= esc($NomeCompleto) ?>, repita abaixo o nome completo do responsável selecionado:</p>
    <label for="nome">Responsável</label>
    <input type="text" name="nome">

    <input type="submit" name="submit" class="delete-submit" value="Excluir responsável">
</form>
