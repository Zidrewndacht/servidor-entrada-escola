<?php
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Shield\Entities\User;


class RespModel extends Model{
    protected $table = 'cad_resp';   
    protected $allowedFields = ['CPF', 'NomeCompleto', 'Email', 'shield_id'];

/** ***************** Acesso à DB: ************************/

    public function criaResp($data) {
        $db = \Config\Database::connect();
        $db->transStart();
        $existingRecord = $this->where('CPF', $data['CPF'])->first();
        if ($existingRecord === null) {
            $users = auth()->getProvider();
            $user = new \CodeIgniter\Shield\Entities\User([
                'username' => $data['CPF'],
                'email'    => $data['Email'],
                'password' => $data['pw'],
            ]);
            $users->save($user);
            $user = $users->findById($users->getInsertID());
            $users->addToDefaultGroup($user);
    
            $this->save([
                'CPF' => $data['CPF'],
                'NomeCompleto'  => $data['NomeCompleto'],
                'Email'  => $data['Email'],
                'shield_id' => $user->id,  // Save the Shield user id
            ]);
            $db->transComplete();
    
            if ($db->transStatus() === FALSE){
                return false;
            } else {
                return true;
            }
        } else {
            $db->transComplete();
            return false;
        }
    }

    public function getRespByCPF($cpf){     //ok
        $db = \Config\Database::connect();
        $builder = $db->table('cad_resp');
        $builder->where('CPF', $cpf);
        $query = $builder->get();
        $resp = $query->getRow();
    
        return $resp;  // This will be an object with Aluno and Turma properties
    }

    public function getRespCPFbyShieldID($user){
        $db = \Config\Database::connect();

        $query = $db->table('cad_resp')
            ->where('shield_id', $user->id)
            ->get();

        $cpf = $query->getRow() ? $query->getRow()->CPF : null;
        return $cpf;
    }

    public function getAlunosByRespCPF($cpf){       //não sei como, mas funciona.
        $db = \Config\Database::connect();
        $builder = $db->table('assoc');
        $builder->join('cad_alunos', 'assoc.AlunoID = cad_alunos.Matr');
        $builder->where('assoc.RespCPF', $cpf);
        $query = $builder->get();
        $alunos = $query->getResultArray();
    
        return $alunos;  // This will be an array of Aluno objects
    }
    
    public function getAllAlunos(){ 
        $db = \Config\Database::connect();
        $builder = $db->table('cad_alunos');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function updateAssociations($cpf, $alunos){
        $db = \Config\Database::connect();
        $builder = $db->table('assoc');

        // Delete existing associations
        $builder->where('RespCPF', $cpf);
        $builder->delete();

        // Add new associations
        if ($alunos !== null) {
            foreach ($alunos as $aluno) {
                $data = [
                    'AlunoID' => $aluno,
                    'RespCPF' => $cpf
                ];
                $builder->insert($data);
            }
        }
    }

    public function deleteResp($cpf){       //ok
        $db = \Config\Database::connect();
        $db->transStart(); // inicia transação atômica restaurável:
        // Remove associações, eventos e cadastro de aluno, respectivamente:
        $db->table('assoc')->where('RespCPF', $cpf)->delete();
        $userRecord = $db->table('cad_resp')->where('CPF', $cpf)->get()->getRowArray();
        $db->table('cad_resp')->where('CPF', $cpf)->delete();

        $users = auth()->getProvider();
        $user = $users->find($userRecord['shield_id']);
        if ($user) { $users->delete($user->id);  }

        $db->transComplete();   //completa transação
        if ($db->transStatus() === FALSE) { //se status negativo, restaure:
            $db->transRollback();
            return "Falha desconhecida ao remover responsável $cpf";
        } else {    //senão, aplique alterações à DB:
            $db->transCommit();
            return "Responsável $cpf removido com sucesso";
        }
    }

    public function updateEmail($data) {        //ok
        $db = \Config\Database::connect();
        $builder = $db->table('cad_resp'); 
        $builder->set('Email', $data["Email"]);
        $builder->where('CPF', $data["CPF"]);
        $builder->update();
            
        // Update the Shield user
        $users = auth()->getProvider();
        $userRecord = $db->table('cad_resp')->where('CPF', $data["CPF"])->get()->getRowArray();
        $user = $users->find($userRecord['shield_id']);
        if ($user) {
            $user->email = $data["Email"];
            $users->save($user);
        }
    }

/** ***************** Auxílio a controllers: ************************/


    public function gatherRespDetail($cpf){ //usada por removeResp e POSTremoveResp 
        $model = model(RespModel::class);
        $data['CPF'] = $cpf;
        $data['NomeCompleto'] = $model->getRespByCPF($cpf)->NomeCompleto;
        $data['Email'] = $model->getRespByCPF($cpf)->Email;
        $data['Alunos'] = $model->getAlunosByRespCPF($cpf);
        $data['AllAlunos'] = $model->getAllAlunos();
        
        return $data;
    }

}
