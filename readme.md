# Laravel Lazy Filters

## Introdução

Bem-vindo ao Laravel Lazy Filters, a solução que simplifica a experiência de Filtragem, Sorteamento, Busca e Paginação de registros  para desenvolvedores que utilizam o poderoso Framework Laravel. Este aplicativo nasceu da necessidade de aprimorar a visualização e interação com dados, proporcionando uma abordagem inovadora para simplificar o processo de filtragem e exibição de registros de maneira eficiente.
## Índice
1. [Introdução](#introdução)
2. [Índice](#indice)
3. [Requisitos](#requisitos)
4. [Instalação](#instalação)
5. [Utilização](#utilização)
6. [Parâmetrização](#parâmetrização)
7. [Filtragem](#searcheable)
8. [Paginação](#paginatable)
9. [Limitação](#limitable)
10. [Sorteamento](#Sorteable)
11. [Exemplo de Query Strings](#exemplo-de-query-strings)
12. [Veja um exemplo de aplicação](#veja-um-exemplo-de-aplicação)

## Requisitos

Necessario PHP 8.0+ e Framework Laravel

## Instalação

#### Para realizar a instalação desta dependência basta executar o seguinte comando:

```shell

composer require moiseskalebe/laravel-lazy-filters

```

## Utilização
#### Para que seja possivel a utilização deste package você precisa ter em mente algumas informações.
O pacote funciona partindo do objeto Request que o Framework Laravel disponibiliza, então veja que os parâmetros precisam ser convêncionados no recebimento da API.

Para que seja possivel realizar as ações como Filtragem, Sorteamento, Busca e Paginação deve-se ter em mente os padrões abaixo: 

## Parâmetrização:
`filter` é utilizado para filtrar os registros,

`sort` é utilizado para ordenar os resultados,

`limit` é utilizado para limitar a quantidade de resultados retornados.

## Searcheable:
Esta trait disponibiliza o metodo `processSearch()` que recebe como parâmetro um `Illuminate\Database\Eloquent\Builder` e lê os filtros passados via query-string .

💡 Para utilizá-los na query : **filter[nomeCampo][operador]**

Listagem de operadores disponiveis:

| Operador | Equivalente |
| --- |-------------|
| lt | <           |
| gt | \>          |
| lte | <=          |
| gte | \>=         |
| eq | =           |
| neq | !=          |
| in | IN          |
| nin | NOT IN      |
| like | LIKE        |
| btw | BETWEEN     |
| sort | SORT        |
| limit | LIMIT       |

## Paginatable:
Esta trait disponibiliza o metodo `buildPagination()` que recebe como parâmetro um `Illuminate\Database\Eloquent\Builder` e como segundo parametro um `int $perPage` que sinaliza a quantidade de resultados por pagina.
Este metodo lê o estado atual da do Builder passado executando assim o que foi configurado como a paginação e o limit baseado no parâmetro secundario que possui 40 como default.

E possivel passar o parâmetro **`perPage`** como query-string na requisição.

ex: `?filter[name][like]=devs&perPage=100`

É possível em uma listagem ignorar a paginação passando o parâmetro `bool skipPagination`

ex: `?filter[name][like]=devs&skipPagination=1`

Apos a execução e atribuido um parâmetro `data` e um `paginationData` onde o Data tera os registros todos com parceamento em atributos primitivos e o PaginationData seriam os meta-dados sobre a Paginação. 

## Limitable:

Esta trait disponibiliza lê a query `limit` passada na requisição e retorna apenas a quantidade de registros informados.
ex: `?filter[name][like]=devs&limit=100`

## Sorteable:
Esta trait disponibiliza o metodo `processSort()` que recebe como parâmetro um `Illuminate\Database\Eloquent\Builder` 
este metodo lê o estado atual da do Builder passado executando assim o sorteamento.

💡 Para utilizá-lo na query :  **`sort`** como query-string na requisição.

ex: `?filter[name][like]=devs&sort=createdAt`

## Exemplo de Query Strings:

Abaixo uma tabela com queries, descritivos e exemplos para cada situação:

| Query | Descrição                                                                                                 | Exemplo |
| --- |-----------------------------------------------------------------------------------------------------------| --- |
| filter[nomeCampo] = valor | Retorna o valor idêntico ao consultado.                                                                   | nome = “Junior” |
| filter[nome][like] = valor | Retorna os registos que possuem no campo nome o valor ou parte do valor passado.                          | nome LIKE “%valor%” |
| filter[nome][in] = A,B,C | Retorna todos os registros que possuem A,B ou C equivalentes.                                             | nome IN (A,B,C) |
| filter[nome][lt] = 20 | Retorna os registros menor que 20. (Less Than)                                                            | nome < 20 |
| filter[nome][lte] = 15 | Retorna os registros menores que e iguais á 15. (Less Than Equal)                                         | nome ≤ 15 |
| filter[nome][gt] = 10 | retorna os registros maiores que 10. (Greater Than)                                                       | nome > 10 |
| filter[nome][gte] = 12 | retorna os registros maiores que e iguais a 12. (Greater Than Equals)                                     | nome ≥ 12 |
| filter[nome][btw] = 2022-12-02, 2023-01-05 | retorna os registros que estiverem entre um determinado intervalo, nesse exemplo 2022-12-02 e 2022-01-05. | nome BETWEEN 2022-12-02 AND 2023-01-05 |
| sort = createdAt, -numbers | Ordena o retorno por createdAt em ascendente e numbers em descendente. Para resultados de forma descendente adicionar o - (hífen) antes do campo|
| limit = 10 | Limita a quantidade de resultados.                                                                        |  |


#### Veja um exemplo de aplicação:

```php
<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MoisesK\LaravelLazyFilters\Limitable;
use MoisesK\LaravelLazyFilters\Paginatable;
use MoisesK\LaravelLazyFilters\Searcheable;
use MoisesK\LaravelLazyFilters\Sorteable;

final class ExampleController
{
    use Searcheable;
    use Sorteable;
    use Limitable;
    use Paginatable;

    public function listAllRegisters(Request $request): JsonResponse
    {
        // Crie uma query Laravel Way com Model::query()
        $query = ExampleModel::query();

        //Chame os metodos das traits conforme precise usar, vide documentação
        $this->processSearch($query);
        $this->processSort($query);
        $this->buildPagination($query);
        
        // Propriedade que ficara armazenado os registros apos os filtros.
        $this->data
        
        // Propriedade que ficara armazenado os dados de paginação.
        $this->paginationData
    }
}
```

## Contribuição

A contribuição para este projeto é realizada por meio de Pull Requests (PRs). Antes de enviar uma contribuição, certifique-se de seguir estas etapas:

1. **Faça um Fork do Repositório:**
    - Faça um fork do repositório para a sua conta no GitHub.
    - Clone o repositório forkado para a sua máquina local.

2. ***Crie uma Branch para sua Contribuição:***
   - Crie uma nova branch para trabalhar na sua contribuição.

3. ***Faça as Modificações:***
    - Implemente as alterações necessárias no código..

4. ***Teste suas Modificações:***
   - Certifique-se de que suas alterações funcionam conforme o esperado.
   - Execute testes adicionais, se aplicável.

5. ***Envie o Pull Request::***
   - Faça o commit das suas alterações.
   - Envie o Pull Request para o repositório principal.

### Diretrizes do Pull Request
Ao enviar um Pull Request, por favor, siga estas diretrizes:

- Descreva claramente as alterações introduzidas pelo seu Pull Request.
- Inclua informações detalhadas sobre o motivo das alterações.
- Se possível, faça referência a problemas específicos por meio de números de issues.

Para tornar o processo de revisão de Pull Requests mais eficiente, aqui estão algumas preferências pessoais:

- Utilize um estilo de codificação consistente com o restante do projeto.
- Inclua testes unitários para as novas funcionalidades ou correções.
- Mantenha as mensagens de commit claras e concisas.

Happy coding! 🚀