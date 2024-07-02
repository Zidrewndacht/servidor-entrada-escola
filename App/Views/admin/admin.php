<!DOCTYPE html>
<html lang="pt-BR" style='background-color: #f5f5f5;'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT entrada escola - Admin</title>     

    <?php /* Estilo crítico, para splash de inicialização. Elementos críticos nessa página usam estilo em linha.*/?>
    <style>
        @import url("/normalize.css"); /* precisa vir aqui para minimizar layout shifts*/
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>

    <script src=<?= esc(base_url('/mqtt.5.6.1.min.js'))?> defer onload="MQTTclientInit()"></script> <?php /* Cliente MQTT, usado para atualização instantânea de eventos:*/?>
    <script src=<?= esc(base_url('/choices.min.js'))?> async></script> <?php /* Choices, para associação de:*/?>                             
    <script src=<?= esc(base_url('/admin.js'))?> defer ></script>
   
    <link rel="stylesheet" href="<?= esc(base_url('/style_admin.css'))?>">
</head>
<body style="cursor: progress">

<div id="loading-placeholder" 
    style="display: grid;  grid-template-rows: 1fr 400px 1fr; place-items: center; 
        position: fixed;   top: 0; left: 0; right: 0; bottom: 0; 
        width: 100vw; height: 100vh;">
    <span class="loading-info" style="font-family: system-ui, sans-serif; font-weight: 400; font-size: 16pt; animation: fadeIn 0.5s ease-in;">Iniciando painel administrativo - Aguarde</span>

    <div class="spinner" style="animation: spin 5s linear infinite, fadeIn 0.5s ease-in;">
        <svg width="400px" height="400px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="hsl(220, 35%, 33%)" class="bi bi-gear-wide">
            <path d="M8.932.727c-.243-.97-1.62-.97-1.864 0l-.071.286a.96.96 0 0 1-1.622.434l-.205-.211c-.695-.719-1.888-.03-1.613.931l.08.284a.96.96 0 0 1-1.186 1.187l-.284-.081c-.96-.275-1.65.918-.931 1.613l.211.205a.96.96 0 0 1-.434 1.622l-.286.071c-.97.243-.97 1.62 0 1.864l.286.071a.96.96 0 0 1 .434 1.622l-.211.205c-.719.695-.03 1.888.931 1.613l.284-.08a.96.96 0 0 1 1.187 1.187l-.081.283c-.275.96.918 1.65 1.613.931l.205-.211a.96.96 0 0 1 1.622.434l.071.286c.243.97 1.62.97 1.864 0l.071-.286a.96.96 0 0 1 1.622-.434l.205.211c.695.719 1.888.03 1.613-.931l-.08-.284a.96.96 0 0 1 1.187-1.187l.283.081c.96.275 1.65-.918.931-1.613l-.211-.205a.96.96 0 0 1 .434-1.622l.286-.071c.97-.243.97-1.62 0-1.864l-.286-.071a.96.96 0 0 1-.434-1.622l.211-.205c.719-.695.03-1.888-.931-1.613l-.284.08a.96.96 0 0 1-1.187-1.186l.081-.284c.275-.96-.918-1.65-1.613-.931l-.205.211a.96.96 0 0 1-1.622-.434L8.932.727zM8 12.997a4.998 4.998 0 1 1 0-9.995 4.998 4.998 0 0 1 0 9.996z"/>
        </svg>
    </div>
    <p style="font-family: system-ui, sans-serif; font-weight: 400; font-size: 11pt; text-align: center">
        &copy; 2024 Luis Alfredo. Este trabalho está licenciado com uma licença<br> 
        <a href="<?= esc(base_url('/licenses.html'))?>" style="text-decoration: none; color: hsl(221, 35%, 33%);">Creative Commons Attribution-NonCommercial 4.0 International</a>.<?php /* Alterar para página detalhando todas as licenças envolvidas.*/?>
    </p>    
</div>

<main id="admin-wrapper"  style="opacity:0;">
    <img src="<?= esc(base_url('/logo.svg'))?>" id="admin-logo" alt="logo" style="width: 72px; height: 72px;">    
    <h1 id="admin-title">Painel administrativo — IoT Entrada Escola</h1> 
    <a class="modal-small" id="cad-csv-btn"     href="/admin/cad_csv" title="Clique para efetuar cadastros em lote a partir de arquivos CSV."> Cadastrar em lote  </a> 
    <a class="modal-small" id="admin-setup-btn" href="/admin/setup_admin" title="Clique para configurar as credenciais do administrador do sistema."> Configurações do administrador  </a> 
    <a class="modal-small" id="mqtt-setup-btn"  href="/admin/setup_mqtt" title="Clique para ajustar configurações de conexão MQTT.">   Configurações MQTT              </a>

    <a id="logout-btn" href="logout" title="Clique para desconectar-se do painel administrativo."> Sair  </a> <?php /* Não pode ser modal-small pois não abre em modal! */?>

    <?php /* Tabelas acessadas via AJAX em vez de puro SSR, para atualização dinâmica: 
         atualizado também após evento publicado via MQTT:*/?>
         
    <div class="outer-table-wrapper" id="tabela-alunos-wrapper-outer">
        <div class="table-wrapper" id="tabela-alunos-wrapper"></div>
    </div>
    
    <div class="outer-table-wrapper" id="tabela-resp-wrapper-outer">
        <div class="table-wrapper" id="tabela-resp-wrapper"></div>
    </div>
    
    <div class="outer-table-wrapper" id="tabela-evt-wrapper-outer">
        <div class="table-wrapper" id="tabela-evt-wrapper"></div>
    </div>
    
    <a id="evt-link-btn" href="javascript:scrollToEvt()" style="display:none"> Ver eventos recentes </a>
    <footer> <?php /* Duplicado para facilitar splash: */?>
        <p>&copy; 2024 Luis Alfredo. Este trabalho está licenciado com uma licença<br> 
            <a class="modal-small license-link" href="<?= esc(base_url('/licenses-modal.html'))?>" target="_blank" rel="noopener noreferrer">
                Creative Commons Attribution-NonCommercial 4.0 International</a>.<?php /* Alterar para página detalhando todas as licenças envolvidas.*/?>
        </p>    
    </footer>
</main>

<?php /* Modal*/?>
<div id="modal-wrapper-large" class="modal-wrapper" style="opacity:0;"> <?php /* oculto por padrão, mesmo se não carregar CSS*/?>
    <div id="modal-large" class="modal" > 
        <div class="modal-content" id="modal-large-content" ></div>
        <button id="modal-large-close" title="Fechar">✕</button>
    </div>
</div>

<div id="modal-wrapper-small" class="modal-wrapper" style="opacity:0;"> <?php /* oculto por padrão, mesmo se não carregar CSS*/?>
    <div id="modal-small" class="modal">
        <div class="modal-content" id="modal-small-content"></div>
        <button id="modal-small-close" title="Fechar">✕</button>
    </div>
</div>

<script>
    
function MQTTclientInit(){

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
    
    console.log('<br>Conectando cliente JS ao broker MQTT via WebSocket...')
    const client = mqtt.connect(connectUrl, options)
    
    client.on('error', (err) => {  // https://github.com/mqttjs/MQTT.js#event-error
        console.log('<br>Erro ao conectar: ', err);
        client.end()
    })
    client.on('reconnect', () => { // https://github.com/mqttjs/MQTT.js#event-reconnect
        console.log('<br>Reconectando...');
    })
    
    const qos = 2;
    client.on('connect', () => {    // https://github.com/mqttjs/MQTT.js#event-connect
        console.log('<br>Cliente conectado. Usando ID ' + clientId);
        client.subscribe(['rfid_lido/#',], { qos }, (error) => {
            if (error) {
                console.log('<br>Erro de subscribe: ', error);
                return;
            }
            console.log(`<br>Subscribe em rfid_lido executado`);
        })
    })
    
    // https://github.com/mqttjs/MQTT.js#event-message
    client.on('message', (topic, payload, packet) => {
        console.log('Recebida mensagem: ' + payload.toString() + '\nTópico: ' + topic);
        if (topic.startsWith('rfid_lido/')) {
            fetchEvt(); 
            fetchAlunos(); 
            console.log(`Atualizando tabelas de alunos e eventos`);
        }
    })    
}

</script>
</body>