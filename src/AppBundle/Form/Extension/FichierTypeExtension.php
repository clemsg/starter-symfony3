<?php

namespace AppBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class FichierTypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return FileType::class;
    }
    
    /**
     * Add the file_path option
     *
     * @param OptionsResolver $resolver
     */
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('file_path', 'file_name'));
    }

    /**
     * Pass the file URL to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options)
    {
        if (array_key_exists('file_path', $options)) {
            $parentData = $form->getParent()->getData();
            
            if (null !== $parentData && $parentData->getNomFichier() !== null) {
                $propertyPath = PropertyAccess::createPropertyAccessor();
                $fileUrl = $propertyPath->getValue($parentData, $options['file_path']);
            } else {
                $fileUrl = null;
            }

            $view->vars['file_url'] = $fileUrl;
            
        }

        if (array_key_exists('file_name', $options)) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
                $propertyPath = PropertyAccess::createPropertyAccessor();
                $fileName = $propertyPath->getValue($parentData, $options['file_name']);
            } else {
                $fileName = null;
            }

            $view->vars['file_name'] = $fileName;
        }
        
        
    }

}