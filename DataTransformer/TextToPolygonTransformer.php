<?php

namespace DCS\Form\PolygonFormFieldBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use CrEOF\Spatial\PHP\Types\Geometry\Polygon;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use CrEOF\Spatial\PHP\Types\Geometry\LineString;

class TextToPolygonTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Polygon) {
            throw new UnexpectedTypeException($value, 'CrEOF\Spatial\PHP\Types\Geometry\Polygon');
        }

        $ringsString = '';

        foreach ($value->getRings() as $ring) {
            $points = array();

            foreach ($ring->getPoints() as $point)
                $points[] = $point->getLongitude().','.$point->getLatitude();

            $ringsString .= '['.implode(' ', $points).']';
        }

        return $ringsString;
    }

    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!preg_match_all("/\[.*?\]/", $value, $matches))
            throw new InvalidArgumentException('Expects a value as: [n,n n,n]');

        $rings = array();

        foreach ($matches[0] as $ringString) {
            $pointsStringList = explode(' ', trim($ringString, '[]'));
            $points = array();

            foreach ($pointsStringList as $pointString) {
                list($longitude, $latitude) = explode(',', $pointString);
                $points[] = new Point($longitude, $latitude);
            }

            $rings[] = new LineString($points);
        }

        return new Polygon($rings);
    }
}