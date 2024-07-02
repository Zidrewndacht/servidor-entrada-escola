<?php
namespace App\Controllers;

use App\Models\AlunoModel;
use App\Models\RespModel;
use App\Models\StatusModel;
use App\Models\SetupModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AdminController extends BaseController {

/** ********************* Métodos acessados via XHR em admin index: ******************/

    public function tabelaAlunos(){      //usada por index, gera view diretamente
        $model = model(AlunoModel::class);
        $data['alunos'] = $model->getLastEventForEachAluno();

        return view('Views/admin/tabela_alunos', $data);
    }
    public function tabelaResp(){        //usada por index, gera view diretamente 
        $model = model(RespModel::class);
        $resp = $model->findAll();

        foreach ($resp as &$r) {
            $r['Alunos'] = $model->getAlunosByRespCPF($r['CPF']);
        }

        return view('Views/admin/tabela_resp', ['resp' => $resp]);
    }
    public function tabelaEvt(){         //usada por index, gera view diretamente 
        $model = model(StatusModel::class);
        $data['result']  = $model->readLog();

        return view('Views/admin/tabela_evt', $data);
    }

/** ***************** Métodos públicos de administrador: ******************/
    public function redir() {
        //redireciona index para /admin por padrão. 
        //SuperAdminFilter força redirecionamento adicional se necessário (usuário não admin).
        return redirect()->to("/admin"); 
    }
    
    public function adminIndex() {
        $mqttModel = model(SetupModel::class);
        $mqttSettings = $mqttModel->getMqttSettings();
        $data = [
            'mqttSettings' => $mqttSettings,
        ];
        return view('Views/admin/admin', $data);
    }
    public function setupAdmin() {    //ok //acessado por route /setup_admin
        helper('form');
        $users = auth()->getProvider();
        $user = $users->findById(auth()->id());
        $email = $user->email;
            
        return view('Views/admin/setup_admin', ['email' => $email]);
    }

    public function POSTsetupAdmin() {   //ok //acessado por route POST /novo_aluno
        helper('form');
        $data = $this->request->getPost(['Email', 'novoEmail','pw', 'pw2']);
        $users = auth()->getProvider();
        $user = $users->findById(auth()->id());
        $email = $user->email;

        if (! $this->validateData($data, [
            'novoEmail'     => 'required|max_length[254]|valid_email',
            'pw'            => 'required|max_length[255]',
            'pw2'           => 'required|max_length[255]|matches[pw]',
        ])) { return $this->setupAdmin(); }
        $validPost = $this->validator->getValidated();

        $model = model(SetupModel::class);
        $newData = ([
            'Email'      => $email,
            'novoEmail'  => $validPost['novoEmail'],
            'pw'         => $validPost['pw'],
        ]);
        $result = $model->updateAdminCredentials($newData);
    
        return view('admin/flashresult',['info' => $result ]);
    }
    public function setupMQTT() { 
        helper('form');
        $model = model(SetupModel::class);
        $mqtt = $model->getMqttSettings();
        return view('Views/admin/setup_mqtt', ['mqtt' => $mqtt]);
    }
    
    public function POSTsetupMQTT() {   
        helper('form');
        $data = $this->request->getPost(['mqtt_server', 'mqtt_port','mqtt_ws_port','mqtt_client_id', 'mqtt_username', 'mqtt_password']);
        if (! $this->validateData($data, [
            'mqtt_server'     => 'required',
            'mqtt_port'       => 'required|integer',
            'mqtt_ws_port'    => 'required|integer',
            'mqtt_username'   => 'required',
            'mqtt_password'   => 'required',
        ])) { return $this->setupMQTT(); }
        $validPost = $this->validator->getValidated();
    
        $model = model(SetupModel::class);
        $newData = ([
            'mqtt_server'     => $validPost['mqtt_server'],
            'mqtt_port'       => $validPost['mqtt_port'],
            'mqtt_ws_port'    => $validPost['mqtt_ws_port'],
            'mqtt_username'   => $validPost['mqtt_username'],
            'mqtt_password'   => $validPost['mqtt_password'],
        ]);
        $result = $model->updateMqttSettings($newData);
    
        return view('admin/flashresult',['info' => $result ]);
    }

    public function getIP() {   //pode ser usada para identificar IP externo do ESP32, não é mais necessário com ESP-IDF.
        $ip_address = $this->request->getIPAddress();
    
        // Check if the IP address is IPv6
        if (filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $groups = explode(':', $ip_address);
            $groups = array_map(function($group) {
                return str_pad($group, 4, '0', STR_PAD_LEFT);
            }, $groups);
            $ip_address = implode(':', $groups);
        }
    
        $this->response->setHeader('Transfer-Encoding', 'identity');
        return $this->response->setBody($ip_address)->setContentType('text/plain');
    }
}