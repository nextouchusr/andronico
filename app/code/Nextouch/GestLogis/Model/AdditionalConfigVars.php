<?php

namespace Nextouch\GestLogis\Model;

use \Magento\Checkout\Model\ConfigProviderInterface;

class AdditionalConfigVars implements ConfigProviderInterface
{
   public function getConfig()
   {
       $additionalVariables['zipcomment'] = __('Non puoi modificare il CAP perché hai nel carrello prodotti con consegna e servizi aggiuntivi');
       return $additionalVariables;
   }
}