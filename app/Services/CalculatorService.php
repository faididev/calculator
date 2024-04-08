<?php
namespace app\Services;

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
                $result = $firstNumber + $secondNumber;
                break;
            case '-':
                $result = $firstNumber - $secondNumber;
                break;
            case '*':
                $result = $firstNumber * $secondNumber;
                break;
            case '/':
                if ($secondNumber == 0) {
                    return response()->json(['error' => 'Division by zero'], 400);
                }
                $result = $firstNumber / $secondNumber;
                break;
            default:
                return throw('invalid');
        }

        return $result;
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
