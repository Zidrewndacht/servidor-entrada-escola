<div class="modal-status">
    <div id="session-info-field">
        <!-- Usando echo em vez de esc porque precisa de formatação HTML -->
        <div id="success-info"><?= $info ?></div>
    </div>
</div>
<form action="#" method="GET">
    <input type="submit" name="submit" value="OK" onclick="animateModalSmallClose()">
</form>
