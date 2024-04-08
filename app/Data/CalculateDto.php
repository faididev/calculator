<?php

namespace App\Data;

class CalculateDto
{

    public function __construct(
        public readonly string $firstNumber,
        public readonly string $operator,
        public readonly string $secondNumber,
    ){}

}
