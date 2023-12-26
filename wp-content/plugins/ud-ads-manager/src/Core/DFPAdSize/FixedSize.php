<?php

namespace UDAdsManager\Core\DFPAdSize;

class FixedSize extends AdSize
{
    public int $width;
    public int $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getComparableValue(): int
    {
        return $this->width;
    }

    public function jsonSerialize(): array
    {
        return [$this->width, $this->height];
    }
}
