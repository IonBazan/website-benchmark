<?php

declare(strict_types=1);

namespace App\DTO;

use Vistik\Collections\TypedCollection;

class WebsiteResultCollection extends TypedCollection
{
    protected $type = WebsiteResult::class;
}
