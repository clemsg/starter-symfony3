<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use AppBundle\Form\ChoiceList\SectionChoice;
use AppBundle\Form\FileUploadType;


class RegistrationType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
                ->add('prenom')
                ->add('societe')
                ->add('logo', FileUploadType::class)
                ->add('enabled', CheckboxType::class, array(
                    'label' => 'app.label.activer_compte',
                    'translation_domain' => 'AppBundle'
                ))
                ->add('roles', ChoiceType::class, array(
                    'choice_loader' => new SectionChoice($options['container']),
                    'multiple' => true,
                    'expanded' => true
                ));
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options){
            
            $form = $event->getForm();
            if($options['editMode'] == true){
                $form->add('plainPassword', RepeatedType::class, array(
                    'required' => false
                ));
            }
            
        });
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'container' => null,
            'editMode' => false
        ));
    }
    
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
