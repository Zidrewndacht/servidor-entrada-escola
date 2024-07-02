<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class SetupModel extends Model{
    protected $table = 'mqtt_settings';
    protected $allowedFields = ['mqtt_server', 'mqtt_port', 'mqtt_ws_port','mqtt_username', 'mqtt_password'];

    public function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->forge = \Config\Database::forge();
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        if (!$this->db->tableExists($this->table)) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 5,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'mqtt_server' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ],
                'mqtt_port' => [
                    'type' => 'INT',
                    'constraint' => 5,
                ],
                'mqtt_ws_port' => [
                    'type' => 'INT',
                    'constraint' => 5,
                ],
                'mqtt_username' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ],
                'mqtt_password' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ]
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable($this->table);
        }
    }
    public function getMqttSettings() {
        $settings = $this->find(1);
        if ($settings) {
            return $settings;
        } else {
            return [    //valores iniciais caso não existam na DB:
                'mqtt_server' => ' ',
                'mqtt_port' => '8883',
                'mqtt_ws_port' => '8084',
                'mqtt_username' => ' ',
                'mqtt_password' => ' ',
            ];
        }
    }
    
    public function updateMqttSettings($data) {
        if ($this->find(1)) {
            $this->update(1, $data);
        } else {
            $this->insert($data);
        }
        return "Credenciais MQTT alteradas com sucesso. É necessário recarregar a página para atualizar configurações do cliente MQTT";
    }
    public function updateAdminCredentials($data) {
        $users = auth()->getProvider();
        $admin = $users->findByCredentials(['email' => $data["Email"]]);
    
        if ($admin) {
            $admin->fill([
                'email' => $data["novoEmail"],
                'password' => $data["pw"]
            ]);
    
            if ($users->save($admin)) {
                return "Credenciais do administrador alteradas com sucesso";
            } else {
                return "Falha ao alterar credenciais de administrador"; //não deve ocorrer
            }
        } else {
            return "Administrador não encontrado";  //não deve ocorrer
        }
    }
}