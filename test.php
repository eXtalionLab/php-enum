<?php

require_once 'Enum.php';

final class Foo extends \Enum
{
    const VALUES = [
        'bar' => 1,
        'boo' => 10,
        'key' => 10
    ];
}

try {
    Foo::empty();
    \assert(false, 'Created disallow value');
} catch (\RuntimeException $ex) {
    //
}

\assert(Foo::bar() === Foo::bar(), 'Enums are not same');
\assert(Foo::bar() !== Foo::boo(), 'Different enums are same');

$enum = Foo::bar();
\assert($enum() === 1, 'Enum values is not equals 1');

\assert(Foo::fromValue(1) === Foo::bar(), 'Enum from value is different');

\assert(Foo::boo() === Foo::key(), 'Enums with same value are not same');

\assert(
    \json_encode(Foo::bar()) === '{"name":"bar","value":1}',
    'Enum json is not valid'
);

\assert((string) Foo::bar() === '1', 'Can\'t cast enum to string');
