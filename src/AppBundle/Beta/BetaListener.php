<?php

namespace AppBundle\Beta;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaListener
{
  // Notre processeur
  protected $betaHTML;

  // La date de fin de la version bêta :
  // - Avant cette date, on affichera un compte à rebours (J-3 par exemple)
  // - Après cette date, on n'affichera plus le « bêta »
  protected $endDate;

  public function __construct(BetaHTMLAdder $betaHTML, $endDate)
  {
    $this->betaHTML = $betaHTML;
    $this->endDate  = new \Datetime($endDate);
  }

  public function processBeta(FilterResponseEvent $event)
  {
    $remainingDays = $this->endDate->diff(new \Datetime())->days;

    if ($remainingDays <= 0) {
      // Si la date est dépassée, on ne fait rien
      return;
    }
    
    // On récupère la réponse que le gestionnaire a insérée dans l'évènement
    $response = $event->getResponse();

    // Ici on modifie comme on veut la réponse…

    // Puis on insère la réponse modifiée dans l'évènement
    $event->setResponse($response);
    
    // Ici on appelera la méthode
    // $this->betaHTML->addBeta()
  }
}