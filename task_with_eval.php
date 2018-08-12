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

            if (eval('return ' . $str . ';') === $this->target) {
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


