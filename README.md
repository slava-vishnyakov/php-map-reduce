To install:

```
composer require slava-vishnyakov/map-reduce
```

```php
use \SlavaVishnyakov\MapReduce\MapReduceMemory;

$m = new MapReduceMemory(); // or MapReduceProcess
$m->send('a', 1);
$m->send('b', 1);
$m->send('a', 2);

foreach($m->iter() as $key => $groups) {
// yields
$key = 'a', $groups = [1,2]
$key = 'b', $groups = [1]
```

Also can be done via next() calls:
```php
$m = new MapReduceMemory(); // or MapReduceProcess
$m->send('a', 1);
$m->send('b', 1);
$m->send('a', 2);
$m->send('a', new stdClass);

$m->next() ==> ['a', [1, 2, new stdClass]] 
$m->next() ==> ['b', [1]]
$m->next() ==> null
```

There are two implementations `MapReduceMemory` and `MapReduceMemory`.

The first does all sorting in memory, the second is for memory-hungry workloads, uses `/usr/bin/sort` to 
process basically unlimited amount of data.