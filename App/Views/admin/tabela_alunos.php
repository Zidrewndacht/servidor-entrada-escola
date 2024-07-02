<!-- wrappers estão em admin.php -->
<table id="tabela-alunos">
    <thead>
        <tr>
            <th class="tabela-aluno-status-btn" rowspan="2" id="novo-aluno-btn">
                <a class="modal-small" title="Clique para iniciar o cadastro individual de um novo aluno. Para adicionar múltiplos alunos em lote em vez disso, clique no botão no topo da tela em vez deste."  href="/admin/novo_aluno">Cadastrar<br>novo aluno </a>
            </th>
            <th class="title-tabela-aluno" colspan="1"> 
                <h1 id="title-aluno">Alunos</h1>
            </th>
            <th><!-- dummy para alinhamento da pesquisa--></th>    
            <th id="tabela-pesquisa-alunos" colspan="5">            
                <input data-table-id="tabela-alunos" type="search" class="tabela-pesquisa-campo" placeholder="Pesquisar alunos">
            </th>
        </tr>
        <tr>
            <th class="tabela-aluno-nome sortable"><span class="arrow">&#x2195;</span> Aluno </th>
            <th class="tabela-aluno-turma sortable"><span class="arrow">&#x2195;</span> Turma</th>
            <th class="tabela-aluno-tipo sortable"><span class="arrow">&#x2195;</span> Situação</th>
            <th class="tabela-aluno-lastevt">Último evento </th>
            <th class="tabela-aluno-manage" colspan="2">Gerenciar</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($alunos as $aluno): ?>
        <tr>
            <td class="tabela-aluno-status-btn">
                <?php /* esconder botão visualizar quando não há eventos relacionados */
                if ($aluno->DataHora): /* oculta opção se não há eventos a visualizar */?>   
                    <a data-table="alunos" class="status-aluno-btn modal-small" title="Clique para visualizar status e eventos deste aluno. Este botão só é exibido para alunos que possuem pelo menos um evento registrado."  href="/status_aluno/<?= esc($aluno->Matr); ?>">Visualizar eventos</a>
                <?php endif; ?>
            </td>
            <td class="tabela-aluno-nome"><?= esc($aluno->Aluno); ?></td>
            <td class="tabela-aluno-turma"><?= esc($aluno->Turma); ?></td>
            <td class="tabela-aluno-tipo <?php if (!$aluno->Tipo){echo 'empty'; } 
                // else{    //sem uso atualmente. Pode ser usado para estilizar ausências.
                //     if ($aluno->Tipo == 'Entrada') {echo 'aluno-presente';} 
                //     else {echo 'aluno-ausente';}
                // }?>">
            <?php 
            if ($aluno->Tipo) {
                if ($aluno->Tipo == 'Entrada') {
                    echo 'Presente';
                } else {
                    $now = new DateTime();
                    $lastExit = DateTime::createFromFormat('Y-m-d H:i:s', $aluno->DataHora);
                    $interval = date_diff($now, $lastExit);
                    $days = $interval->format('%a');
                    echo $days >= 2 ? 'Ausente há ' . $days . ' dias' : 'Ausente';
                }
            } else {
                echo 'Nenhum';
            }
            ?>
            </td>
            <td class="tabela-aluno-lastevt"><?= esc($aluno->DataHora ? (date('d/m/Y \à\s H:i:s', strtotime($aluno->DataHora))) : 'Nenhum'); ?></td>
            <td class="tabela-aluno-manage-btn rfid-write-btn"><a data-table="alunos" class="iframe-small" title="Clique para iniciar gravação de nova tag RFID. Essa ação substitui a tag antiga, se existir." href="/admin/write_tag/<?= esc($aluno->Matr); ?>">Gravar tag</a></td>
            <td class="tabela-aluno-manage-btn cad-ban-btn" title="Clique para excluir do sistema permanentemente este aluno, e todos os dados associados, exceto responsáveis."><a data-table="alunos" class="modal-small" href="/admin/cad_ban/<?= esc($aluno->Matr); ?>">Excluir aluno</a></td>
            
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>