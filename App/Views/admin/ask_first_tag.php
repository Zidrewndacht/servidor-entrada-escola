<div class="modal-status">
    <h1>Cadastrar novo aluno</h1>
    <p>Aluno <strong><?= esc($Aluno) ?></strong> criado com sucesso
    <p>Certifique-se de gravar uma nova tag RFID para o novo aluno:</p>
</div>
<form action="#">
    <button id="ask-tag-btn" onclick="firstTag(event,'<?=esc($Matr)?>')">Gravar nova tag</button>
</form>
