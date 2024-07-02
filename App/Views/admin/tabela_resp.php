<!-- wrappers estão em admin.php -->
<table id="tabela-resp">
    <thead>
        <tr>
            <th rowspan="2" id="novo-resp-btn">
                <a class="modal-small"  title="Clique para iniciar o cadastro individual de um novo responsável. Para adicionar múltiplos responsáveis em lote em vez disso, clique no botão no topo da tela em vez deste."  href="/admin/novo_resp">Cadastrar responsável </a> 
            </th>
            <th colspan="2">
                <h1 id="title-resp">Responsáveis</h1>
            </th>
            <th class="tabela-resp-dummy"><!-- dummy para alinhamento da pesquisa--></th>  
            <th class="tabela-pesquisa-th" colspan="4">            
                <input data-table-id="tabela-resp" type="search" class="tabela-pesquisa-campo" placeholder="Pesquisar responsáveis">
            </th>
        </tr>
        <tr>
            <th class="tabela-resp-nome sortable"><span class="arrow">&#x2195;</span> Responsável</th>
            <th class="tabela-resp-assoc" colspan="2">Alunos associados</th>
            <th class="tabela-resp-email">E-mail</th>
            <th class="tabela-resp-manage" colspan="3">Gerenciar</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        /* findAll() entrega dados em forma de array em vez de objetos */ 
        foreach ($resp as $r): 
            $alunos = $r['Alunos'];
        ?>
        <tr> 
            <td class="tabela-resp-status-btn">
                <a data-table="resp" class="status-resp-btn" title="Clique para visualizar simultaneamente status e eventos de todos os alunos associados com este responsável." href="/status_resp/<?= $r['CPF']; ?>">Visualizar status</a>
            </td>
            <td class="tabela-resp-nome"><?= $r['NomeCompleto']; ?></td>
            <td class="tabela-resp-assoc">
            <?php if (!empty($alunos)): ?>
                <?php foreach ($alunos as $a): ?>
                    <span class="tabela-resp-aluno"><?= $a['Aluno']; ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="tabela-resp-aluno empty">Nenhum</span>
            <?php endif; ?>
            </td>
            <td class="tabela-resp-assoc-btn assoc-btn"><a data-table="resp" class="modal-small"  title="Clique para alterar a lista de alunos associados com este responsável." href="/admin/assoc/<?= $r['CPF']; ?>">Alterar assoc.</a></td>
        
            <td class="tabela-resp-email"><?= $r['Email']; ?></td>
            <td class="tabela-resp-manage-btn resp-email-btn"><a data-table="resp" class="modal-small" title="Clique para atualizar e-mail deste responsável" href="/admin/email_reset/<?= $r['CPF']; ?>">Alterar E-mail        </a></td>
            <!-- <td class="tabela-resp-manage-btn resp-pw-btn"><a data-table="resp" class="modal-small"    title="Clique para atualizar senha deste responsável" href="/admin/pw_reset/<?= $r['CPF']; ?>">   Gerar nova senha     </a></td> -->
            <td class="tabela-resp-manage-btn resp-ban-btn"><a data-table="resp" class="modal-small"   title="Clique para excluir do sistema permanentemente este responsável, e todos os dados associados, exceto alunos." href="/admin/resp_ban/<?= $r['CPF']; ?>">   Excluir responsável  </a></td>
            
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>