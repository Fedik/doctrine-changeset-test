# Performance test for UoF ``computeChangeSets``

Run in terminal as follow:
```bash
~$ php changesetTest.php case0
~$ php changesetTest.php case1
~$ php changesetTest.php case2
```

## Previous results

#### Case: default

```
Dummies 10000 items
Make dummies: 3.6029109954834
Compute changes: 0.1790030002594
Recompute changes: 0.22904109954834
```

#### Case 1: ``method_exists($orgValue, '__toString')``

```
Dummies 10000 items
Make dummies: 3.6226229667664
Compute changes: 0.18751001358032
Recompute changes: 0.23972296714783
```

_note: `DateTime` does not have `__toString` so it ignored here_

#### Case 2: ``Doctrine\DBAL\Types\Type::isValuesIdentical($val1, $val2)``

```
Dummies 10000 items
Make dummies: 3.6306829452515
Compute changes: 0.22055912017822
Recompute changes: 0.24937987327576
```

* _note: all time in seconds_
* _CPU: Intel i5, PHP: 7.0_