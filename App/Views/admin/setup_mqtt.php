<div class="modal-status">
    <h1>Configurações do broker MQTT</h1>
    <div id="session-info-field">
        <?php 
        /*<div id="success-info"><?= session()->getFlashdata('success') ?></div>
        <div id="error-info"><?= session()->getFlashdata('error') ?></div> */?>
        <div id="validation-info"><?= validation_list_errors() ?></div>
    </div>
</div>

<form action="/admin/setup_mqtt" method="post">
    <?= csrf_field() ?>
    <p>As configurações abaixo controlam apenas a comunicação entre o servidor PHP, o cliente JS e o broker MQTT. As configurações não se aplicam ao dispositivo IoT, que precisa ser configurado separadamente.</p>
    <p>Por razões de segurança e compatibilidade, apenas brokers MQTT seguros (TLS) são suportados.</p>
    <p>As alterações serão percebidas no próximo login.</p>

    <label for="mqtt_server">Servidor MQTT</label>
    <input type="text" name="mqtt_server" value="<?= $mqtt['mqtt_server'] ?>" required>

    <label for="mqtt_port">Porta MQTT TLS</label>
    <input type="number" name="mqtt_port" value="<?= $mqtt['mqtt_port'] ?>" required>

    <label for="mqtt_ws_port">Porta WebSocket WSS</label>
    <input type="number" name="mqtt_ws_port" value="<?= $mqtt['mqtt_ws_port'] ?>" required>

    <label for="mqtt_username">Usuário MQTT</label>
    <input type="text" name="mqtt_username" value="<?= $mqtt['mqtt_username'] ?>" required>

    <label for="mqtt_password">Senha MQTT</label>
    <input type="password" name="mqtt_password" value="<?= $mqtt['mqtt_password'] ?>" required>

    <input type="submit" name="submit" value="Salvar configurações">
</form>