# Notificações de publicações

No cadastro de jovens egressos, a opção de receber notificações é facultativa e começa desmarcada. Cadastros anteriores permanecem com a preferência desativada.

## Atualização do banco existente

Com o MySQL iniciado, execute uma única vez, antes de usar o novo cadastro:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root apoio -e "SOURCE C:/xampp/htdocs/ProjetoApoio_260814/backend/migrations/002_notificacoes_posts.sql"
```

Na hospedagem, execute o conteúdo dessa migração no banco da aplicação. Para uma instalação nova, o campo já consta na definição de `egressos` em `backend/apoio_dump.sql`. Não importe o dump para atualizar um banco existente: ele remove os dados.

## Envio

O projeto reutiliza o SMTP descrito em `CONFIGURACAO_EMAIL.md`. Após salvar uma publicação, envia uma mensagem individual para cada egresso com `receber_notificacoes = 1`. O e-mail contém instituição, título, descrição e o link da oportunidade, quando for HTTP(S).

Falhas de envio são registradas no log do PHP com os identificadores do post e do egresso. Não desfazem o post nem impedem a tentativa para os demais destinatários. Não há reenvio automático.

Esta versão envia durante a requisição e é voltada para uma base pequena: a confirmação da publicação aguarda os envios. Antes de aumentar o volume, mover o processamento para uma fila com tarefa agendada e controle de tentativas. A preferência é definida no cadastro; ainda não existe uma tela para alterá-la posteriormente.

## Verificação manual

1. Cadastre um jovem com a opção marcada e outro sem marcar, usando e-mails de teste sob seu controle.
2. Confira que as preferências no banco são, respectivamente, 1 e 0.
3. Publique com uma conta de instituição. Apenas o primeiro endereço deve receber a mensagem.
4. Em ambiente de teste, use SMTP indisponível e confirme que o post continua salvo e a falha aparece no log.

Não execute esse teste com destinatários reais sem intenção de notificá-los.
