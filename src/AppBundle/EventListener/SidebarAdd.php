<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\HttpFoundation\Response;

namespace AppBundle\EventListener;

/**
 * Description of SidebarAdd
 *
 * @author ipeps
 */
class SidebarAdd {

  protected $betaHTML;
  protected $endDate;

  public function __construct(BetaHTMLAdder $betaHTML, $endDate)

  {

    $this->betaHTML = $betaHTML;

    $this->endDate  = new \Datetime($endDate);

  }


  public function processBeta()

  {

    $remainingDays = $this->endDate->diff(new \Datetime())->days;


    if ($remainingDays <= 0) {

      // Si la date est dépassée, on ne fait rien

      return;

    }

    

    // Ici on appelera la méthode

    // $this->betaHTML->addBeta()

  }
}
