# Guia de Trabalho do Projeto

Este documento define como trabalhar no projeto, onde cada tipo de código deve ser colocado e como a equipa deve colaborar.

## Objetivo

O projeto utiliza PHP puro e uma arquitetura dividida por responsabilidades. Cada camada deve fazer apenas o seu trabalho. Esta separação facilita a manutenção, os testes e a integração entre os membros da equipa.

## Estrutura do projeto

```text
backend/
├── config/
├── database/
├── public/
├── routes/
└── src/
    ├── Controllers/
    ├── Core/
    ├── Middlewares/
    ├── Models/
    ├── Repositories/
    └── Services/
```

### `config/`

Contém a configuração da aplicação e da base de dados.

- `env.php`: carrega as variáveis do ficheiro `.env`.
- `database.php`: cria e fornece a ligação PDO.

Não colocar regras de negócio nesta pasta.

### `database/`

Contém o schema e os scripts SQL da base de dados. Alterações nas tabelas devem ser feitas aqui e comunicadas à equipa antes de alterar Models ou Repositories.

### `public/`

Contém o ponto de entrada da aplicação. O ficheiro `public/index.php` carrega as dependências, configura a aplicação e encaminha o pedido para o Router.

Não colocar regras de negócio no front controller.

### `routes/`

Define os endpoints da API e liga cada método HTTP a um Controller.

As rotas não devem consultar a base de dados nem conter validações de negócio. Elas apenas devem registar o caminho, o método, o middleware necessário e o Controller responsável.

### `src/Core/`

Contém componentes comuns da aplicação, como:

- `Request`: leitura do método, caminho, headers e body.
- `Response`: criação e envio de respostas JSON.
- `Router`: registo e encaminhamento das rotas.

Alterações nesta pasta podem afetar toda a API e devem ser feitas com cuidado.

### `src/Controllers/`

Recebe os pedidos HTTP e devolve respostas HTTP.

Responsabilidades:

- Ler dados do `Request`.
- Chamar o Service correto.
- Converter o resultado em `Response`.
- Mapear erros para códigos HTTP.

Controllers não devem conter SQL nem regras complexas de negócio.

### `src/Middlewares/`

Contém verificações que acontecem antes ou depois de um Controller, como autenticação e autorização.

Um middleware pode interromper o pedido e devolver `401` ou `403`, ou pode chamar o próximo handler quando a verificação for bem-sucedida.

### `src/Models/`

Representa as entidades da aplicação e os seus dados. Os Models não devem abrir ligações à base de dados nem decidir regras de negócio.

### `src/Repositories/`

É a camada responsável pela persistência e consulta de dados.

Responsabilidades:

- Receber a ligação PDO.
- Executar SQL com prepared statements.
- Converter os resultados para Models ou arrays.
- Isolar a aplicação dos detalhes da base de dados.

Repositories não devem decidir se uma operação é permitida pelo negócio. Essa decisão pertence aos Services.

### `src/Services/`

É a camada onde ficam os casos de uso e as regras de negócio.

Responsabilidades:

- Coordenar Repositories e outros Services.
- Aplicar validações e regras do domínio.
- Controlar o fluxo de uma operação.
- Devolver resultados ao Controller.

Services não devem enviar headers, ler `$_POST` ou construir respostas HTTP.

## Fluxo de uma requisição

O fluxo esperado é:

```text
Request
  -> Router
  -> Middleware, quando necessário
  -> Controller
  -> Service
  -> Repository
  -> PDO / Base de dados
  -> Repository
  -> Service
  -> Controller
  -> Response
```

Cada camada deve comunicar com a camada apropriada. Um Controller não deve saltar diretamente para o PDO e um Repository não deve chamar um Controller.

## Como criar uma rota pública

Uma rota pública não precisa de autenticação. Registe-a no ficheiro de rotas e encaminhe-a para um Controller.

```php
$router->add('GET', '/api/imoveis', [$imovelController, 'index']);
$router->add('POST', '/api/imoveis', [$imovelController, 'store']);
```

O Controller deve receber o `Request`, chamar o `ImovelService` e devolver um `Response`.

Rotas públicas podem ser usadas para operações que não exigem um utilizador autenticado, como listagens públicas de imóveis disponíveis.

## Como criar uma rota com middleware

Rotas protegidas devem passar pelo `AuthMiddleware` antes de chegar ao Controller.

```php
$router->add('GET', '/api/auth/me', function (Request $request) use ($authMiddleware, $authController): Response {
    return $authMiddleware->handle(
        $request,
        fn (Request $request, array $auth): Response => $authController->me($request, $auth)
    );
});
```

O middleware deve:

1. Ler o token do header `Authorization`.
2. Validar o token.
3. Devolver `401` se o pedido não estiver autenticado.
4. Encaminhar o pedido ao Controller quando o token for válido.

Exemplo de chamada:

```http
GET /api/auth/me
Authorization: Bearer TOKEN_DO_UTILIZADOR
```

Nunca se deve remover um middleware apenas para fazer um endpoint funcionar. Se a rota precisa de autenticação, o middleware deve permanecer ativo.

## Criação de um novo recurso

Ao adicionar um recurso novo, seguir esta ordem:

1. Confirmar ou alterar o schema em `backend/database/`.
2. Criar ou atualizar o Model em `backend/src/Models/`.
3. Criar os métodos de persistência no Repository.
4. Implementar o caso de uso no Service.
5. Implementar os métodos HTTP no Controller.
6. Registar as rotas públicas ou protegidas.
7. Testar respostas de sucesso, validação, autenticação e erros.
8. Atualizar a documentação se o endpoint for público.

## Regras de responsabilidade

- Configuração fica em `config/`.
- SQL fica em `Repositories/`.
- Regras de negócio ficam em `Services/`.
- HTTP fica em `Controllers/`, `Core/` e `routes/`.
- Autenticação e autorização ficam em `Middlewares/`.
- Dados das entidades ficam em `Models/`.

Não duplicar a mesma regra em várias camadas. Se uma regra pertence ao negócio, deve existir no Service correspondente.

## Trabalho entre programadores

Cada programador deve respeitar a responsabilidade dos outros membros da equipa.

- Não alterar ficheiros ou lógica atribuídos a outra pessoa sem combinar previamente.
- Não reescrever uma implementação de outra camada para resolver um problema local.
- Não mover código de pasta apenas por preferência pessoal.
- Não alterar o schema, contratos públicos ou autenticação sem informar a equipa.
- Manter as alterações focadas na tarefa atribuída.

Se for encontrado um erro na lógica implementada por outra pessoa:

1. Reproduzir o erro, se possível.
2. Registar o ficheiro, o método, o comportamento esperado e o comportamento atual.
3. Informar diretamente o responsável e a equipa.
4. Anexar logs, pedido HTTP ou passos para reproduzir.
5. Não corrigir, apagar ou reestruturar a lógica da outra pessoa sem autorização.

Exceção: problemas críticos de segurança ou bloqueios que impeçam o projeto de funcionar devem ser comunicados imediatamente ao responsável e ao coordenador antes de qualquer alteração.

## Convenções de código

- Usar `declare(strict_types=1);` nos ficheiros PHP.
- Usar `PDO` com prepared statements para valores externos.
- Não guardar senhas em texto simples.
- Não expor a coluna `senha` nas respostas da API.
- Usar códigos HTTP adequados.
- Validar entradas no Service.
- Manter nomes de métodos e propriedades consistentes com os Models.
- Evitar comentários que apenas repetem o código; escrever documentação fora do código quando ela for necessária para a equipa.

## Checklist antes de entregar

- A alteração está na pasta correta?
- A responsabilidade está na camada correta?
- A rota precisa de middleware?
- O Controller chama um Service em vez de conter SQL?
- O Service contém as regras de negócio necessárias?
- O Repository usa PDO e prepared statements?
- Os dados sensíveis estão protegidos?
- O endpoint foi testado com sucesso e com erro?
- Os ficheiros PHP não apresentam erros de sintaxe?
- A alteração foi comunicada aos responsáveis pelas partes relacionadas?
