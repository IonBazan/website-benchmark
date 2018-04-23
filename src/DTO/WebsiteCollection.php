<?php

declare(strict_types=1);

namespace App\DTO;

use Vistik\Collections\TypedCollection;

class WebsiteCollection extends TypedCollection
{
    protected $type = Website::class;
}
