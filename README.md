```
composer require slava-vishnyakov/map-reduce
```

```php
$m = new MapReduceMemory(); // or MapReduceProcess
$m->send('a', 1);
$m->send('b', 1);
$m->send('a', 2);

$this->assertEquals(['a', [1, 2]], $m->next());
$this->assertEquals(['b', [1]], $m->next());
$this->assertEquals(null, $m->next());
```

There are two implementations `MapReduceMemory` and `MapReduceMemory`.
The first does all sorting in memory, the second is for memory-hungry workloads, uses `/usr/bin/sort` to 
process basically unlimited amount of data.