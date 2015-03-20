<?php

class vertice
{
    public $chave         = null;
    public $visitado     = 0;
    public $distancia    = 1000000;  // infinite
    public $parente      = null;
    public $caminho        = null;
 
    public function __construct($chave) 
    {
        $this->chave  = $chave;
    }
}
 
class PriorityQueue extends SplPriorityQueue
{
    public function compare($a, $b)
    {
        if ($a === $b) return 0;
        return $a > $b ? -1 : 1;
    }
}
 
$v0 = new vertice(0);
$v1 = new vertice(1);
$v2 = new vertice(2);
$v3 = new vertice(3);
$v4 = new vertice(4);
$v5 = new vertice(5);
 
$list0 = new SplDoublyLinkedList();
$list0->push(array('vertice' => $v1, 'distancia' => 3));
$list0->push(array('vertice' => $v3, 'distancia' => 1));
$list0->rewind();
 
$list1 = new SplDoublyLinkedList();
$list1->push(array('vertice' => $v0, 'distancia' => 3));
$list1->push(array('vertice' => $v2, 'distancia' => 7));
$list1->rewind();
 
$list2 = new SplDoublyLinkedList();
$list2->push(array('vertice' => $v1, 'distancia' => 7));
$list2->push(array('vertice' => $v3, 'distancia' => 8));
$list2->push(array('vertice' => $v4, 'distancia' => 12));
$list2->rewind();
 
$list3 = new SplDoublyLinkedList();
$list3->push(array('vertice' => $v0, 'distancia' => 1));
$list3->push(array('vertice' => $v2, 'distancia' => 8));
$list3->rewind();
 
$list4 = new SplDoublyLinkedList();
$list4->push(array('vertice' => $v2, 'distancia' => 12));
$list4->push(array('vertice' => $v5, 'distancia' => 3));
$list4->rewind();
 
$list5 = new SplDoublyLinkedList();
$list5->push(array('vertice' => $v4, 'distancia' => 3));
$list5->rewind();
 
$listaDeAdjacencias = array(
    $list0,
    $list1,
    $list2,
    $list3,
    $list4,
    $list5,
);


 
function calculoMenorCaminho(vertice $inicio, &$listaDeAdjacencias)
{
    foreach ($listaDeAdjacencias as $value) {
        $teste[] = new $value;
    }
    
    $somaDistancia = 0;
    // define an empty queue
    $q = new PriorityQueue();
 
    // push the starting vertice into the queue
    $q->insert($inicio, 0);
    $q->rewind();
 
    // mark the distancia to it 0
    $inicio->distancia = 0;
 
    // the caminho to the starting vertice
    $inicio->caminho = array($inicio->chave);
 
    while ($q->valid()) {
        $t = $q->extract();
        $t->visitado = 1;
 
        $l = $listaDeAdjacencias[$t->chave];
        while ($l->valid()) {
            $item = $l->current();
 
            if (!$item['vertice']->visitado) {
                if ($item['vertice']->distancia > $t->distancia + $item['distancia']) 
                {
                    $item['vertice']->distancia = $t->distancia + $item['distancia'];
                    $item['vertice']->parente = $t;
                    $somaDistancia = $somaDistancia + $t->distancia ;
                }
 
                $item['vertice']->caminho = array_merge($t->caminho, array($item['vertice']->chave));
                $q->insert($item["vertice"], $item["vertice"]->distancia);
            }
            $l->next();
        }
        
        $q->recoverFromCorruption();
        $q->rewind();
    }
    
    echo 'A distancia do vertice  '. $inicio->chave. ' para os demais e ' .$somaDistancia;
    
    print_r($teste);
}
 
calculoMenorCaminho($v2, $listaDeAdjacencias);

echo '[' . implode(', ', $v5->caminho) . ']'.PHP_EOL;
echo  $v5->distancia.PHP_EOL;

echo '[' . implode(', ', $v4->caminho) . ']'.PHP_EOL;
echo  $v4->distancia;

echo '[' . implode(', ', $v3->caminho) . ']'.PHP_EOL;
echo  $v3->distancia;

echo '[' . implode(', ', $v2->caminho) . ']'.PHP_EOL;
echo  $v2->distancia;

echo '[' . implode(', ', $v1->caminho) . ']'.PHP_EOL;
echo  $v1->distancia;

echo '[' . implode(', ', $v0->caminho) . ']'.PHP_EOL;
echo  $v0->distancia;