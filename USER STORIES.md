# User Stories

## [US0 - Criar Banco de Dados](https://trello.com/c/ikwbQ1Yz)

### Descrição:
Como desenvolvedor, quero criar o banco de dados necessário para armazenar as informações do sistema, de forma a garantir que ele suporte todas as funcionalidades descritas nas histórias de usuário.

### Regas de negócio:
* O banco de dados deve ser estruturado para suportar todas as entidades e relacionamentos necessários para o funcionamento do sistema.
* As tabelas devem possuir índices e restrições para garantir integridade e desempenho.
Devem ser seguidas boas práticas de normalização, sem prejuízo ao desempenho do sistema.

### Observações:
* Deve-se garantir compatibilidade com o ambiente de produção.

### Dados:
* Estrutura do banco de dados: tabelas, colunas, tipos de dados, chaves primárias e estrangeiras.

## [US1 - Adicionar cartão a uma lista ou uma nova lista](https://trello.com/c/MFLkz67V)

### Descrição:
Como um usuário, eu quero poder adicionar um cartão a uma lista ou criar uma nova lista.

### Regras de negócio:
* Seja possível criar novos cartões e listas.

### Observações:
N/A

### Dados:
* Novo cartão ou nova lista.

### Protótipo:
#### Adicionar novo cartão:
![Adicionar Cartão](./resources/prototipo/Kanban%20-%20Ad%20cartão.png)
#### Adicionar nova lista:
![Adicionar Lista](./resources/prototipo/Kanban%20-%20Ad%20lista.png)

## [US2 - Editar cartão numa lista ou título de uma lista](https://trello.com/c/cX6UPmNr)

### Descrição:
Como um usuário, eu quero poder editar os detalhes de um cartão, para isso vou clicar no botão de editar que apresentará uma entrada de texto que me permite modificar o conteúdo do cartão.

### Regras de negócio:
* Seja possível editar novos cartões e listas.

### Observações:
N/A

### Dados:
* Conteúdo novo do cartão/Título novo da lista.

### Protótipo
#### Editar cartão:
![Editar cartão](./resources/prototipo/Kanban%20-%20Editar%20cartão%201.png)
#### Editar lista:
![Editar lista](./resources/prototipo/Kanban%20-%20Editar%20lista1.png)

## [US3 - Remover cartão de uma lista ou uma lista](https://trello.com/c/JmFsSOG5)

### Descrição:
Como usuário, eu quero poder remover um cartão de uma lista ou uma lista, para isso vou clicar no botão de editar e depois no botão de remover o item.

### Regras de negócio:
* Seja possível remover cartões e listas existentes.

### Observações:
N/A

### Dados:
* Cartão/Lista a ser removido.

### Protótipo
#### Remover cartão:
![Remover cartão](./resources/prototipo/Kanban%20-%20Editar%20cartão%201.png)
#### Remover lista:
![Remover lista](./resources/prototipo/Kanban%20-%20Editar%20lista1.png)

## [US4 - Carregar cartões e listas salvos no banco de dados](https://trello.com/c/IrWNyFLg)

### Descrição:
Como usuário, eu quero poder mudar a posição relativa de cartões e listas, para isso clicarei num item e o arrasterei para o nova posição.

### Regras de negócio:
* Seja possível mudar a posição de cartões e listas.

### Observações:
* O usuário usará do mecanismo drag-n-drop para fazer isso.

### Dados:
* Cartão: nova lista e posição;
* Lista: nova posição.

## [US5 - Mudar cartão ou lista de posição com drag-n-drop](https://trello.com/c/ZqDBpkzB)

### Descrição:
Como usuário, quero poder mover cartões para outras listas ou para outras posições e listas para outras lugares, usando drag-n-drop.

### Regras de negócio:RF6 - Permitir login de um usuárioRF6 - Permitir login de um usuário
* Seja possível mover cartões e listas apenas arrastando e soltando para o lugar desejado.

### Observações:
N/A

### Dados:
* Entradas do mouse/touchpad.https://trello.com/c/tlzdlKBZ

## [US6 - Permitir login de um usuário](https://trello.com/c/tlzdlKBZ)

### Descrição:
Como usuário, eu quero poder me autenticar e usar os recursos da plataforma, para isso inserirei minhas informações cadastrais em um formulário.

### Regras de negócio:
* Seja possível entrar como um usuário em especifíco.

### Observações:
N/A

### Dados:
* E-mail e senha.

### Protótipo
#### Login:
![Login](./resources/prototipo/Login.png)

## [US6 - Cadastrar um usuário](https://trello.com/c/IYoymYTq)

### Descrição:
Como usuário, quero poder me registrar na plataforma para usar seus recursos, para isso escreverei meus dados num formulário.

### Regras de negócio:
* Seja possível cadastrar um usuário na plataforma.

### Observações:
N/A

### Dados:
* Nome do usuário, e-mail e senha.

### Protótipo
#### Cadastro:
![Cadastro](./resources/prototipo/Cadastro.png)

## [US8 - Recuperar a senha de um usuário](https://trello.com/c/bpYUvuIT)

### Descrição:
Como usuário, quero poder recuperar minha senha caso a tenha esquecido, para isso colocarei meu e-mail num formulário e receberei um e-mail que me permite ir na página de criar nova senha.

### Regras de negócio:
* Seja possível ao usuário mudar de senha caso necessário.

### Observações:
N/A

### Dados:
* E-mail.

### Protótipo
#### Recuperação de senha:
![Recuperação de senha](./resources/prototipo/Recuperar%20senha.png)

## [US9 - Convidar membros a um quadro.](https://trello.com/c/yie20E3x)

### Descrição:
Como usuário, eu quero poder convidar outros membros da equipe ao quadro, para isso digitarei seus nomes ou e-mails num formulário.

### Regras de negócio:
* Seja possível adicionar novos membros ao quadro.

### Observações:
N/A

### Dados:
* E-mail ou nome do usuário a ser adicionado.

### Protótipo
#### Convidar membros a um quadro:
![Convidar membros a um quadro](./resources/prototipo/Kanban%20-%20Compartilhar%202.png)

## [[US10 - Criar ou remover quadros](https://trello.com/c/VaOzGoA4)

### Descrição:
Como usuário, quero acessar uma tela que me mostre todos os meus quadros e que me permita adicionar ou remover um quadro para organizar minhas atividades de forma eficiente.

### Regras de negócio:
* O usuário deve ser capaz de criar novos quadros.
* O usuário deve ser capaz de remover quadros existentes.
* Os quadros criados devem ser vinculados ao usuário que os criou.
* Apenas o dono do quadro pode removê-lo.

### Critérios de aceitação:
* O sistema deve exibir uma lista de todos os quadros vinculados ao usuário.
* O botão "Criar Quadro" deve estar disponível na interface para que o usuário adicione um novo quadro.
* Ao remover um quadro, o sistema deve solicitar uma confirmação antes de excluí-lo permanentemente.
* O sistema deve garantir que, ao remover um quadro, todas as informações associadas a ele sejam excluídas corretamente.

### Observações:
N/A

### Dados:
* Quadro removido ou criado.

### Protótipo
#### Manipulação de quadros:
![Quadros](./resources/prototipo/Quadros.png)

## [US10 - Reconfigurar Apache](https://trello.com/c/RnnbSaO5)

### Descrição:
Como hosteador do serviço, quero que as regras de configuração do servidor Apaches sejam seguras e sólidas, tal que o hosteamento fique mais fácil.

### Regras de negócio:
* Seja possível criar ou remover quadros.

### Observações:
N/A

### Dados:
N/A

## [US12 - Refatorar MVC - Cartão e Lista](https://trello.com/c/0xsmbtwo)

### Descrição:
Como desenvolvedor, eu quero refatorar o código das listas e cartões para seguir os princípios MVC para que a aplicação seja mais organizada, escalável e fácil de manter.

### Regras de negócio:
* Seguir o padrão MVC para garantir organização, escalabilidade e facilidade de manutenção.

### Observações:
N/A

### Dados:
N/A

### Protótipo
#### Manipulação de quadros:
![Kanban Neutro](./resources/prototipo/Kanban%20-%20Neutro.png)

## [US13 - Refatorar MVC - Quadro](https://trello.com/c/Edu9mqwk)

### Descrição:
Como desenvolvedor, eu quero refatorar o código dos Quadros para seguir os princípios MVC para que a aplicação seja mais organizada, escalável e fácil de manter.

### Regras de negócio:
* Seguir o padrão MVC para garantir organização, escalabilidade e facilidade de manutenção.

### Observações:
N/A

### Dados:
N/A

### Protótipo
#### Manipulação de quadros:
![Kanban Neutro](./resources/prototipo/Quadros.png)

## [US14 - Criar tela de perfil](https://trello.com/c/8YJiBBpr)

### Descrição:
Como um usuário do GreenBoard, quero ter acesso a uma tela de perfil onde poderei visualizar e editar minhas informações pessoais, como nome, e-mail e foto de perfil. Isso me permitirá atualizar meus dados sempre que necessário.

### Regras de negócio:
* O usuário poderá visualizar e editar sua foto de perfil, nome completo, apelido, E-amil e data de nascimento.

### Observações:
N/A

### Dados:
* Nome e E-maiL.
  
### Protótipo
#### Editar Perfil:
![Editar Perfil](./resources/prototipo/Perfil.png)

## [US15 - Refatorar MVC - Usuário](https://trello.com/c/jujWyZrT)

### Descrição:
Como desenvolvedor, eu quero refatorar os usuários seguindo os princípios MVC para que a aplicação seja mais organizada, entendível e fácil de manter.

### Regras de negócio:
* Seguir o padrão MVC para garantir organização e facilidade no entendimento e na manutenção.

### Observações:
N/A

### Dados:
N/A

## [US16 - Adicionar usuário a um cartão](https://trello.com/c/KYr2K54J)

### Descrição:
Como usuário, quero poder me adicionar a um cartão, para isso clicarei num botão que me adicionará ao cartão, mostrando meu icone nele.

### Regras de negócio:
* Seja possível adicionar um usuário a um cartão.

### Observações:
N/A

### Dados:
* Quais e quantos usuários estão em cada cartão.

## [US17 - Editar remotamente cartões e/ou listas](https://trello.com/c/YXE939T2)

### Descrição:
Como tech lead, quero poder enviar mudanças de cartões ou listas remotamente para o serviço através de uma token.

### Regras de negócio:
* Seja possível editar um cartão ou lista remotamente.

### Observações:
N/A

### Dados:
* Quais operações e em quais cartões/listas;
* Endpoints para recebimento de dados.

## [US18 - Receber conteúdo via webhook](https://trello.com/c/zp3OJLbY)

### Descrição:
Como usuário, quero poder adicionar um webhook ao github e que cartões sejam criados baseados no conteúdo dele.

### Regras de negócio:
* Seja possível adicionar um webhook a uma lista, tal que cartões sejam criados quando o hook é ativado.

### Observações:
N/A

### Dados:
* Quais listas tem webhook;
* Endpoint para receber webhook;

## [US19 - Entrar via Google](https://trello.com/c/JLkKQild)

### Descrição:
Como usuário, quero poder me conectar ao serviço através da minha conta do Google.

### Regras de negócio:
* Seja possível conectar através Google via OAuth.

### Observações:
N/A

### Dados:
* Dados da conta do Google.

## [US20 - Criar página inicial](https://trello.com/c/2hEt2ZQZ)

### Descrição:
Como usuário, quero entrar por uma tela específica ao invés de ser redirecionado ao login.

### Regras de negócio:
* Seja possível acessar a página inicial;

### Observações:
N/A

### Dados:
*N/A

### Protótipo:
#### Página inicial:
![Página inicial](./resources/prototipo/Landing%20Page.png)

## [US21 - Adicionar etiquetas](https://trello.com/c/6qFPD2En)

### Descrição:
Como usuário, quero poder adicionar etiquetas a cartões para organiza-los.

### Regras de negócio:
* Seja possível adicionar uma etiqueta a um cartão.

### Observações:
N/A

### Dados:
* Qual cartão, nome e cor da etiqueta.

### Protótipo:
#### Etiqueta num cartão:
![Etiqueta num cartão](./resources/prototipo/Kanban%20-%20Ad%20cartão.png)

#### Etiqueta no editar cartão:
![Etiqueta no editar cartão](./resources/prototipo/Kanban%20-%20Editar%20cartão%201.png)

#### Editar etiqueta:
![Editar etiqueta](./resources/prototipo/Kanban%20-%20Editar%20etiqueta.png)

## [US22 - Mudar o icone do usuário](https://trello.com/c/Qmd4Lpn6)

### Descrição:
Como usuário, quero poder mudar o meu icone de usuário.

### Regras de negócio:
* Seja possível mudar o icone de usuário.

### Observações:
N/A

### Dados:
* Icone do usuário.

## [US23 - Editar quadros no painel.](https://trello.com/c/znU49ZCI)

### Descrição:
Como usuário, quero poder mudar o nome e cor dos quadros.

### Regras de negócio:
* Seja possível mudar nome e cor de quadros.

### Observações:
N/A

### Dados:
* Novo nome/cor do quadro;

## [US24 - Testes unitários dos Adicionados](https://trello.com/c/8KVju8nJ)

### Descrição:
Como usuário, quero que os adicionados sejam sempre consistentes.

### Regras de negócio:
N/A

### Observações:
N/A

### Dados:
N/A


## [US25 - Testes unitários das Permissões e dos Usuários](https://trello.com/c/iRpbiQMs)

### Descrição:
Como usuário, quero que minhas manipulações envolvendo os usuários e permissões sejam sempre consistentes.

### Regras de negócio:
N/A

### Observações:
N/A

### Dados:
N/A

## [US26 - Teste Unitário dos Cartões](https://trello.com/c/LjyDFPvq)

### Descrição:
Como usuário, quero que minhas manipulações de cartões sejam sempre consistentes.

### Regras de negócio:
N/A

### Observações:
N/A

### Dados:
N/A

## [US27 - Teste Unitário das Listas](https://trello.com/c/MrHoJ0Fy)

### Descrição:
Como usuário, quero que minhas manipulações com listas sejam sempre consistentes.

### Regras de negócio:
N/A

### Observações:
N/A

### Dados:
N/A

## [US28 - Teste Unitário dos Quadros](https://trello.com/c/M0D2o12s)

### Descrição:
Como usuário, quero que minhas manipulações de quadros sejam sempre consistentes.

### Regras de negócio:
N/A

### Observações:
N/A

### Dados:
N/A

## [US29 - Teste Unitário dos Webhooks](https://trello.com/c/d4TQZsa7)

### Descrição:
Como usuário, quero que minhas manipulações com Webhook sejam sempre consistentes.

### Regras de negócio:
N/A

### Observações:
N/A

### Dados:
N/A

## [US30 - Teste Unitário das Etiquetas](https://trello.com/c/3eruMpid)

### Descrição:
Como usuário, quero que minhas manipulações com etiquetas sejam sempre consistentes.

### Regras de negócio:
N/A

### Observações:
N/A

### Dados:
N/A

## [US31 - Consertar a API](https://trello.com/c/AeFEKUDj)

### Descrição:
Como desenvolvedor, quero que a API esteja funcional.

### Regras de negócio:
N/A

### Observações:
N/A

### Dados:
N/A