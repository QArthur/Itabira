<?php

class Application_Model_VehiclePdfInspection
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

  public function createPdf($vehicleId)
  {
    $vehicle = new Application_Model_Vehicle(); 
    $vehicleRow = $vehicle->returnById($vehicleId);
    $this->header();
    $this->vehicleBody();
    $this->vehicleBodyBoxes($vehicleRow);
    $this->pdf->pages[] = $this->page;
    return $this->pdf;
  }

	protected function header()
	{
    $image = Zend_Pdf_Image::imageWithPath(APPLICATION_PATH . '/../public/img/dermg.png');
    $this->page->drawImage($image, 52, 747, 151, 811);

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
        ->drawLine(150, 740, 150, 820);

    $this->page->setLineWidth(0.5)
        ->drawLine(450, 740, 450, 820);

    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),20)
                    ->drawText('LAUDO DE VISTORIA', 200, 773, 'UTF-8');

    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),14)
                    ->drawText('Nº', 457, 803, 'UTF-8');

    $inspectionNumber = '03600';
    $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER),22)
                    ->setFillColor(Zend_Pdf_Color_Html::color('red'))
                    ->drawText($inspectionNumber, 465, 770, 'UTF-8');
	}

    protected function vehicleBody($relative=0)
    {
        // DOCUMENT BORDER
        //top
        $this->page     ->setLineWidth(1)
                      ->drawLine(51, $relative + 730, 544, $relative + 730);
        //right
        $this->page     ->setLineWidth(1)
                      ->drawLine(544, $relative + 30, 544, $relative + 730);
        // left
        $this->page     ->setLineWidth(1)
                      ->drawLine(51, $relative + 30, 51, $relative + 730);
        //bottom
        $this->page     ->setLineWidth(1)
                      ->drawLine(50, $relative + 30, 545, $relative + 30);

        // notification
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(55, $relative + 560, 540, $relative + 560);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(55, $relative + 537, 55, $relative + 560);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(540, $relative + 537, 540, $relative + 560);
        // BOTTOM
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(55, $relative + 537, 540, $relative + 537);
        
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),15)
                        ->setFillColor(Zend_Pdf_Color_Html::color('black'))
                        ->drawText('Notificações',270, $relative + 543, 'UTF-8');

        // NOTIFICATION LINES    
        $y = 535;
        for ($x=1; $x <= 17; $x++) {
            $this->page     ->setLineWidth(0.3)
                            ->drawLine(544, $relative + $y, 51, $relative + $y);
            $y = $y - 20;
        }
         
    }

    protected function vehicleBodyBoxes($vehicleRow,$relative=0)
    {
      $this->registerNumber($relative,$vehicleRow->external_number);
      $this->plate($vehicleRow->plate,$relative);
      $this->renavam($vehicleRow->renavam,$relative);
      $this->frame($vehicleRow->chassi_number,$relative);
      $this->color($vehicleRow->color,$relative);
      $this->seal($vehicleRow->seal_roulette,$vehicleRow->seal_floor,$vehicleRow->seal_support,$relative);
      $this->engineData($vehicleRow,$relative);
      $this->pattern($vehicleRow, $relative);
      $this->amount_seats($vehicleRow,$relative);
      $this->ownerVehicle($vehicleRow,$relative);
      $this->hours($relative);
    }

    protected function registerNumber($relative = 0, $externalNumber= '')
    {
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 727, 112, $relative + 727);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 727, 54, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(112, $relative + 727, 112, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 697, 112, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Nº de ordem', 58, $relative + 718, 'UTF-8');

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($externalNumber, 70, $relative + 705);
    }

    protected function plate($plate, $relative = 0)
    {
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(170, $relative + 727, 117, $relative + 727);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(117, $relative + 727, 117, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(170, $relative + 727, 170, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(117, $relative + 697, 170, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Placa', 120, $relative + 719);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($plate, 122, $relative + 705);
    }   

    protected function renavam($renavam, $relative = 0)
    {
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(175, $relative + 727, 243, $relative + 727);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(175, $relative + 727, 175, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(243, $relative + 727, 243,$relative +  697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(175, $relative + 697, 243, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('RENAVAM', 177, $relative + 719);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($renavam, 180, $relative + 705);
    }

    protected function frame($frame, $relative = 0)
    {
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(371, $relative + 727, 540, $relative + 727);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(371, $relative + 727, 371, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(540, $relative + 727, 540, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(371, $relative + 697, 540, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Nro. do Chassi', 375, $relative + 718);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($frame, 380, $relative + 705);
    }

    protected function color($color, $relative = 0)
    {

        $colorDb = new Application_Model_DbTable_VehicleColor();
        $colorRow = $colorDb->fetchRow($colorDb->select()->where('id = ?',$color));

        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(250, $relative + 727, 365, $relative + 727);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(250, $relative + 727, 250, $relative + 697);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(365, $relative + 727, 365, $relative + 697);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(250, $relative + 697, 365, $relative + 697);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Cor', 252, $relative + 718, 'UTF-8');

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($colorRow->name, 252, $relative + 704, 'UTF-8');
    }

    protected function seal($seal_roulette,$seal_floor,$seal_support,$relative = 0)
    {
        // seal roulette
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(409, $relative + 694, 356, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(356, $relative + 694, 356, $relative + 668);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(409, $relative + 694, 409, $relative + 668);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(356, $relative + 668, 409, $relative + 668);

        // seal floor
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(475, $relative + 694, 415, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(415, $relative + 694, 415, $relative + 668);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(475, $relative + 694, 475, $relative + 668);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(415, $relative + 668, 475, $relative + 668);

        // seal suport
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(481, $relative + 694, 540, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(481, $relative + 694, 481, $relative + 668);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(540, $relative + 694, 540, $relative + 668);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(481, $relative + 668, 540, $relative + 668);


      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                      ->drawText('Selo da Roleta:', 360, $relative + 687, 'UTF-8'); 
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                      ->drawText('Selo do Assoalho:', 419, $relative + 687, 'UTF-8'); 
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                      ->drawText('Selo do Pescoço:', 485, $relative + 687, 'UTF-8');

      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                      ->drawText($seal_roulette, 365, $relative + 673);
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                      ->drawText($seal_floor, 421, $relative + 673);
      $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                      ->drawText($seal_support, 490, $relative + 673);


    }

    protected function engineData($vehicleRow,$relative = 0)
    {
        $chassi = new Application_Model_DbTable_VehicleChassi();
        $chassiRow = $chassi->fetchRow($chassi->select()->where('id = ?',$vehicleRow->chassi_model));
        $body = new Application_Model_DbTable_VehicleBody();
        $bodyRow = $body->fetchRow($body->select()->where('id = ?',$vehicleRow->body_model));

        // box chassi name
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(54, $relative + 694, 237, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(237, $relative + 694, 237, $relative + 668);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(54, $relative + 694, 54, $relative + 668);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 668, 237, $relative + 668);

        // box chassi year
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(244, $relative + 694, 298, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(244, $relative + 694, 244, $relative + 668);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(298, $relative + 694, 298, $relative + 668);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(244, $relative + 668, 298, $relative + 668);


        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Marca / Modelo do Chassi', 57, $relative + 685, 'UTF-8');

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Ano do Chassi', 247, $relative + 685, 'UTF-8'); 

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($chassiRow->name, 56, $relative + 672, 'UTF-8');

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($vehicleRow->chassi_year, 250, $relative + 672, 'UTF-8');
    }

    protected function ownerVehicle($vehicleRow, $relative = 0)
    {   
        // company id
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(197, $relative + 665, 141, $relative + 665);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(197, $relative + 637, 197, $relative + 665);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(141, $relative + 637, 141, $relative + 665);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(197, $relative + 637, 141, $relative + 637);

        // company name
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(203, $relative + 665, 540, $relative + 665);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(203, $relative + 637, 203, $relative + 665);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(540, $relative + 637, 540, $relative + 665);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(203, $relative + 637, 540, $relative + 637);


        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),8)
                        ->drawText('Cod. empresa', 145, $relative + 655, 'UTF-8');
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),8)
                        ->drawText('Empresa',207, $relative + 655, 'UTF-8');
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($vehicleRow->company_name, 207, $relative + 642, 'UTF-8');
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText('0'.$vehicleRow->company_id, 150, $relative + 642, 'UTF-8');
    }

    protected function pattern($vehicleRow, $relative = 0){
        $pattern = new Application_Model_DbTable_VehiclePattern();
        $patternRow = $pattern->fetchRow($pattern->select()->where('id = ?',$vehicleRow->pattern));

        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(54, $relative + 665, 135, $relative + 665);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(54, $relative + 637, 54, $relative + 665);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(135, $relative + 637, 135, $relative + 665);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 637, 135, $relative + 637);

        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),8)
                        ->drawText('Padrão', 57, $relative + 655, 'UTF-8');

        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($patternRow->name, 57, $relative + 642, 'UTF-8');
    }

    protected function amount_seats($vehicleRow,$relative = 0)
    {
        // TOP
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(305, $relative + 694, 350, $relative + 694);
        // LEFT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(305, $relative + 668, 305, $relative + 694);
        // RIGHT
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(350, $relative + 668, 350, $relative + 694);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(305, $relative + 668, 350, $relative + 668);
        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Capacidade', 310, $relative + 685, 'UTF-8');

        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES),10)
                        ->drawText($vehicleRow->amount_seats, 315, $relative + 672);
    }

    protected function hours($relative = 0)
    {
        //arrived hour
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(54, $relative + 634, 110, $relative + 634);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(54, $relative + 634, 54, $relative + 600);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(110, $relative + 634, 110, $relative + 600);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(54, $relative + 600, 110, $relative + 600);

        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Hora de chegada', 57, $relative + 625, 'UTF-8');

        //started hour
        // TOP
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(117, $relative + 634, 187, $relative + 634);
        // LEFT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(117, $relative + 634, 117, $relative + 600);
        // RIGHT
        $this->page     ->setLineWidth(0.5)
                        ->drawLine(187, $relative + 634, 187, $relative + 600);
        // BOTTOM
        $this->page     ->setLineWidth(0.3)
                        ->drawLine(117, $relative + 600, 187, $relative + 600);

        // Descrição
        $this->page     ->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD),7)
                        ->drawText('Hora de atendimento', 120, $relative + 625, 'UTF-8');
    }

}

?>