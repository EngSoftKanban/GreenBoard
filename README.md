# GreenBoard

## Descrição

GreenBoard é o projeto de um de quadro Kanban simples e mínimo para de fato aumentar sua produtividade.

#### [Landing Page](https://engsoftkanban.github.io)

#### [Vídeo](https://github.com/EngSoftKanban/GreenBoard/blob/main/V%C3%ADdeo%20de%20Demonstra%C3%A7%C3%A3o.mp4)

#### [Versão atual](https://github.com/EngSoftKanban/GreenBoard/releases/latest)

#### [Apresentação final](https://github.com/EngSoftKanban/GreenBoard/blob/main/GREENBOARD.pdf)

## Equipe

| Nome | Github |
| ------------------------- | ------------------------------------------ |
| José Lucas Carvalho Silva | [@lalisalix](https://github.com/lalisalix) |
| Iago Arruda Faria | [@fariaiago](https://github.com/fariaiago) |
| Daniel Vinicius da Silva | [@DanielVinicius00](https://github.com/DanielVinicius00) |
| Ana Júlia Campos Vieira | [@Ana4Julia](https://github.com/Ana4Julia) |
| João Victor da Mota Sousa | [@JaumMota](https://github.com/JaumMota) |

## Institucional

| Informação | Descrição |
| ------------ | ------------------------------------------------- |
| Universidade | Universidade Federal do Tocantins - Campus Palmas |
| Curso | Ciência da Computação |
| Disciplina | Engenharia de Software |
| Semestre | 2024/2 |
| Professor | Edeilson Milhomem da Silva |

## Organização
* A organização do repositório foi baseada na pesquisa apresentada [aqui](https://github.com/php-pds/skeleton_research);
* [User Stories](/USER%20STORIES.md);
* [Valor das iterações](/SPRINTS.md);
* [Trello do projeto](https://trello.com/b/K7ykKPwI/kanban).

## Instalação e uso

1. Instalação e setup do XAMPP

É necessário instalar o [XAMPP 8.2.12](https://www.apachefriends.org/pt_br/index.html) primeiramente. Após, é necessário mudar o valor da variável DocumentRoot do Apache para o caminho para a pasta `public` dentro da pasta do projeto, além disso é necessário adicionar o conteúdo abaixo ao `httpd.conf`:
```
Alias "/resources/" "/caminho/à/pasta/do/projeto/resources/"
<Directory "/caminho/à/pasta/do/projeto/resources/">
    Options Indexes FollowSymLinks ExecCGI Includes
    AllowOverride All
    Require all granted
</Directory>

<Directory "/caminho/à/pasta/do/projeto/resources/prototipo">
    Require all denied
</Directory>

Alias "/src/" "/caminho/à/pasta/do/projeto/src/"
<Directory "/caminho/à/pasta/do/projeto/src/">
    Options Indexes FollowSymLinks ExecCGI Includes
    AllowOverride All
    Require all granted
</Directory>
```
Ademais, é necessario adicionar o caminho à pasta do projeto ao `include_path` do `php.ini`:
![Captura de tela_2024-11-24_17-50-06](https://github.com/user-attachments/assets/3f5d2a2e-bae9-44d2-90d6-524629487c55)

Quanto ao banco de dados, crie um banco de dados de nome 'GreenBoard' e importe as configurações expostas em `GreenTest.sql`, os dados padrões criados pela importação podem ser apagados sem problemas.

2. Instalação e setup do Composer

O Composer é um gerenciador de pacotes, que serve para ajudar no gerenciamento de dependências de um projeto. O Composer pode ser baixado [aqui](https://getcomposer.org/), após instalá-lo, na pasta do projeto faça:
```
composer install
```
Esse comando fará o Composer baixar e instalar todas as dependências especificadas pelo projeto, após isso o projeto estará pronto para uso.
