# Molecular

### Sobre

Molecular foi criado como sendo um framework extremamente leve e minimalista, contendo apenas o exencial para o inicio de
um projeto, sem dependencias de pacotes externos o framework foi totamente escrito com foco em performance com o menor 
overhead possivel para o servidor de aplicaçao, tornando o framework uma otima escolha na criaçao de APIs e micro serviços.


### Criando um novo projeto
Para criar um novo projeto basta utilizar o composer. atraves do comando :

```
composer create-project --prefer-dist molecular/molecularframework MyAwesomeAPI
```

## Rotas

O Molecular já possui um modulo de rotas capaz de registrar rotas por Methods seguindo o padrão REST
ou com um método customizado, ligando a rota a execução de uma função anonima, ou método de um controller

##### Criando uma rota para uma função anonima
```PHP
route()->get("/",function(){
   return 'is alive =)';
});
```

##### Criando uma rota para um método de um controller
```PHP
route()->get('/index','\App\Controller\HomeController@index');
```

##### Methods do request
É possivel criar rotas com qualquer método do padrão REST ou com algum Methods Customizado

```PHP
route()->get('/get','\App\Controller\HomeController@get');
route()->post('/post','\App\Controller\HomeController@post');
route()->put('/put','\App\Controller\HomeController@put');
route()->delete('/delete','\App\Controller\HomeController@delete');
route()->option('/option','\App\Controller\HomeController@option');
route()->path('/path','\App\Controller\HomeController@path');
route()->head('/head','\App\Controller\HomeController@head');
route()->any('/any','\App\Controller\HomeController@any');
```

Também é possivel criar algum método customizado, tanto para um Method especifico quanto
para um array de Methods 

```PHP
route()->custom('CUSTOM','/custom','\App\Controller\HomeController@custom');
route()->custom(['FOO','BAR'],'/customArray','\App\Controller\HomeController@customArray');
```

### Grupo de rotas
Um grupo de rotas permite que os middlewares e prefix das rotas sejam compartilhadas com todas
as rotas que pertencem ao mesmo grupo

Exemplo de grupo de rotas


```PHP
route()->group('api', function ($group) {
    
    /** @var \Molecular\Routes\RouteDispacher $group */
    
    $group->get('get', function () {
        return 'responde';
    });
    
    $group->get('/get','\App\Controller\HomeController@get');
});
```


## Middlewares
Os middlewares do Molecular funcionam como o padrão decoreitor, onde o resultado do middleware anterior
é passado para o próximo através do comando `next`, após o ultimo middleware responder, as
chamadas são retornadas por todos os middlewares do ultimo para o primeiro, para qualquer
tratamento após a execução.  
Um middleware deve obrigatóriamente implementar a interface `\Molecular\Routes\Middleware\Middleware`.  
Exemplo de um middleware que adiciona `foo` antes do payload e `bar` após o payload

```PHP
use Molecular\Http\Request;
use Molecular\Http\Response;
use Molecular\Routes\Middleware\Middleware;

class FooMiddleware extends Middleware
{

    public function handle(Request $request, Response $response)
    {
        $response->setResponseContent('foo');
        $this->next($request,$response);
        $response->setResponseContent('bar');
    }
}
```

#### Adicionando um Middleware a uma rota ou grupo de rotas
Um middleware pode ser adicionado a uma rota qualquer ou a um grupo de rotas.
Adicionando um middleware a uma rota
```PHP
route()->get('/get','\App\Controller\HomeController@get',['middleware'=>[FooMiddleware::class]]);
``` 
Da mesma forma que é possivel adicionar um middleware a uma rota, também é possivel adicionar um 
middleware a um grupo de rota, dessa forma, todas as rotas que estiverem dentro do grupo 
sofrerão o efeito do middleware


```PHP
route()->group('api', function ($group) {
    
    /** @var \Molecular\Routes\RouteDispacher $group */
    
    $group->get('get', function () {
        return 'responde';
    });
    
    $group->get('/get','\App\Controller\HomeController@get');
},['middleware'=>[FooMiddleware::class]]);
```


## Controllers
Os controllers não necessitam nenhuma classe especial para funcionarem, servem apenas como um
ponto de execução para a rota, por enquanto não existe nenhum tipo de injeção de dependencia
na contrução de um novo controller, dessa forma é recomendado que seja criado as dependencias no construtor ao invés
de serem injetadas.
Um controller deve retornar uma `string`, ou um objeto que tenha o método `__toString` implementado, caso o retorno seja uma 
view, essa já possui o método `__toString` implementado.

```PHP
use App\Model\HomeModel;

class HomeController
{
    public function index(){
        $model = new HomeModel();
        $model->work = "it's Work";
        return  view('home.php',['model'=>$model]);
    }

}
```

## Resolve
O Molecular possui uma biblioteca interna que consegue resolver a criação de uma classe
Através do método `resolve` é possivel passar o caminho de uma classe, o método retorna a classe instanciada 
a menos que algum item não tenha um valor padrão na criação do objeto

```PHP
class Bar
{
    public $bar;
    public function __construct($bar = 10){
        $this->bar = $bar;
    }
}

class Foo
{
    public $bar;
    public function __construct(Bar $bar){
        $this->bar = $bar;
    }
}

$foo = resolve(Foo::class);
echo $foo->bar->bar; // 10
```

Caso a classe que esteja sendo criada não tenha tipagem no atributo do construtor, ou não tenha valor
padrão, a criação irá lançar uma exception

```PHP
class invalidclass
{
    public function __construct($bar){
        $this->bar = $bar;
    }
}
$foo = resolve(invalidclass::class); //Exception 
```

