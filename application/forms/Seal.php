<?php

class Application_Form_Seal extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {


/*
      $this->addElement('text', 'removed_seals', array(
          'label'         => 'Lacre do pescoço',
          'placeholder'   => 'Lacre do pescoço',
          'class'         => 'form-control',
          'label'         => 'Lacre do assoalho',
          'placeholder'   => 'Lacre do assoalho',
          'class'         => 'form-control',
          'label'         => 'Lacre do suporte',
          'placeholder'   => 'Lacre do suporte',
          'class'         => 'form-control',
      ));


      $this->addElement('text', 'placed_seals', array(
          'label'         => 'Lacre do pescoço',
          'placeholder'   => 'Lacre do pescoço',
          'class'         => 'form-control',
          'label'         => 'Lacre do assoalho',
          'placeholder'   => 'Lacre do assoalho',
          'class'         => 'form-control',
          'label'         => 'Lacre do suporte',
          'placeholder'   => 'Lacre do suporte',
          'class'         => 'form-control',
      ));

*/
      $this->addElement('text', 'old_roulette_number', array(
          'label'         => 'Número da roleta removida',
          'placeholder'   => 'Número da roleta removida',
          'class'         => 'form-control',
          //'required'      => 'required',
      ));


      $this->addElement('text', 'new_roulette_number', array(
          'label'         => 'Número da roleta instalada',
          'placeholder'   => 'Número da roleta instalada',
          'class'         => 'form-control',
          //'required'      => 'required',
      ));


      $sealChangeArray = array(  0 => '-- Selecione a origem da troca do lacre --',
                                  1 => 'Notificação de irregularidade',
                                  2 => 'Alteração de frota',
                                  3 => 'Solicitação da empresa',);
      $this->addElement('select','seal_change', array(
          'label'             => 'Origem da troca do lacre',
          'class'             => 'form-control',
          'MultiOptions'      => $sealChangeArray
      ) );


      $sealChangeReasonArray = array(  0 => '-- Selecione o motivo da troca do lacre --',
                                  1 => 'Defeito na roleta',
                                  2 => 'Defeito no pescoço',
                                  3 => 'Defeito no assoalho',
                                  4 => 'Defeito no suporte',
                                  5 => 'Lacre violado',
                                  6 => 'Lacre danificado',
                                  7 => 'Falta do lacre');
      $this->addElement('select','seal_change_reason', array(
          'label'             => 'Motivo',
          'class'             => 'form-control',
          'MultiOptions'      => $sealChangeReasonArray
      ) );


      $this->addElement('text', 'GR_number', array(
          'label'         => 'Número da GR',
          'placeholder'   => 'Número da GR',
          'class'         => 'form-control',
          //'required'      => 'required',
      ));

     $this->addElement('submit', 'submit', array(
        'buttonType'      => Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
        'label'           => 'Salvar',
        'class'           => 'col-lg-offset-5'
      ));
    }


}