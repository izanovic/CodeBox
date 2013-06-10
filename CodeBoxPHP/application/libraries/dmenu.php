<?php

class Dmenu {

  function show_menu()
  {
    $obj =& get_instance();
    $obj->load->helper('url');
    $dmenu = "<ul>";
      $dmenu .= "<li>"; $dmenu .= anchor("/","Home"); $dmenu .= "</li>";
      $dmenu .= "<li>"; $dmenu .= anchor("/nieuws", "Nieuws"); $dmenu .= "</li>";
      $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/", "Inschrijven");
        $dmenu .= "<ul>";
          $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/get/1", "Jaar 1"); 
            $dmenu .= "<ul>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/1/1", "Periode 1"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/1/2", "Periode 2"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/1/3", "Periode 3"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/1/4", "Periode 4"); $dmenu .= "</li>";
            $dmenu .= "</ul>";
          $dmenu .= "</li>";
          $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/get/2", "Jaar 2");
            $dmenu .= "<ul>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/2/1", "Periode 1"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/2/2", "Periode 2"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/2/3", "Periode 3"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/2/4", "Periode 4"); $dmenu .= "</li>";
            $dmenu .= "</ul>";
          $dmenu .= "</li>";
          $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/get/3", "Jaar 3");
            $dmenu .= "<ul>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/3/1", "Periode 1"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/3/2", "Periode 2"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/3/3", "Periode 3"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/3/4", "Periode 4"); $dmenu .= "</li>";
            $dmenu .= "</ul>";
          $dmenu .= "</li>";
          $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/get/4", "Jaar 4");
            $dmenu .= "<ul>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/4/1", "Periode 1"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/4/2", "Periode 2"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/4/3", "Periode 3"); $dmenu .= "</li>";
              $dmenu .= "<li>"; $dmenu .= anchor("/inschrijven/getperiod/4/4", "Periode 4"); $dmenu .= "</li>";
            $dmenu .= "</ul>";
          $dmenu .= "</li>";
        $dmenu .= "</ul>";
      $dmenu .= "</li>";
      $dmenu .= "<li>"; $dmenu .= anchor("/faq","FAQ"); $dmenu .= "</li>";
      $dmenu .= "<li>"; $dmenu .= anchor("/profiel", "Profiel");
        $dmenu .= "<ul>"; 
          $dmenu .= "<li>"; $dmenu .= anchor("/uitloggen","Uitloggen"); $dmenu .= "</li>";
        $dmenu .= "</ul>";
      $dmenu .= "</li>";
    $dmenu .= "</ul>";

    return $dmenu;
  }
}