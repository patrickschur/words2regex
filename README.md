# words2regex

Build your regular expressions based on your texts.
It has never been easier to create regular expressions.

### Example 1
```php
<?php
 
require 'vendor/autoload.php';
 
$regex = new Words2Regex\Words2Regex();
 
$words = [
    'abc', 'abcde', 'abcdef',
    'bbc', 'bbcde', 'bbcdef',
    'cbc', 'cbcde', 'cbcdef',
];

foreach ($words as $word)
{
    $regex->add($word);
}
 
echo $regex->getRegex();
```
Output2: 
```text
([abc]bc(def?)?)
```

### Example 2
```php
<?php
 
require 'vendor/autoload.php';
 
$regex = new Words2Regex\Words2Regex();
 
$words = [
    'foo', 'foobar'
];
 
foreach ($words as $word)
{
    $regex->add($word);
}
 
echo $regex->getRegex();
```
Output2: 
```text
foo(bar)?
```