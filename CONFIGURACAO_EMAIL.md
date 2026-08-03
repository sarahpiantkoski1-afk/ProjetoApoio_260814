# Configuração de e-mail do Apoio+

O projeto usa PHPMailer via SMTP, carregado diretamente da pasta local. As credenciais nunca devem ser salvas no Git.

## Instalação

Baixe uma versão oficial da biblioteca em:

`https://github.com/PHPMailer/PHPMailer/releases`

Copie estes três arquivos da pasta `src` do pacote baixado:

- `Exception.php`
- `PHPMailer.php`
- `SMTP.php`

para:

`backend/vendor/phpmailer/src/`

O projeto não depende do Composer nem de `vendor/autoload.php`.

## XAMPP/localhost

1. Abra `backend/config/mail.local.php`.
2. Preencha `MAIL_PASSWORD` com uma Senha de App do Google, nunca com a senha normal da conta.
3. Mantenha Gmail com porta `587` e criptografia `tls`.
4. Ajuste `APP_URL` se a pasta ou URL local do projeto for diferente.

O arquivo `mail.local.php` já está ignorado pelo Git.

## Produção

Defina as seguintes variáveis no painel da hospedagem ou no ambiente do PHP:

```text
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=apoiomais.oficial@gmail.com
MAIL_PASSWORD=SENHA_DE_APP_DO_GOOGLE
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=apoiomais.oficial@gmail.com
MAIL_FROM_NAME=Apoio+
APP_URL=https://seu-dominio.com/caminho/frontend/telas
```

Para o Gmail, ative a verificação em duas etapas na conta Google e gere uma Senha de App. A hospedagem precisa permitir conexão SMTP de saída para `smtp.gmail.com:587`.

## Banco de dados

Execute uma vez:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root apoio -e "SOURCE C:/xampp/htdocs/ProjetoApoiofinal/ProjetoApoio/backend/migrations/001_password_reset_tokens.sql"
```

Em produção, importe o mesmo arquivo pelo painel do banco ou por uma ferramenta de migração.

Tokens são armazenados somente como SHA-256, expiram após uma hora e deixam de funcionar depois da primeira redefinição.
