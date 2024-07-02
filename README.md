Aplicação para o servidor Web do monitoramento de acesso de alunos do CEDUP Dario Geraldo Salles.

Em desenvolvimento usando PHP com o framework CodeIgniter 4. É necessário configurar o banco de dados e hostname no arquivo .env, além de configurar o broker MQTT na interface de administrador em seguida.

Antes do primeiro uso, necessário criar o banco de dados sistema, criar as tabelas necessáras e as pré-preencher. O arquivo prefill.sql executa esta tarefa, criando uma conta administrativa admin@admin.com com senha admin1234, e alguns alunos e responsáveis fictícios.

O arquivo emqx.conf configura o broker MQTT EMQX para uso com IPv6. Pode ser inserido no local correspondente (tipicamente /etc/) na sua instalação do EMQX.

## License

Para licença dos componentes utilizados, verifique **/public/licenses.html**

Shield: [![CC BY-NC 4.0][cc-by-nc-shield]][cc-by-nc]

This work is licensed under a
[Creative Commons Attribution-NonCommercial 4.0 International License][cc-by-nc].

[![CC BY-NC 4.0][cc-by-nc-image]][cc-by-nc]

[cc-by-nc]: https://creativecommons.org/licenses/by-nc/4.0/
[cc-by-nc-image]: https://licensebuttons.net/l/by-nc/4.0/88x31.png
[cc-by-nc-shield]: https://img.shields.io/badge/License-CC%20BY--NC%204.0-lightgrey.svg