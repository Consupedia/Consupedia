<?php


class GapmindReader{


  private function __construct()
  {
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
  }

  public static function get($file)
  {
    require_once('Document.php');
    require_once('benchmark.php');
    $bm = new Benchmark();
    $bm->marker('init');

    require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel/IOFactory.php';

    $objPHPExcel = PHPExcel_IOFactory::load($file);

    // Set timestamp
    $bm->marker('loaded');


    // start parse the document
    $document = array();
    $colums = array();

    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

      // Loop each row
      foreach ($worksheet->getRowIterator() as $row) {

          $cellIterator = $row->getCellIterator();

          // loop each cell
          $current_country = '';
          $index = 0;
          foreach ($cellIterator as $cell) {

            $cords = $cell->coordinateFromString($cell->getCoordinate());

            // Collect all colums names
            if($row->getRowIndex()==1){
              if($cords[0]!=='A'){
                $colums[] = $cell->getCalculatedValue();
              }
            }else{

              // Index the country name
              if($cords[0]==='A'){
                $current_country = $cell->getCalculatedValue();
              }else{
                  // Add the value to right year and country
                  $document[$current_country][$colums[$index-1]] = $cell->getCalculatedValue();
              }
            }
            $index++;
          }

      }
      // break the loop, we only care about the first page
      break;
    }

    $bm->marker('parsed');

    return new Document($document);

    //echo 'File loaded in '.$this->bm->elapsed_time('init','loaded').' seconds.<br/>';
    //echo 'File loaded in '.$this->bm->elapsed_time('loaded','parsed').' seconds.';

  }

}
