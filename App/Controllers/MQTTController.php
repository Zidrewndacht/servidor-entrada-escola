<?php
namespace App\Controllers;

use App\Models\AlunoModel;
use App\Models\SetupModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MQTTController extends BaseController {
    public function cadNovoPublish($Matr) {
        $model = model(AlunoModel::class);
        $student = $model->where('Matr', $Matr)->first();
    
        $mqttModel = model(SetupModel::class);
        $mqttSettings = $mqttModel->getMqttSettings();
        $mqtt_client_id =   "PHPbackend";
        $clean_session =    true;
    
        $mqtt = new MqttClient($mqttSettings['mqtt_server'], $mqttSettings['mqtt_port'],$mqtt_client_id,  MqttClient::MQTT_3_1_1);
    
        $settings = (new ConnectionSettings)
            ->setUsername($mqttSettings['mqtt_username'])
            ->setPassword($mqttSettings['mqtt_password'])
            ->setKeepAliveInterval(60)
            ->setConnectTimeout(3)
            ->setUseTls(true);
            // ->setTlsSelfSignedAllowed(true);
    
        try {
            $mqtt->connect($settings, $clean_session);
    
            $payload = json_encode([
                'UID' => substr(strval(time()), -8) . substr(strval(rand()), -8),
                'Matr' => $student['Matr'],
                'Aluno' => $student['Aluno'],
                'Turma' => $student['Turma'],
                'Tag_ID' => $student['Tag_ID'],
            ]);
    
            $mqtt->publish('cad_novo', $payload, 2, true); //com retenção, QoS 2
    
            $mqtt->disconnect();
            return $this->response->setBody(json_encode(['status' => 'success', 'payload' => $payload]));
        } catch (\Exception $e) {
            return $this->response->setBody(json_encode(['status' => 'error', 'message' => $e->getMessage()]));
        }
    }

    public function writeTag($Matr){    
        $model = model(AlunoModel::class);
        $student = $model->where('Matr', $Matr)->first();

        $mqttModel = model(SetupModel::class);
        $mqttSettings = $mqttModel->getMqttSettings();
        $data = [
            'Aluno' => $student['Aluno'],
            'Turma' => $student['Turma'],
            'Tag_ID' => $student['Tag_ID'],
            'Matr' => $Matr,
            'mqttSettings' => $mqttSettings,
        ];
        return view('Views/admin/mqtt_write_view', $data);
    }

    public function POSTevtAluno(){
        $request = \Config\Services::request();
        $rawBody = $request->getBody();

        // Mover variável para configuração MQTT:
        $allowed_token = 'fkhjgdiojhhjg984w5u8quaj09g54';

        $request_token = $this->request->getHeader('X-Auth-Token')->getValue();

        $data = json_decode($rawBody);
        if ($request_token != $allowed_token) {
            log_message('error', 'Failed to validate token on data: {data}', ['data' => print_r($data, true)]);
            log_message('error', $allowed_token);
            log_message('error', $request_token);
            return $this->fail('Forbidden', 403);
        }

        $payload = json_decode($data->payload);

        $model = new \App\Models\StatusModel();
        $model->insertEvent($payload);
    
        // log_message('error', 'Received data: {data}', ['data' => print_r($data, true)]);
        return $this->response->setStatusCode(200)->setJSON(['message' => 'Success']);
    }
}