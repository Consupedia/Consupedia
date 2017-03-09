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

          $ChildrenOutOfSchool = GapmindReader::get("Child out of school primary.xlsx");
          $population = GapmindReader::get("indicator_total 5-9 number.xlsx");

          $yearsInScoolMen = GapmindReader::get("Years in school men 25-34.xlsx");
          $yearsInScoolWomen = GapmindReader::get("Years in school women 25-34.xlsx");

          $result = array();
          foreach($ChildrenOutOfSchool->getCountries() as $c){

            $COS =  $ChildrenOutOfSchool->country($c)->getRecently();

            try {

                // No data for this country
                if($COS!==NULL){

                  $childOutOfSchoolValue = $COS->value;
                  $populationValue = $population->country($c)->getClosest($COS->year)->value;

                  // No population for this country
                  if($populationValue!==0){
                    $NumberOfYearInSchool = ($yearsInScoolMen->country($c)->getRecently()->value+$yearsInScoolWomen->country($c)->getRecently()->value)/2;

                    $result[$c] = (((1-($childOutOfSchoolValue/$populationValue))/0.1)*0.8 + $NumberOfYearInSchool*0.3)/0.125;

                }
              }
            } catch (Exception $e) {}

          }

          echo '<h1>Education: '.count($result).'st länder analyserade</h1>';

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
