<?php

namespace DCS\Form\PolygonFormFieldBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DCS\Form\PolygonFormFieldBundle\DataTransformer\TextToPolygonTransformer;

class PolygonType extends AbstractType
{
    private $parentType;

    public function __construct($parentType)
    {
        $this->parentType = $parentType;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new TextToPolygonTransformer(), true);
    }

    public function getParent()
    {
        return $this->parentType;
    }

    public function getName()
    {
        return 'polygon';
    }
}