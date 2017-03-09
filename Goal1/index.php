<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" charset="utf-8"></script>
    <style media="screen">
      body{
        padding-top: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-6">

          <?php
          set_time_limit(120);
          include '../Consupedia/GapmindReader.php';

          error_reporting(E_ALL);
          ini_set('display_errors', TRUE);
          ini_set('display_startup_errors', TRUE);

          $ExtreamPoverty = GapmindReader::get("Indicator_ExtreamPoverty.xlsx");
          $IndicatorPoverty = GapmindReader::get("Indicator_Poverty.xlsx");

          $result = array();
          foreach($ExtreamPoverty->getCountries() as $c){

            $EP =  $ExtreamPoverty->country($c)->getMeanValue(10);

            try {
                if($EP!==NULL){
                  $IP = $IndicatorPoverty->country($c)->getMeanValue(10);
                  if($IP!==NULL)
                    $result[$c] = 100-(($EP*0.6)+($IP*0.4));
                }

            } catch (Exception $e) {}

          }

          echo '<h1>Poverty: '.count($result).' st länder analyserade</h1>';

          arsort($result);
          echo '<pre>';
          print_r(
            $result
          );
          echo '</pre>';
          echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB";


          ?>

          </div>
          <div class="col-md-2"></div>
      </div>
    </div>
  </body>
</html>
