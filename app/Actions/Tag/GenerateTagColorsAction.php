<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use Exception;

final class GenerateTagColorsAction
{
    private array $colors = [
        '#0B69FF',
        '#00C2A8',
        '#FF6B6B',
        '#FFB84D',
        '#9B59FF',
        '#00A3FF',
        '#27D27D',
        '#FF4DA6',
        '#4D5CFF',
        '#FFC857',
    ];

    private string $textColor = '';

    public function getTextColor(): string
    {
        $color = $this->colors[array_rand($this->colors)];
        $this->textColor = $color;

        return $this->textColor;
    }

    public function setTextColor(): string
    {
        return 'asd';
    }

    public function getBackgroundColor(float $lightness = 0.95): string
    {
        if (! $this->textColor) {
            throw new Exception('Text color is not choosen');
        }

        $hexColor = mb_ltrim($this->textColor, '#');

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
