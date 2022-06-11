<?php

namespace App;

class Lock
{
    private array $rules = [
        [
            'digits' => [6,9,0],
            'hint' => [1, true]
        ],
        [
            'digits' => [7,4,1],
            'hint' => [1, false]
        ],
        [
            'digits' => [5,0,4],
            'hint' => [2, false]
        ],
        [
            'digits' => [3,8,7],
            'hint' => [0, false]
        ],
        [
            'digits' => [2,1,9],
            'hint' => [1, false]
        ],
    ];

    public function process(): void
    {
        $allPossibleNumbers = collect($this->getAllPossibleNums());

        $filtered = $allPossibleNumbers->filter(function ($item, $key)  {
            return collect($this->rules)->every(function ($v, $k) use ($item) {
                return $this->checkConstraint($this->getNumberRules($item, $v['digits']), $v['hint']);
            });
        })->values();

        echo $filtered->unique()->join(',');
    }

    private function checkConstraint($c1, $c2): bool
    {
        return $c1[0] === $c2[0] && $c1[1] === $c2[1];
    }

    private function getNumberRules(string $num, array $hintNums): array
    {
        $nums = array_map('intval', str_split($num));

        $guess = collect($hintNums)->map(function ($v, $k) use ($hintNums, $nums) {
            $includes = in_array($v, $nums);
            $correctPlace = $includes && $hintNums[$k] === $nums[$k];

            if ($includes && $correctPlace)
                return 1;
            if ($includes && !$correctPlace)
                return 0;
            return 2;
        });

        $correctCount = $guess->filter(function($v, $k) {
            return $v === 1;
        })->count();

        if ($correctCount > 0) {
            return [$correctCount, true];
        }

        $misplaceCount = $guess->filter(function ($v, $k) {
            return $v === 0;
        })->count();

        if ($misplaceCount > 0) {
            return [$misplaceCount, false];
        }

        return [0, false];
    }

    private function getAllPossibleNums(): array
    {
        $numbers = [];

        foreach (range(0, 999) as $num) {
            $numbers[] = str_pad($num, 3, '0');
        }

        return $numbers;
    }
}