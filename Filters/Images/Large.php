<?php

declare(strict_types=1);

// namespace Intervention\Image\Templates;

namespace Modules\Xot\Filters\Images;

use Intervention\Image\Filters\FilterContract;
use Intervention\Image\Image;

class Large implements FilterContract
{
    public function applyFilter(Image $image)
    {
        return $image->fit(480, 360);
    }
}
