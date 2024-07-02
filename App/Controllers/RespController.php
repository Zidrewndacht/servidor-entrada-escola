<?php
namespace App\Controllers;

use App\Models\RespModel;
use App\Models\AlunoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class RespController extends BaseController {
    
/** ***************** Métodos públicos de responsável: ******************/

    public function novoResp() {  //ok      //acessado por /novo_resp
        helper('form');

        return view('Views/admin/novo_resp');
    }

    public function POSTnovoResp() {  //ok     //acessado por POST /novo_resp
        helper('form');

        $data = $this->request->getPost(['CPF', 'NomeCompleto', 'Email', 'pw', 'pw2']);

        if (! $this->validateData($data, [
            'CPF'           => 'required|max_length[11]|min_length[11]',
            'NomeCompleto'  => 'required|max_length[80]|min_length[6]',
            'Email'         => 'required|max_length[254]|valid_email',
            'pw'            => 'required|max_length[255]',
            'pw2'           => 'required|max_length[255]|matches[pw]',
        ])) { return $this->novoResp(); }
        $validPost = $this->validator->getValidated();

        //Mover este código para método em RespModel?

        $model = model(RespModel::class);
        $result = $model->criaResp($validPost);

        if ($result) {
            return $this->assocResp($validPost['CPF']); //se responsável criado corretamente, redireciona para associações
        } else {        
            $this->validator->setError('CPF', 'Usuário já existe com o CPF ou email indicado');
            return $this->novoResp();
        }
    }


    public function removeResp($cpf){   //ok    acessado por resp_ban/$1
        $model = model(RespModel::class);
        $data = $model->gatherRespDetail($cpf);
        
        helper('form');

        return view('Views/admin/resp_ban', $data);
    } 
    public function POSTremoveResp($cpf){   //ok    acessado por resp_ban/$1
        $model = model(RespModel::class);
        $resp = $model->gatherRespDetail($cpf);

        helper('form');
        $data = $this->request->getPost(['nome']);

        if ($resp["NomeCompleto"] != $data["nome"]) { 
            $this->validator = \Config\Services::validation();  //precisa ser inicializado manualmente por algum motivo.
            $this->validator->setError('nome', 'Nome incorreto');
            return $this->removeResp($cpf);
         }
         
        $model = model(RespModel::class);
        $result = $model->deleteResp($cpf);
        return  view('admin/flashresult',['info' => $result]); 
    }


    public function assocResp($cpf){
        helper('form');

        $model = model(RespModel::class);
        $data = $model->gatherRespDetail($cpf);
        return view('admin/assoc', $data);
    }
    public function POSTassocResp($cpf){
        // helper('session');
        helper('form');
        $model = model(RespModel::class);
        $alunos = $this->request->getPost('Alunos');
        $model->updateAssociations($cpf, $alunos);
        $resp = $model->gatherRespDetail($cpf);

        return view('admin/flashresult',['info' =>   'Associações do responsável '.$resp['NomeCompleto'].' atualizadas.',]);

    }


    public function alteraRespEmail($cpf) { 
        helper('form');
        
        $model = model(RespModel::class);
        $resp = $model->getRespByCPF($cpf);
        $data = [
            'CPF' =>   $cpf,
            'NomeCompleto' => $resp->NomeCompleto,        
            'Email' =>  $resp->Email, 
        ];

        return view('Views/admin/altera_email', $data);
    }
    public function POSTalteraRespEmail($cpf){  //acessado por POST /email_reset/$1
        helper('form');

        $data = $this->request->getPost(['CPF', 'Email']);

        // se validação falhar, retorma ao form:
        if (! $this->validateData($data, [
            // 'CPF'           => 'required|max_length[11]|min_length[11]',
            'Email'         => 'required|max_length[254]|valid_email',
        ])) { return $this->alteraRespEmail($cpf); }
        $validPost = $this->validator->getValidated();

        $model = model(RespModel::class);
        $newData = ([
            'CPF'    => $cpf,
            'Email'  => $validPost['Email'],
        ]);
        $model->updateEmail($newData);

        return view('admin/flashresult',['info' => "Email de $cpf alterado para {$validPost['Email']}"]);
    }


    public function pwRespReset($cpf){      //acessado por /pw_reset/$1
        return  view('admin/flashresult',['info' => "Responsável $cpf, Funcionalidade de reset de senha não implementada. Requer CodeIgniter Shield"]);
        //ativar flag de primeiro acesso
        //enviar senha provisória por email e link para login
    }


    public function statusResp($cpf){       //acessado por route /status_resp/$1, usado em /admin
        $respModel = model(RespModel::class);
        $respData = $respModel->gatherRespDetail($cpf);
        $alunoModel = model(AlunoModel::class);
        $alunos = $respModel->getAlunosByRespCPF($cpf);
    
        $output =  view('templates/status_resp', $respData);

        foreach ($alunos as $aluno) {
            $alunoData = $alunoModel->gatherAlunoStatus($aluno['Matr']);
            $output .= view('Views/templates/status_aluno', $alunoData);
        }
        return $output;
    }

    public function statusRespUser($cpf){       //acessado por route /status_resp/$1, usado em /admin
        $respModel = model(RespModel::class);
        $respData = $respModel->gatherRespDetail($cpf);
        $alunoModel = model(AlunoModel::class);
        $alunos = $respModel->getAlunosByRespCPF($cpf);

        //precisa ser separado pois não pode incluir header de modal.
        //Conteúdo em seguida é o mesmo de /status_resp/$1
        $output = view('templates/status_resp_header', ['title' => 'Status Responsável'])
                . view('templates/status_resp', $respData);

        foreach ($alunos as $aluno) {
            $alunoData = $alunoModel->gatherAlunoStatus($aluno['Matr']);
            $output .= view('Views/templates/status_aluno', $alunoData);
        }
        $output .= view('Views/templates/status_resp_footer');
        return $output;
    }

}