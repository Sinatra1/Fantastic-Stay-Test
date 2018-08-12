<?php

class Task
{

    protected $countNumbers = 0;
    protected $operations = [];
    protected $target = 0;
    protected $combinations = [];
    protected $startTime = 0;
    protected $endTime = 0;

    public function __construct($countNumbers, $operations, $target)
    {
        $this->countNumbers = $countNumbers;
        $this->operations = $operations;
        $this->target = $target;
    }

    public function showCombinations()
    {
        if (empty($this->operations) || empty($this->countNumbers)) {
            throw new Exception("can't show combinations, becouse operations or countNumbers are empty");
        }

        $this->startExecution();

        $operationsMap = $this->getOperationsMap();

        $this->getCombinations($operationsMap);

        $this->endExecution();

        $this->printExecutionTime();

        $this->printCombinations();
    }

    protected function getCombinations($operationsMap, $str = '', $i = 0)
    {
        $i++;

        if ($i > ($this->countNumbers - 1)) {
            $str .= $i;

            if ($this->calculateCombination($str) === $this->target) {
                $this->combinations[] = $str;
            }

            return;
        }

        foreach ($operationsMap as $operation => $subOperationsMap) {
            $strBefore = $str;
            $str .= $i . $operation;

            $this->getCombinations($subOperationsMap, $str, $i);

            $str = $strBefore;
        }

        return true;
    }

    protected function getOperationsMap($i = 0)
    {
        $operationsMap = [];

        $i++;

        if ($i > $this->countNumbers) {
            return $operationsMap;
        }

        foreach ($this->operations as $operation) {
            $operationsMap[$operation] = $this->getOperationsMap($i);
        }

        return $operationsMap;
    }

    protected function printCombinations()
    {
        echo implode("<br/>", $this->combinations);
    }

    protected function calculateCombination($combinationStr)
    {
        $sum = 0;

        if (empty($combinationStr)) {
            return $sum;
        }

        $chars = str_split($combinationStr);

        $currentNumber = '';
        $currentOperation = '';
        $lastIndex = count($chars) - 1;

        $i = 0;

        while ($i < ($lastIndex - strlen($currentNumber))) {
            $k = $i;

            $lastNumber = $this->getNumber($chars, $k);

            if (empty($currentOperation)) {
                $sum = $lastNumber;
            }

            $currentOperation = $chars[$k];

            $k++;

            $i = $k;

            $currentNumber = $this->getNumber($chars, $k);

            $sum = $this->calculate($currentNumber, $sum, $currentOperation);
        }

        return $sum;
    }

    protected function getNumber($chars = [], &$k = 0)
    {
        $number = '';

        while (is_numeric($chars[$k])) {
            $number .= $chars[$k];
            $k++;
        }

        return $number;
    }

    protected function calculate($currentNumber, $lastNumber, $currentOperation)
    {
        $sum = 0;

        if (!$this->canCalculate($currentNumber, $lastNumber, $currentOperation)) {
            return $sum;
        }

        switch ($currentOperation) {
            case '+':
                $sum = $sum + $lastNumber + $currentNumber;
                break;
            case '-':
                $sum = $sum + $lastNumber - $currentNumber;
                break;
        }

        return $sum;
    }

    protected function canCalculate($currentNumber, $lastNumber, $currentOperation)
    {
        return ($currentNumber !== '' && $lastNumber !== '' && !empty($currentOperation));
    }

    protected function startExecution()
    {
        $this->startTime = microtime(true);
    }

    protected function endExecution()
    {
        $this->endTime = microtime(true);
    }

    protected function printExecutionTime()
    {
        $executionTime = $this->endTime - $this->startTime;

        echo "$executionTime sec</br></br>";
    }

}

$countNumbers = 9;
$operations = ['-', '+', ''];
$target = 100;

$task = new Task($countNumbers, $operations, $target);
$task->showCombinations();


