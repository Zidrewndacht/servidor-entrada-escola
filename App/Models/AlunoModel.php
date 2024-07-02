<?php
namespace App\Models;
use CodeIgniter\Model;

class AlunoModel extends Model{
    protected $table = 'cad_alunos';   
    protected $allowedFields = ['Aluno', 'Matr', 'Tag_exists', 'Tag_ID', 'Turma'];

/** ***************** Acesso à DB: ************************/

    public function novoAluno($data) {
        $existingRecord = $this->where('Matr', $data['Matr'])->first();

        if ($existingRecord === null) {
            $this->save([
                'Matr'          => $data['Matr'],
                'Aluno'         => $data['Aluno'],
                'Turma'         => $data['Turma'],
                'Tag_exists'    => false,
                'Tag_ID'        => substr(strval(time()), -8) . substr(strval(rand()), -7), //15 caracteres para caber em 16 bytes no ESP32 incluindo \0.
            ]);
            return true;
        } else {
            return false;
        }
    }

    public function getLastEventForEachAluno(){
        //bruxaria do Bing Chat, estudar depois. db->query parece permitir acesso a todas as tabelas automaticamente.
        //converter queries para Query Builder futuramente para maior segurança e portabilidade.
        //Left Join para incluir itens que não têm associação (alunos novos, que nunca tiveram registro de E/S)
        $query = $this->db->query("     
            SELECT cad_alunos.Matr, cad_alunos.Aluno, cad_alunos.Turma, eventos.DataHora, eventos.Tipo
            FROM cad_alunos
            LEFT JOIN (
                SELECT AlunoID, MAX(DataHora) as LastEventTime
                FROM eventos
                GROUP BY AlunoID
            ) AS last_events ON cad_alunos.Matr = last_events.AlunoID
            LEFT JOIN eventos ON eventos.AlunoID = last_events.AlunoID AND eventos.DataHora = last_events.LastEventTime
        ");
        return $query->getResult();
    }
    
    public function getAlunoByMatr($Matr){
        $db = \Config\Database::connect();
        $builder = $db->table('cad_alunos');
        $builder->where('Matr', $Matr);
        $query = $builder->get();
        $aluno = $query->getRow();
    
        return $aluno;  // This will be an object with Aluno and Turma properties
    }
    
    public function getAlunoEvents($Matr){     //exemplo query builder do bing chat:
        $db = \Config\Database::connect();
        $builder = $db->table('eventos');
        $builder->where('AlunoID', $Matr);
        $builder->orderBy('DataHora', 'DESC');
        $query = $builder->get();
        $eventos = $query->getResult();
        
        return $eventos;
    }

    public function deleteAluno($Matr){
        $db = \Config\Database::connect();
        
        $db->transStart(); // inicia transação atômica restaurável:
        // Remove associações, eventos e cadastro de aluno, respectivamente:
        $db->table('assoc')->where('AlunoID', $Matr)->delete();
        $db->table('eventos')->where('AlunoID', $Matr)->delete();
        $db->table('cad_alunos')->where('Matr', $Matr)->delete();

        $db->transComplete();   //completa transação
        if ($db->transStatus() === FALSE) { //se status negativo, restaure:
            $db->transRollback();
            return "Falha ao remover aluno $Matr";
        } else {    //senão, aplique alterações à DB:
            $db->transCommit();
            return "Aluno com matrícula $Matr removido com sucesso";
        }
    }

/** ***************** Auxílio a controllers: ************************/
    
    public function gatherAlunoStatus($Matr){
        $aluno = $this->getAlunoByMatr($Matr);
        $eventos = $this->getAlunoEvents($Matr);
        $data = [
            'aluno' =>   $aluno,
            'eventos' => $eventos,        
        ];
        return $data;
    }

}
