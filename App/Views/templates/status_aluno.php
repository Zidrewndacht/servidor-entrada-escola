<div class="aluno-card">
    <div class="aluno-status">
        <table class="status-aluno-header-table">
            <tbody>
                <tr><td><strong>Aluno:</strong>     <td><?= esc($aluno->Aluno) ?>  </tr>
                <tr><td><strong>Turma:</strong>     <td><?= esc($aluno->Turma) ?>  </tr>
                <tr><td><strong>Status:</strong>    <td>
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
                </tr>  <!-- alterar para indicar tempo de ausência --> 
                
            </tbody>
        </table>   
    </div>
    <h1>Eventos</h1>
    <div class="scrollable">
        <table>
            <tr>
                <th>Data / Hora</th>
                <th>Tipo</th>
            </tr>
            <?php foreach ($eventos as $evento): ?>
            <tr>
                <td><?= esc($evento->DataHora) ?></td>
                <td class="<?= esc($evento->Tipo) ?>"><?= esc($evento->Tipo) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
