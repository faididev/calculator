<?php

namespace App\Services;

use App\Data\CalculateDto;

class CalculatorService
{
    public function calculate(CalculateDto $data)
    {
        $firstNumber = $data->firstNumber;
        $operator = $data->operator;
        $secondNumber = $data->secondNumber;

        switch ($operator) {
            case '+':
                return $this->add($firstNumber, $secondNumber);
            case '-':
                return $this->subtract($firstNumber, $secondNumber);
            case '*':
                return $this->multiply($firstNumber, $secondNumber);
            case '/':
                return $this->divide($firstNumber, $secondNumber);
            default:
                throw new \InvalidArgumentException("Invalid operator");
        }
    }

    private function add($num1, $num2)
    {
        return $num1 + $num2;
    }

    private function subtract($num1, $num2)
    {
        return $num1 - $num2;
    }

    private function multiply($num1, $num2)
    {
        return $num1 * $num2;
    }

    private function divide($num1, $num2)
    {
        if ($num2 == 0) {
            throw new \InvalidArgumentException("Cannot divide by zero.");
        }
        return $num1 / $num2;
    }
}
