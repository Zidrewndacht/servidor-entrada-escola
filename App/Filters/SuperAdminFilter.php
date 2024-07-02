<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\RespModel;


class SuperAdminFilter implements FilterInterface{
    public function before(RequestInterface $request, $arguments = null){   
        //comente conteúdo este método aqui, e a linha abaixo em filters.php, para pular autenticação admin:
        //        'session' => ['except' => ['login*', 'register', 'auth/a/*', 'logout', 'rfid_lido', 'ip']],

        $user = auth()->user();
        if (!$user) { return redirect()->to('/login'); }
        if (!($user->inGroup('superadmin'))) {
            $model = model(RespModel::class);
            $cpf = $model->getRespCPFbyShieldID($user);
            
            $url = '/resp/'.$cpf;
            return redirect()->to($url);
        } 
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // Do nothing
    }
}