<?php
namespace App\Controllers;

use App\Models\AlunoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AlunoController extends BaseController { /** ********************* Métodos públicos de aluno ******************/

    public function novoAluno() {    //ok //acessado por route /novo_aluno
        helper('form');

        return view('Views/admin/novo_aluno');
    }
    public function POSTnovoAluno() {   //ok //acessado por route POST /novo_aluno
        helper('form');
    
        $data = $this->request->getPost(['Matr', 'Aluno', 'Turma']);
    
        if (! $this->validateData($data, [
            'Matr'  => 'required|max_length[16]|min_length[6]',
            'Aluno' => 'required|max_length[80]|min_length[6]',
            'Turma' => 'required|max_length[16]|min_length[6]',
        ])) { return $this->novoAluno(); }
        $validPost = $this->validator->getValidated();
    
        $model = model(AlunoModel::class);
    
        $result = $model->novoAluno($validPost);
    
        if ($result) {
            return view('Views/admin/ask_first_tag', $data);
        } else {        
            $this->validator->setError('Matr', 'Matrícula já existente');
            return $this->novoAluno();
        }
    }
    
    public function cadastraCSV(){      //acessado por /cad_csv
        helper('form');
        return view('Views/admin/cad_csv');
        // return  view('admin/flashresult',['info' => "Você foi enganado. Não há a possibilidade de cadastro em lote atualmente."]);
    }
    public function POSTcadastraCSV(){      //acessado por /cad_csv
        helper('form');
        $file = $this->request->getFile('csv_file');  // Assuming 'csv_file' is the name of the input field in your form

        if ($file->isValid() && !$file->hasMoved()) {
            $csvData = array_map('str_getcsv', file($file->getRealPath()));
            $model = model(AlunoModel::class);
            // Assuming your model is in the App\Models namespace

            $info ="";
            foreach ($csvData as $row) {
                if (count($row) < 3) { // Skip this row, it does not have enough fields
                    $info .= "Linha inválida.<br>\n";
                    continue;
                }
                $data = [
                    'Aluno' => $row[0],
                    'Matr' => $row[1],
                    'Turma' => $row[2]
                ];

                $result = $model->novoAluno($data);
                if (!$result) {
                    // Handle the case where the record already exists
                    // For example, you could log a message or skip the record
                    // This is just an example, adjust this part based on your actual requirements
                    $info .= "Matrícula {$data['Matr']} já existe.<br>\n";
                }
            }
            if ($info != ""){
                return view('admin/flashresult',['info' => $info]);
            }
            return view('admin/flashresult',['info' => "Todos os dados importados com sucesso."]);
        }
    }

    public function removeAluno($Matr) {   //ok //acessado por route /cad_ban/$1
        $model = model(AlunoModel::class);
        $data = $model->gatherAlunoStatus($Matr);

        helper('form');

        //Converter para flashdata com exibição em nova janela em vez disso:
        return view('Views/admin/cad_ban', $data);
             
        //Para pular confirmação de exclusão, sobstitua conteúdo do método pelo abaixo:
        // $model = model(AlunoModel::class);
        // $result = $model->deleteAluno($Matr);
        // return view('admin/flashresult',['info' => $result]);
    }

    public function POSTremoveAluno($Matr) {   //ok //acessado por route /cad_ban/$1
        $model = model(AlunoModel::class);
        $aluno = $model->gatherAlunoStatus($Matr);

        helper('form');
        $data = $this->request->getPost(['nome']);

        if ($aluno["aluno"]->Aluno != $data["nome"]) { 
            $this->validator = \Config\Services::validation();  //precisa ser inicializado manualmente por algum motivo.
            $this->validator->setError('nome', 'Nome incorreto');
            return $this->removeAluno($Matr); //retorna para nova tentativa
        }
        $model = model(AlunoModel::class);
        
        //Converter para flashdata com exibição em nova janela em vez disso:
        $result = $model->deleteAluno($Matr);
        return view('admin/flashresult',['info' => $result]);
    }
    

    public function statusAluno($Matr){  //ok //acessado por route /status_aluno/$1
        $model = model(AlunoModel::class);
        $data = $model->gatherAlunoStatus($Matr);

        return view('Views/templates/status_aluno', $data);
    }
}