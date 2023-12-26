<?php

namespace UDAdsManager\Core\DFPAdSize;

class FluidSize extends AdSize
{
    public function getComparableValue(): int
    {
        return -1;
    }

    public function jsonSerialize(): string
    {
        return 'fluid';
    }
}
