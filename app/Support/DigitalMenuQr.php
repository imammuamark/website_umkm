<?php

namespace App\Support;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use GdImage;

final class DigitalMenuQr
{
    public function printablePng(string $url, string $label): string
    {
        $canvas = imagecreatetruecolor(1240, 1754);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        $ink = imagecolorallocate($canvas, 15, 23, 42);
        $muted = imagecolorallocate($canvas, 71, 85, 105);
        imagefill($canvas, 0, 0, $white);
        $qr = $this->qrImage($url, 850);
        imagecopy($canvas, $qr, 195, 250, 0, 0, 850, 850);
        imagedestroy($qr);
        $this->centerText($canvas, 'PANAMA CORNER', 90, 5, $muted, 2);
        $this->centerText($canvas, 'SCAN MENU', 150, 5, $ink, 4);
        $this->centerText($canvas, mb_strtoupper($label), 1160, 5, $ink, 3);
        $this->centerText($canvas, 'Pindai QR untuk melihat menu dan harga terbaru', 1240, 4, $muted, 2);
        imageline($canvas, 195, 1325, 1045, 1325, imagecolorallocate($canvas, 226, 232, 240));
        $this->centerText($canvas, mb_strimwidth($url, 0, 92, '...'), 1570, 3, $muted, 2);
        ob_start();
        imagepng($canvas, null, 9);
        $png = (string) ob_get_clean();
        imagedestroy($canvas);

        return $png;
    }

    public function printablePdf(string $url, string $label): string
    {
        $source = imagecreatefromstring($this->printablePng($url, $label));
        ob_start();
        imagejpeg($source, null, 92);
        $jpeg = (string) ob_get_clean();
        imagedestroy($source);
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /XObject << /Im0 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /XObject /Subtype /Image /Width 1240 /Height 1754 /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length '.strlen($jpeg)." >>\nstream\n".$jpeg."\nendstream",
        ];
        $content = "q\n595 0 0 842 0 0 cm\n/Im0 Do\nQ";
        $objects[] = '<< /Length '.strlen($content)." >>\nstream\n{$content}\nendstream";
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n{$object}\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf.'trailer << /Size '.(count($objects) + 1).' /Root 1 0 R >>'."\nstartxref\n{$xref}\n%%EOF";
    }

    private function qrImage(string $content, int $targetSize): GdImage
    {
        $matrix = Encoder::encode($content, ErrorCorrectionLevel::M())->getMatrix();
        $margin = 4;
        $scale = max(1, intdiv($targetSize, $matrix->getWidth() + $margin * 2));
        $image = imagecreatetruecolor($targetSize, $targetSize);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 10, 22, 19);
        imagefill($image, 0, 0, $white);
        $renderSize = ($matrix->getWidth() + $margin * 2) * $scale;
        $offset = intdiv($targetSize - $renderSize, 2) + $margin * $scale;
        for ($y = 0; $y < $matrix->getHeight(); $y++) {
            for ($x = 0; $x < $matrix->getWidth(); $x++) {
                if ($matrix->get($x, $y) === 1) {
                    imagefilledrectangle($image, $offset + $x * $scale, $offset + $y * $scale, $offset + ($x + 1) * $scale - 1, $offset + ($y + 1) * $scale - 1, $black);
                }
            }
        }

        return $image;
    }

    private function centerText(GdImage $image, string $text, int $y, int $font, int $color, int $scale = 1): void
    {
        $sourceWidth = imagefontwidth($font) * strlen($text);
        $sourceHeight = imagefontheight($font);
        $source = imagecreatetruecolor($sourceWidth, $sourceHeight);
        imagesavealpha($source, true);
        $transparent = imagecolorallocatealpha($source, 255, 255, 255, 127);
        imagefill($source, 0, 0, $transparent);
        imagestring($source, $font, 0, 0, $text, $color);
        $width = $sourceWidth * $scale;
        $height = $sourceHeight * $scale;
        imagecopyresampled($image, $source, max(20, intdiv(imagesx($image) - $width, 2)), $y, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
        imagedestroy($source);
    }
}
