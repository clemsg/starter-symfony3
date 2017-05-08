<?php

namespace AppBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use FOS\UserBundle\Model\UserInterface;

/**
 * Description of AuthorizationVoter
 *
 * @author utilisateur
 */
class AuthorizationVoter implements VoterInterface {
    
    protected $container;
    
    public function __construct(ContainerInterface $container) {
        
        $this->container = $container;
    }
    
    public function supportsAttribute($attribute) {

        return 'SECTION_CHECK' === $attribute;
    }
    
    public function supportsClass($class) {
        
        return true;
    }
    
    public function vote(TokenInterface $token, $object, array $attributes) {
        
        $support = false;
        
        foreach($attributes as $attribute){
            
            if($this->supportsAttribute($attribute)){
                $support = true;
            }
        }
        
        if(!$support){
            return VoterInterface::ACCESS_ABSTAIN;
        }
        
        $user = $token->getUser();
        
        if(!$user instanceof UserInterface){
            return VoterInterface::ACCESS_DENIED;
        }
        
        if($user->hasRole('ROLE_ADMIN')){
            return VoterInterface::ACCESS_GRANTED;
        }
        
        $roles = $user->getRoles();

        $router = $this->container->get('router');
        $routeCollection = $router->getRouteCollection();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $route = $routeCollection->get($request->get('_route'));
        
        $section = $route->getOption('section');
        
        foreach($roles as $role){
            if($role == 'ROLE_' . strtoupper($section)){
                return VoterInterface::ACCESS_GRANTED;
            }
        }
        
        return VoterInterface::ACCESS_DENIED;
        
    }
    
}
