<?php

class Application_Model_VehiclePdfCertificate
{
	
	protected $pdf;
	protected $page;
	protected $font;

	public function __construct()
	{
		$this->pdf = new Zend_Pdf();
    $this->page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    $this->font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
	}

  public function createPdf($vehicleId, $period, $validity)
  {
    $vehicle = new Application_Model_Vehicle(); 
    $vehicleRow = $vehicle->returnById($vehicleId);
    $vehicleLastInspection = $vehicle->returnLastInspection($vehicleId);
    $vehicleLastCRV = $vehicle->returnLastCRV($vehicleId);
    $this->header();
    $this->footer();
    $this->vehicleBodyHead($vehicleLastCRV);
    //$this->vehicleBodyHead(-407);
    $this->vehicleBodyBoxes($vehicleLastCRV,$vehicleRow);
    //$this->vehicleBodyBoxes($vehicleRow,-407);
    $this->vehicleOtherData($vehicleRow, $vehicleLastInspection, $vehicleId, $period, $validity);
   //$this->vehicleOtherData($vehicleRow, $vehicleLast, $vehicleId, $period, $validity, -407);
    $this->pdf->pages[] = $this->page;
    return $this->pdf;
  }

	protected function header()
	{
    $image = Zend_Pdf_Image::imageWithPath(APPLICATION_PATH . '/../public/img/brasao-da-PMI.png');
    $imageTransita = Zend_Pdf_Image::imageWithPath(APPLICATION_PATH . '/../public/img/TRANSITA.png');
    $this->page->drawImage($image, 61, 747, 131, 811);
    $this->page->drawImage($imageTransita, 474, 747, 544, 811);
    
    $this->page->setLineWidth(2)
        ->drawLine(50, 820, 545 , 820);

    $this->page->setLineWidth(2)
        ->drawLine(50, 740, 545, 740);

    $this->page->setLineWidth(2)
        ->drawLine(51, 740, 51, 819);

    $this->page->setLineWidth(2)
        ->drawLine(544, 740, 544, 820);

    $this->page->setLineWidth(2)
        ->drawLine(544, 740, 544, 820);

    $this->page->setLineWidth(0.5)
        ->drawLine(140, 740, 140, 820);

    $this->page->setLineWidth(0.5)
        ->drawLine(470, 740, 470, 820);

    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),13)
                    ->drawText('AUTORIZAÇÃO DE TRÁFEGO', 200, 790, 'UTF-8');
    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),13)
                    ->drawText('PARA VEÍCULOS DO TRANSPORTE', 185, 775, 'UTF-8');
    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),13)
                    ->drawText('COLETIVO DE PASSAGEIROS', 200, 760, 'UTF-8');
        
    //$this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),9)
    //                ->drawText('SUBSECRETARIA DE TRANSPORTES E OBRAS PÚBLICAS', 185, 767, 'UTF-8');

    //$this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
    //                ->drawText('1a. Via', 490, 775, 'UTF-8');
	}

	protected function footer()
	{
            /*
    $image = Zend_Pdf_Image::imageWithPath(APPLICATION_PATH . '/../public/img/brasao.png');
    $this->page->drawImage($image, 61, 340, 131, 404);
    
    $this->page->setLineWidth(2)
        ->drawLine(300, 332, 545, 660);

    $this->page->setLineWidth(2)
        ->drawLine(51, 332, 51, 412);

    $this->page->setLineWidth(2)
        ->drawLine(544, 332, 544, 412);

    $this->page->setLineWidth(2)
        ->drawLine(544, 332, 544, 412);

    $this->page->setLineWidth(0.5)
        ->drawLine(140, 332, 140, 412);

    $this->page->setLineWidth(0.5)
        ->drawLine(480, 332, 480, 412);
        
    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),13)
                    ->drawText('CERTIFICADO DE REGISTRO DO VEÍCULO', 170, 375, 'UTF-8');

    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),9)
                    ->drawText('SUBSECRETARIA DE TRANSPORTES E OBRAS PÚBLICAS', 185, 362, 'UTF-8');

    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                    ->drawText('2a. Via', 490, 368, 'UTF-8');*/
	}

    protected function vehicleBodyHead($vehicleLastCRV, $relative=0)
    {    
        $this->page     ->setLineWidth(2)
                        ->drawLine(50, $relative + 740, 545, $relative + 740);

        $this->page     ->setLineWidth(2)
                        ->drawLine(544, $relative + 450, 544, $relative + 740);

        $this->page     ->setLineWidth(2)
                        ->drawLine(51, $relative + 450, 51, $relative + 740);

        $this->page     ->setLineWidth(2)
                        ->drawLine(50, $relative + 450, 545, $relative + 450);

        $this->page->setLineWidth(0.5)
            ->drawLine(297.5, $relative + 620, 297.5 , $relative + 740);
        $this->page->setLineWidth(0.5)
            ->drawLine(50, $relative + 620, 545 ,$relative +  620);

        $this->page->setLineWidth(0.5)
            ->drawLine(170, $relative + 680, 170 ,$relative +  740);
        $this->page->setLineWidth(0.5)
            ->drawLine(50, $relative + 710, 545 , $relative + 710);    

        $this->page->setLineWidth(0.5)
            ->drawLine(421, $relative + 680, 421 , $relative + 740);    
        $this->page->setLineWidth(0.5)
            ->drawLine(50, $relative + 680, 545 , $relative + 680);
        
        $this->page->setLineWidth(0.5)
            ->drawLine(50, $relative +  650, 545 ,$relative +  650);
        
        $this->page->setLineWidth(2)
            ->drawLine(50, $relative +  600, 545 ,$relative +  600);
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),12)
                      ->drawText('AUTORIZAÇÃO :', 55, $relative + 607, 'UTF-8');     
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),12)
                      ->drawText($vehicleLastCRV->id, 180, $relative + 607, 'UTF-8'); 
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC),10)
                      ->drawText('ESTE VEÍCULO FOI VISTORIADO E ESTÁ AUTORIZADO A PRESTAR SERVIÇO DE', 60, $relative + 590, 'UTF-8');
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC),10)
                      ->drawText('TRANSPORTE COLETIVO DE PASSAGEIROS NO MUNICÍPIO DE ITABIRA-MG', 60, $relative + 578, 'UTF-8');
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC),10)
                      ->drawText('DESDE QUE MANTIDA AS CONDIÇÕES TÉCNICAS E MECÂNICAS CONFORME', 60, $relative + 566, 'UTF-8');
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC),10)
                      ->drawText('APROVADO EM INSPEÇÃO.', 60, $relative + 554, 'UTF-8');
      //$this->page     ->setLineWidth(2)
      //                ->drawLine(51, $relative + 710, 544, $relative + 710);
      /*
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),8)
                      ->drawText('CARACTERÍSTICAS DO VEÍCULO', 235, $relative + 715, 'UTF-8');

      $this->page     ->setLineWidth(0.3)
                      ->drawLine(51, $relative + 666, 544, $relative + 666); 

      $this->page     ->setLineWidth(0.3)
                      ->drawLine(51, $relative + 640, 544, $relative + 640);  

      $this->page     ->setLineWidth(0.3)
                      ->drawLine(51, $relative + 610, 544, $relative + 610);    

      $this->page     ->setLineWidth(0.3)
                      ->drawLine(477, $relative + 610, 477, $relative + 666); 

      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),8)
                      ->drawText('Marca / Modelo do Chassi', 234, $relative + 658, 'UTF-8'); 

      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),8)
                      ->drawText('Marca / Modelo da Carroceria', 229, $relative + 631, 'UTF-8'); 

      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),8)
                      ->drawText('Ano do Chassi', 485, $relative + 658, 'UTF-8');

      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),8)
                      ->drawText('Ano da Carroceria', 478, $relative + 631, 'UTF-8'); 
     * 
     */  
    }

    protected function vehicleBodyBoxes($vehicleLastCRV, $vehicleRow,$relative=0)
    {
      $this->registerNumber($vehicleLastCRV, $relative,$vehicleRow->external_number);
      $this->collectorArea($vehicleRow->collector_area,$relative);
      $this->plate($vehicleRow->plate,$relative);
      $this->renavam($vehicleRow->renavam,$relative);
      $this->frame($vehicleRow->chassi_number,$relative);
      $this->color($vehicleRow->color,$relative);
      $this->seal($vehicleRow->seal_roulette,$vehicleRow->seal_floor,$vehicleRow->seal_support,$relative);
      $this->engineData($vehicleRow,$relative);
      $this->ownerVehicle($vehicleRow,$relative);
    }

    protected function registerNumber($vehicleLastCRV, $relative = 0, $externalNumber= '')
    {
    /*    
    // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 707, 145, $relative + 707);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 707, 54, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(145, $relative + 707, 145, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 697, 145, $relative + 697);
     * 
     */
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Nº da Licença', 75, $relative + 720, 'UTF-8');
/*
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 694, 145, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 694, 54, $relative + 682);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(145, $relative + 694, 145, $relative + 682);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 682, 145, $relative + 682);
 * 
 */
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),12)
                        ->drawText($vehicleLastCRV->id, 100, $relative + 690);
    }
    
    protected function collectorArea($collectorArea, $relative = 0)
    {
        if($collectorArea)
        {
            $collectorArea = 'SIM';
        }
        else
        {
            $collectorArea = 'NÃO';
        }
        /*
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(147, $relative + 707, 217, $relative + 707);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(147, $relative + 707, 147, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(217, $relative + 707, 217, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(147, $relative + 697, 217, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Área p/ Cobrador', 155, $relative + 700, 'UTF-8');

        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(147, $relative + 694, 217, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(147, $relative + 694, 147, $relative + 682);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(217, $relative + 694, 217, $relative + 682);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(147, $relative + 682, 217, $relative + 682);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),10)
                        ->drawText($collectorArea, 174, $relative + 685, 'UTF-8');
     */
    }
    
    protected function plate($plate, $relative = 0)
    {
        /*
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(219, $relative + 707, 285, $relative + 707);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(219, $relative + 707, 219, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(285, $relative + 707, 285, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(219, $relative + 697, 285, $relative + 697);
         * 
         */
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Placa do Veículo', 180, $relative + 720, 'UTF-8');
/*
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(219, $relative + 694, 285, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(219, $relative + 694, 219, $relative + 682);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(285, $relative + 694, 285, $relative + 682);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(219, $relative + 682, 285, $relative + 682);
 * 
 */
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($plate, 220, $relative + 690);
    }   

    protected function renavam($renavam, $relative = 0)
    {
       /* // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(287, $relative + 707, 355, $relative + 707);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(287, $relative + 707, 287, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(355, $relative + 707, 355,$relative +  697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(287, $relative + 697, 355, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('RENAVAM', 303, $relative + 700);

        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(287, $relative + 694, 355, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(287, $relative + 694, 287, $relative + 682);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(355, $relative + 694, 355, $relative + 682);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(287, $relative + 682, 355, $relative + 682);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),10)
                        ->drawText($renavam, 298, $relative + 685);*/
    }

    protected function frame($frame, $relative = 0)
    {
        /*
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(357, $relative + 707, 477, $relative + 707);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(357, $relative + 707, 357, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(477, $relative + 707, 477, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(357, $relative + 697, 477, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Nro. do Chassi', 395, $relative + 700);

        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(357, $relative + 694, 477, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(357, $relative + 694, 357, $relative + 682);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(477, $relative + 694, 477, $relative + 682);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(357, $relative + 682, 477, $relative + 682);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),10)
                        ->drawText($frame, 369, $relative + 685);*/
    }

    protected function color($color, $relative = 0)
    {
       /*
        $colorDb = new Application_Model_DbTable_VehicleColor();
        $colorRow = $colorDb->fetchRow($colorDb->select()->where('id = ?',$color));

        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(479, $relative + 707, 542, $relative + 707);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(479, $relative + 707, 479, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(542, $relative + 707, 542, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(479, $relative + 697, 542, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Cor', 503, $relative + 700, 'UTF-8');

        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(479, $relative + 694, 542, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(479, $relative + 694, 479, $relative + 682);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(542, $relative + 694, 542, $relative + 682);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(479, $relative + 682, 542, $relative + 682);

        if(strlen($colorRow->name) == 4)
        {
            $length = 497;
        }
        elseif (strlen($colorRow->name) == 5) 
        {
            $length = 492;
        }
        elseif (strlen($colorRow->name) == 7) 
        {
            $length = 487;
        }
        else
        {
            $length = 481;
        }
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),10)
                        ->drawText($colorRow->name, $length, $relative + 685, 'UTF-8');
       */
    }

    protected function seal($seal_roulette,$seal_floor,$seal_support,$relative = 0)
    {
        /*
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(54, $relative + 680, 217, $relative + 680);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(54, $relative + 680, 54, $relative + 668);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(217, $relative + 680, 217, $relative + 668);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 668, 217, $relative + 668);

        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(219, $relative + 680, 355, $relative + 680);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(219, $relative + 680, 219, $relative + 668);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(355, $relative + 680, 355, $relative + 668);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(219, $relative + 668, 355, $relative + 668);

        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(357, $relative + 680, 542, $relative + 680);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(357, $relative + 680, 357, $relative + 668);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(542, $relative + 680, 542, $relative + 668);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(357, $relative + 668, 542, $relative + 668);


      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),9)
                      ->drawText('Selo da Roleta:', 90, $relative + 672, 'UTF-8'); 
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),9)
                      ->drawText('Selo do Assoalho:', 242, $relative + 672, 'UTF-8'); 
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),9)
                      ->drawText('Selo do Pescoço:', 400, $relative + 672, 'UTF-8');


      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                      ->drawText($seal_roulette, 152, $relative + 671);
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                      ->drawText($seal_floor, 313, $relative + 671);
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                      ->drawText($seal_support, 467, $relative + 671);
      */

    }

    protected function engineData($vehicleRow,$relative = 0)
    {
        $chassi = new Application_Model_DbTable_VehicleChassi();
        $chassiRow = $chassi->fetchRow($chassi->select()->where('id = ?',$vehicleRow->chassi_model));
        $body = new Application_Model_DbTable_VehicleBody();
        $bodyRow = $body->fetchRow($body->select()->where('id = ?',$vehicleRow->body_model));
        /*
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($chassiRow->name, 56, $relative + 647, 'UTF-8');
        */
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Ano de Fab.', 320, $relative + 720, 'UTF-8');
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),12)
                        ->drawText($vehicleRow->chassi_year, 350, $relative + 690, 'UTF-8');

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Data de Aquisição', 430, $relative + 720, 'UTF-8');        
        
       
         $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),12)
                        ->drawText($vehicleRow->start_date, 480, $relative + 690, 'UTF-8');
         
        /*$this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($bodyRow->name, 56, $relative +  616, 'UTF-8');

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($vehicleRow->body_year, 498, $relative + 616, 'UTF-8');
        */ 
         
    }

    protected function ownerVehicle($vehicleRow, $relative = 0)
    {
        /*
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 596, 410, $relative + 596);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 596, 54, $relative + 607);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(410, $relative + 596, 410, $relative + 607);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 607, 410, $relative + 607);
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(412, $relative + 596, 542, $relative + 596);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(412, $relative + 596, 412, $relative + 607);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(542, $relative + 596, 542, $relative + 607);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(412, $relative + 607, 542, $relative + 607);
*/
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Nome da Concessionaria:', 100, $relative + 660, 'UTF-8');
        // Descrição
        /*
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),8)
                        ->drawText('Célula Operacional', 450, $relative + 599, 'UTF-8');
        */
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($vehicleRow->consortium_name, 80, $relative + 630, 'UTF-8');
        // // Descrição
        /*
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($vehicleRow->cell_name, 460, $relative + 580, 'UTF-8');
        
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(50, $relative + 576, 543, $relative + 576);

        $this->page     ->setLineWidth(0.5)
                        ->drawLine(50, $relative +  572, 543, $relative + 572);
         * 
         */
                // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Capacidade', 380, $relative + 660, 'UTF-8');
        // Descrição
        /*
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),8)
                        ->drawText('Célula Operacional', 450, $relative + 599, 'UTF-8');
        */
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($vehicleRow->consortium_name, 80, $relative + 630, 'UTF-8');
    }

    protected function vehicleOtherData($vehicleRow, $vehicleLastInspection,$vehicleId, $period, $validity, $relative = 0)
    {
        $authNamespace = new Zend_Session_Namespace('userInformation');
        $user = new Application_Model_DbTable_User();
        $select = $user->select()->where('id = ?',$authNamespace->user_id);
        $userRow = $user->fetchRow($select);

        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),12)
                        ->drawText('Próxima Vistoria:', 170, $relative + 536, 'UTF-8');

        $this->page     ->setLineWidth(0.5)
            ->drawLine(50, $relative + 548, 543, $relative + 548);
        
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(50, $relative + 530, 545, $relative + 530);
        
        date_default_timezone_set( 'America/Sao_Paulo' );  
        $date = new Zend_Date();
        // Descrição
        
        if (($validity=="MESES")||($validity=="MES")||($validity=="MÊS"))
        {
            $tempo="months";
        }else{
            $tempo="years";
        }
            
        
        $data = date('d/m/Y', strtotime('+ '.$period.' '.$tempo));//.$period.' '.$tempo));
                
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC),12)
                        ->drawText($data, 280, $relative + 536,'UTF-8');
        
//->drawText($date->toString('dd/MM/YYYY'), 280, $relative + 536);
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Emissão: ', 55, $relative + 515,'UTF-8');
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC),12)
                        ->drawText($date->toString('dd/MM/YYYY'), 180, $relative + 515);
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Nº do Veículo: ', 55, $relative + 493,'UTF-8');
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC),12)
                        ->drawText($vehicleId, 180, $relative + 493,'UTF-8');
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('Última Vistoria: ', 55, $relative + 471,'UTF-8');
        
        $dataTratada=list($year,$month,$day)=explode("-",$vehicleLastInspection->data);
        $dataTratada=$day.'/'.$month.'/'.$year;
        if (empty($dataTratada)) {
            $dataTratada='Data não encontrada.';
        }
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC),12)
                        ->drawText($dataTratada, 180, $relative + 471,'UTF-8');
        
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(250, $relative + 475, 545, $relative + 475);
        
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),12)
                        ->drawText('Superintendente de Transporte e Trânsito', 290, $relative + 460,'UTF-8');
//impirmir númer  de assentos no Ônibus
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),12)
                        ->drawText($vehicleRow->number_seats, 400, $relative + 630,'UTF-8');
         
        //$this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
        //                ->drawText($validity, 478, $relative + 500, 'UTF-8');
    /*    
    // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(53, $relative + 535, 53, $relative + 546);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(230, $relative + 535, 230, $relative + 546);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(53, $relative + 546, 230, $relative + 546);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),8)
                        ->drawText('Responsável pela Expedição', 97, $relative + 538, 'UTF-8');
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(233, $relative + 535, 370, $relative + 535);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(233, $relative + 535, 233, $relative + 546);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(370, $relative + 535, 370, $relative + 546);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(233, $relative + 546, 370, $relative + 546);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($userRow->name . ' - SETOP', 55, $relative + 515, 'UTF-8');
*/
/*        
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),8)
                        ->drawText('Autoridade Competente', 264, $relative + 538, 'UTF-8');

        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(374, $relative + 535, 450, $relative + 535);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(374, $relative + 535, 374, $relative + 546);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(450, $relative + 535, 450, $relative + 546);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(374, $relative + 546, 450, $relative + 546);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),8)
                        ->drawText('Data de Emissão', 388, $relative + 538, 'UTF-8');

        date_default_timezone_set( 'America/Sao_Paulo' );  
        $date = new Zend_Date();
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText($date->toString('dd/MM/YYYY'), 380, $relative + 510);

        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(454, $relative + 535, 542, $relative + 535);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(454, $relative + 535, 454, $relative + 546);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(542, $relative + 535, 542, $relative + 546);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(454, $relative + 546, 542, $relative + 546);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),8)
                        ->drawText('Validade', 484, $relative + 538);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText($period, 490, $relative + 515);

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText($validity, 478, $relative + 500, 'UTF-8');

        $this->page     ->setLineWidth(0.5)
                        ->drawLine(50, $relative + 533, 543, $relative + 533);

        $this->page     ->setLineWidth(0.5)
                        ->drawLine(50, $relative + 495, 543, $relative + 495);

        $this->page     ->setLineWidth(0.5)
                        ->drawLine(232, $relative + 495, 232, $relative + 533);

        $this->page     ->setLineWidth(0.5)
                        ->drawLine(372, $relative + 495, 372, $relative + 533);

        $this->page     ->setLineWidth(0.5)
                        ->drawLine(452, $relative + 495, 452, $relative + 533);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                        ->drawText('VÁLIDO SOMENTE SEM EMENDAS E/OU RASURAS', 130, $relative + 475, 'UTF-8');
 * 
 */
    }
    

}

?>