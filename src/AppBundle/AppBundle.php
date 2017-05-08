<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use AppBundle\Helper\Globals;

class AppBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
    
    public function boot() {
        
        Globals::setDocumentsUploadDir($this->container->getParameter('uri_documents_prefix_value'));
        
    }
}
