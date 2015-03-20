<?php
ob_start();

class vertex
{
    public $key         = null;
    public $visited     = 0;
    public $distance    = 1000000;  // infinite
    public $parent      = null;
    public $path        = null;
 
    public function __construct($key) 
    {
        $this->key  = $key;
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

$v0 = new vertex(0);
$v1 = new vertex(1);
$v2 = new vertex(2);
$v3 = new vertex(3);
$v4 = new vertex(4);
$v5 = new vertex(5);

$vertexs = array(
    0=>$v0,
    $v1,
    $v2,
    $v3,
    $v4,
    $v5,
);
 
$list0 = new SplDoublyLinkedList();
$list0->push(array('vertex' => $v1, 'distance' => 3));
$list0->push(array('vertex' => $v3, 'distance' => 1));
#$list0->rewind();
 
$list1 = new SplDoublyLinkedList();
$list1->push(array('vertex' => $v0, 'distance' => 3));
$list1->push(array('vertex' => $v2, 'distance' => 7));
#$list1->rewind();
 
$list2 = new SplDoublyLinkedList();
$list2->push(array('vertex' => $v1, 'distance' => 7));
$list2->push(array('vertex' => $v3, 'distance' => 8));
$list2->push(array('vertex' => $v4, 'distance' => 12));
#$list2->rewind();
 
$list3 = new SplDoublyLinkedList();
$list3->push(array('vertex' => $v0, 'distance' => 1));
$list3->push(array('vertex' => $v2, 'distance' => 8));
#$list3->rewind();
 
$list4 = new SplDoublyLinkedList();
$list4->push(array('vertex' => $v2, 'distance' => 12));
$list4->push(array('vertex' => $v5, 'distance' => 3));
#$list4->rewind();
 
$list5 = new SplDoublyLinkedList();
$list5->push(array('vertex' => $v4, 'distance' => 3));
#$list5->rewind();

function resetLists(&$adjList){
    foreach($adjList as $k => $tmpAdjItem)
        $adjList[$k]->rewind();
}

$adjacencyList = array(
    $list0,
    $list1,
    $list2,
    $list3,
    $list4,
    $list5,
);

resetLists($adjacencyList);

function calcShortestPaths(vertex $start, &$adjLists)
{
    // define an empty queue
    $q = new PriorityQueue();

    // push the starting vertex into the queue
    $q->insert($start, 0);
    $q->rewind();

    // mark the distance to it 0
    $start->distance = 0;

    // the path to the starting vertex
    $start->path = array($start->key);

    while ($q->valid()) {
        $t = $q->extract();
        $t->visited = 1;

        $l = $adjLists[$t->key];
        while ($l->valid()) {
            $item = $l->current();

            if (!$item['vertex']->visited) {
                if ($item['vertex']->distance > $t->distance + $item['distance']) {
                    $item['vertex']->distance = $t->distance + $item['distance'];
                    $item['vertex']->parent = $t;
                }

                $item['vertex']->path = array_merge($t->path, array($item['vertex']->key));

                $q->insert($item["vertex"], $item["vertex"]->distance);
            }
            $l->next();
        }
        $q->recoverFromCorruption();
        $q->rewind();
    }
    //resetLists($adjLists);
}

/*
calcShortestPaths($v1, $adjacencyList);

// The path from node 0 to node 5
// [0, 1, 2, 4, 5]
echo '[' . implode(', ', $v5->path) . ']'.PHP_EOL;
echo  $v5->distance.PHP_EOL;
die;
*/

// The path from node 0 to node 5
// [0, 1, 2, 4, 5]

$tempDistances = array();
for($key = 0; $key <=5; $tempDistances[$key++] = 0);

for($key = 0; $key <=5; $key++) {
    $tempStringKey = 'v'.$key;
    calcShortestPaths($vertexs[$key], $adjacencyList);
    for($k = 0; $k <= 5; $k++) {
            $tempVertex = $vertexs[$k];
            echo '-------------------------- ', PHP_EOL;
            echo 'Informacoes entre a rua ', $key, ' e a rua ', $tempVertex->key, PHP_EOL;
            echo 'Caminho: [' . implode(', ', $tempVertex->path) . ']' . PHP_EOL;
            echo 'Distancia: ', $tempVertex->distance . PHP_EOL . PHP_EOL;
            $tempDistances[$key] += $tempVertex->distance;
            //$tempDistances[$k] += $tempVertex->distance;

            var_dump($key . ' - ' . $tempDistances[$key]);
            var_dump($k . ' - ' . $tempDistances[$k]);
            echo PHP_EOL . PHP_EOL;
            echo '-------------------------- ', PHP_EOL;
            echo '+', PHP_EOL;
    }
}

//foreach($adjacencyList as $key => $list) {
//    $tempDistances[$key] = 0;
//    foreach($list as $k => $item) {
//        echo 'Informacoes entre a rua ',$key,' e a rua ',$item['vertex']->key,PHP_EOL;
//        echo 'Caminho: [' . implode(', ', $item['vertex']->path) . ']' . PHP_EOL;
//        echo 'Distancia: ', $item['vertex']->distance . PHP_EOL . PHP_EOL;
//        $tempDistances[$key] += $item['vertex']->distance;
//    }
//}

var_dump($tempDistances);
asort($tempDistances, 1);
var_dump($tempDistances);

$bestVertex = array();
$lastDistance = null;

foreach($tempDistances as $i => $distance){
    $bestVertex[] = $i;
    if(!is_null($lastDistance) && $distance != $lastDistance) break;
    $lastDistance = $distance;
}

if(count($bestVertex) > 1)
    echo 'Os melhores cruzamentos para se colocar uma SAMU sao: ';
else
    echo 'O melhor cruzamento para se colocar uma SAMU eh: ';
echo implode(', ', $bestVertex);

//echo 'adads[' . implode(', ', $v4->path) . ']'.PHP_EOL;
//echo  $v4->distance;