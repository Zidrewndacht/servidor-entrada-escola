<?php
namespace App\Models;
use CodeIgniter\Model;

class StatusModel extends Model{
    public function readLog(){
        $db = \Config\Database::connect();
        $query = $db->table('eventos')
        ->select('cad_alunos.Aluno, eventos.DataHora, eventos.Tipo')
        ->join('cad_alunos', 'cad_alunos.Matr = eventos.AlunoID')
        ->orderBy('eventos.DataHora', 'DESC')
        ->limit(50)  // Limit the results to the last 100 events
        ->get();

        $result = $query->getResult();
        return $result;
    }

    public function getLastEventTipo($AlunoID) {
        $db = \Config\Database::connect();
        $query = $db->table('eventos')
        ->select('Tipo')
        ->where('AlunoID', $AlunoID)
        ->orderBy('DataHora', 'DESC')
        ->limit(1)
        ->get();

        $result = $query->getRow();
        return $result ? $result->Tipo : null;
    }

    public function insertEvent($data) {
        $db = \Config\Database::connect();
        $lastEventTipo = $this->getLastEventTipo($data->Matr);
        $newTipo = $lastEventTipo === 'Entrada' ? 'Saída' : 'Entrada';

        $db->table('eventos')->insert([
            'AlunoID' => $data->Matr,
            'DataHora' => date('Y-m-d H:i:s', $data->timestamp),
            'Tipo' => $newTipo
        ]);
    }
}