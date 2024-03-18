<?php

namespace ViragRouter;

interface RequestInterface
{
    public function getMethod(): string;
    public function getUri(): string;
    // Add other methods as needed...
}
