<?php

function sum(int $a, int $b)
{
    return $a + $b;
}

function subtract(int $a, int $b)
{
    return $a - $b;
}


test('2 numbers are added properly', function () {
    expect(sum(1, 2))->toBe(3);
    expect(sum(1, 2))->toEqual(3);
    expect(sum(1, 2))->toBeInt();
    expect(sum(1, 2))->toBeGreaterThan(2);
});

test('2 numbers are subtracted properly', function () {
    expect(subtract(5, 2))->toBe(3);
    expect(subtract(5, 2))->toEqual(3);
    expect(subtract(5, 2))->toBeInt();
    expect(subtract(5, 2))->toBeGreaterThan(2);
});
