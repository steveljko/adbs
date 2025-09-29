<?php

declare(strict_types=1);

namespace App\Actions\Tag;

final class GenerateBackgroundColorAction
{
    public function execute(string $color): string
    {
        $lightness = (float) 0.95;
        $hexColor = mb_ltrim($color, '#');

        if (mb_strlen($hexColor) === 3) {
            $hexColor = $hexColor[0].$hexColor[0].
                $hexColor[1].$hexColor[1].
                $hexColor[2].$hexColor[2];
        }

        $r = hexdec(mb_substr($hexColor, 0, 2));
        $g = hexdec(mb_substr($hexColor, 2, 2));
        $b = hexdec(mb_substr($hexColor, 4, 2));

        // apply lightness by blending with white
        $r = (int) round($r + ($lightness * (255 - $r)));
        $g = (int) round($g + ($lightness * (255 - $g)));
        $b = (int) round($b + ($lightness * (255 - $b)));

        // ensure values are within valid range
        $r = max(0, min(255, $r));
        $g = max(0, min(255, $g));
        $b = max(0, min(255, $b));

        // convert back to hex
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
