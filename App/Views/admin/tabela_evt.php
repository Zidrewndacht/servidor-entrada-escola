<!-- wrappers estão em admin.php -->
<table id="tabela-evt">
    <thead>
        <tr>
            <th colspan="1" id="title-evt"><h1>Últimos 50 eventos</h1></th>
            <th style="min-width:40px"><!-- dummy para alinhamento da pesquisa--></th>  
            <th class="tabela-pesquisa-th" colspan="1">            
                <input data-table-id="tabela-evt" type="search" class="tabela-pesquisa-campo" placeholder="Pesquisar">
            </th>
        </tr>
        <tr>
            <th id="tabela-evt-aluno" colspan="2">Aluno</th>
            <th id="tabela-evt-data">Evento</th>
        </tr>
        
    </thead>
    <tbody>
        <?php foreach ($result as $row): ?>
        <tr>
            <td><?= $row->Aluno ?></td>
        <td  colspan="2"><?= $row->Tipo . ' em ' . date('d/m/Y \à\s H:i:s', strtotime($row->DataHora)) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>