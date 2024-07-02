<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente MQTT para gravação de tag</title>
    <script src="/mqtt.5.6.1.min.js"></script>      
    <link rel="stylesheet" href="<?= esc(base_url('/normalize.css'))?>">
    <link rel="stylesheet" href="<?= esc(base_url('/style_mqtt.css'))?>">
</head>
<body class="waiting">
<div class="modal-status">
    <h1>Gravar nova tag</h1>
</div>
<div class="mqtt-content-wrapper"> 
    <div id="aluno-info">
        <!-- converter em tabela? -->
         <table>
            <tbody>
                <tr><td><strong>Aluno:</strong>     <td><?= esc($Aluno) ?>  </tr>
                <tr><td><strong>Turma:</strong>     <td><?= esc($Turma) ?>  </tr>
                <tr><td><strong>Matrícula:</strong> <td><?= esc($Matr) ?>   </tr>
                <tr><td><strong>Tag ID:</strong>    <td><?= esc($Tag_ID) ?> </tr>
            </tbody>
         </table>
    </div>
    <div id="mqtt-rfid-status">
        Aguardando envio da solicitação de gravação pelo servidor PHP...
    </div>
    <div id="icon">
        <div id="spinner">
            <svg width="200px" height="200px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="hsl(220, 35%, 73%)" class="bi bi-gear-wide">
                <path d="M8.932.727c-.243-.97-1.62-.97-1.864 0l-.071.286a.96.96 0 0 1-1.622.434l-.205-.211c-.695-.719-1.888-.03-1.613.931l.08.284a.96.96 0 0 1-1.186 1.187l-.284-.081c-.96-.275-1.65.918-.931 1.613l.211.205a.96.96 0 0 1-.434 1.622l-.286.071c-.97.243-.97 1.62 0 1.864l.286.071a.96.96 0 0 1 .434 1.622l-.211.205c-.719.695-.03 1.888.931 1.613l.284-.08a.96.96 0 0 1 1.187 1.187l-.081.283c-.275.96.918 1.65 1.613.931l.205-.211a.96.96 0 0 1 1.622.434l.071.286c.243.97 1.62.97 1.864 0l.071-.286a.96.96 0 0 1 1.622-.434l.205.211c.695.719 1.888.03 1.613-.931l-.08-.284a.96.96 0 0 1 1.187-1.187l.283.081c.96.275 1.65-.918.931-1.613l-.211-.205a.96.96 0 0 1 .434-1.622l.286-.071c.97-.243.97-1.62 0-1.864l-.286-.071a.96.96 0 0 1-.434-1.622l.211-.205c.719-.695.03-1.888-.931-1.613l-.284.08a.96.96 0 0 1-1.187-1.186l.081-.284c.275-.96-.918-1.65-1.613-.931l-.205.211a.96.96 0 0 1-1.622-.434L8.932.727zM8 12.997a4.998 4.998 0 1 1 0-9.995 4.998 4.998 0 0 1 0 9.996z"/>
            </svg>
        </div>
    </div>
    <button id="btn-cancel">Cancelar gravação</button>
<script>
let icon = document.getElementById("icon");
let MQTTStatusHTML = document.getElementById("mqtt-rfid-status");
fetch("/admin/mqtt_write_request/<?=$Matr?>", {
    headers: {
        "X-Requested-With": "XMLHttpRequest"
    }
})
.then(response => response.text())
.then(text => {
    console.log("Raw response:", text);
    return JSON.parse(text);
})
.then(data => {
    if (data.status === 'success') {
        MQTTStatusHTML.innerHTML = 
            "Solicitação de gravação enviada pelo servidor.<br>Aguardando dispositivo IoT.";
    } else {
        MQTTStatusHTML.innerHTML += 
            "Erro nos dados: " + data.message;
    }
})
.catch(error => {
    MQTTStatusHTML.innerHTML += 
        "<br>Erro de rede: " + error.message;
});
/** Baseado no exemplo do EMQX:
 *  https://github.com/emqx/MQTT-Client-Examples/blob/master/mqtt-client-WebSocket/ws-mqtt.html
 */
const clientId = 'mqttjs_' + Math.random().toString(16).substring(2, 8);
const connectUrl = 'wss://[' + <?= json_encode($mqttSettings['mqtt_server']) ?> + ']:'+parseInt(<?=json_encode($mqttSettings['mqtt_ws_port'])?>)+'/mqtt';
const options = {
    keepalive: 30,
    clientId: clientId,
    clean: true,
    connectTimeout: 5000,
    username: <?= json_encode($mqttSettings['mqtt_username']) ?>,
    password: <?= json_encode($mqttSettings['mqtt_password']) ?>,
    reconnectPeriod: 1000,
}
const qos = 1;

console.log('Conectando cliente JS ao broker MQTT via WebSocket...')
const client = mqtt.connect(connectUrl, options)

client.on('error', (err) => {  // https://github.com/mqttjs/MQTT.js#event-error
    console.log('Erro ao conectar: ', err);
    client.end()
})
client.on('reconnect', () => { // https://github.com/mqttjs/MQTT.js#event-reconnect
    console.log('Reconectando...');
})
client.on('connect', () => {    // https://github.com/mqttjs/MQTT.js#event-connect
    console.log('Cliente conectado. Usando ID ' + clientId);
    client.subscribe(['cad_aguardando','cad_gravado','rfid_cancelado'], { qos }, (error) => {
        if (error) {
            console.log('Erro de subscribe: ', error);
            return;
        }
        console.log(`Subscribe em tópicos executado`);
    })
})

document.getElementById("btn-cancel").onclick = function(event){
    event.preventDefault();
    client.publish('rfid_cancela', 'CANCELAR', { qos, retain:true }, (error) => {
        if (error) console.error(error);
        console.log(`Solicitação de cancelamento executada`);
    })
    client.publish('cad_novo', '', { qos,  retain:true}, (error) => {
        if (error) console.error(error);
    })
    client.publish('rfid_cancelado', "OK", { qos,  retain:true}, (error) => {
        if (error) console.error(error);
        console.log(`Cancelamento concluído`);
    })
    console.log(`Cancelamento publicado`);
}

// https://github.com/mqttjs/MQTT.js#event-message
client.on('message', (topic, payload, packet) => {
    if (packet.retain) return;  // Ignore retained messages
    //console.log('Received Message: ' + payload.toString() + '\nOn topic: ' + topic);
    if (topic == "cad_aguardando" && payload.toString() == "OK") {
        icon.innerHTML = '<img id="rfid" src="/rfid.svg">';
        document.getElementById("btn-cancel").style="display: inline";
        MQTTStatusHTML.innerHTML = "Dispositivo IoT pronto para gravação.<br>Posicione a tag sobre o leitor.";
    } else if (topic == "cad_gravado" && payload.toString() == "OK") {
        icon.innerHTML = "<div id='success'>✓</div>";
        MQTTStatusHTML.innerHTML = "Gravação concluída. Retire a tag do leitor.";
        document.getElementById("btn-cancel").style="display: none";
        document.body.classList.remove("waiting");
    } else if (topic == "rfid_cancelado" && payload.toString() == "OK") {
        icon.innerHTML = "<div id='fail'>✕</div>";
        document.getElementById("btn-cancel").style="display: none";
        MQTTStatusHTML.innerHTML = "Gravação cancelada.";
        client.end();
        document.body.classList.remove("waiting");
    }
})

</script>
</div>

</body>
</html>