<?php

namespace AppBundle\Form\ChoiceList;

use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class SectionChoice implements ChoiceLoaderInterface {
    
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;

    }
    
    public function loadChoiceList($value = null) {
        
        $router  = $this->container->get('router');
        $routeCollection = $router->getRouteCollection();

        foreach($routeCollection as $route){
            
            if($route->getOption('section') !== null)
                $tabSection[strtoupper(str_replace ('_', ' ', $route->getOption('section')))] = 'role_' . $route->getOption('section');
        }
        
        asort($tabSection);
        $choices = array_unique($tabSection);
        
        return new ArrayChoiceList($choices);
        
    }
    
    public function loadValuesForChoices(array $choices, $value = null)
    {
        // optimize when no data is preset
        if (empty($choices)) {
            return array();
        }

        $values = array();
        foreach ($choices as $route) {
            $values[] = 'role_' . $route->getOption('section');
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     * 
     * $values are the submitted string ids
     *
     */
    public function loadChoicesForValues(array $values, $value = null)
    {
        // optimize when nothing is submitted
        if (empty($values)) {
            return array();
        }

        // get the entities from ids and return whatever data you need.
        // e.g return $this->delegate->getPersonsByIds($values);
    }
    
}
