<?php declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    die('This script can be called only from the command line.' . PHP_EOL);
}

class Calculator
{
    /**
     * @var string
     */
    private $rawArgument;

    /**
     * @var int
     */
    private $firstNum;

    /**
     * @var int
     */
    private $secondNum;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var array
     */
    private $allowedOperators = [
        '+',
        '-',
        '*',
        '/'
    ];

    public function __construct(array $argv)
    {
        if (count($argv) <= 1) {
            throw new InvalidArgumentException('You need to provide an operation!');
        }

        $this->input = $argv[1];
    }

    public function calculate(): float
    {
        return $this->findOperator()
                    ->parseInput()
                    ->getResult();
    }

    private function findOperator(): self
    {
        foreach ($this->allowedOperators as $operator) {
            if (strpos($this->input, $operator)) {
                $this->operator = $operator;
                return $this;
            }
        }

        throw new InvalidArgumentException('Unrecognized operand provided!');
    }

    private function parseInput(): self
    {
        [$this->firstNum, $this->secondNum] = explode($this->operator, $this->input);

        if (is_numeric($this->firstNum) && is_numeric($this->secondNum)) {
            return $this;
        }

        throw new InvalidArgumentException('Provided arguments are not of type integer.');
    }

    private function getResult(): float
    {
        switch ($this->operator) {
            case '+':
                $result = $this->add((int)$this->firstNum, (int)$this->secondNum);
                break;
            case '-':
                $result = $this->subtract((int)$this->firstNum, (int)$this->secondNum);
                break;
            case '*':
                $result = $this->multiply((int)$this->firstNum, (int)$this->secondNum);
                break;
            case '/':
                $result = $this->divide((int)$this->firstNum, (int)$this->secondNum);
                break;
        }

        return $result;
    }

    private function add(int $a, int $b): int
    {
        return $a + $b;
    }

    private function subtract(int $a, int $b): int
    {
        return $a - $b;
    }

    private function multiply(int $a, int $b): int
    {
        return $a * $b;
    }

    private function divide(int $a, int $b): float
    {
        return $a / $b;
    }
}


try {
    $calculator = new Calculator($argv);
    printf('Result: %s' . PHP_EOL, $calculator->calculate());
} catch (Throwable $e) {
    printf('Error: %s' . PHP_EOL, $e->getMessage());
}