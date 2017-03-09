<?php

class Document{

  private $country = null;
  private $data;
  public function __construct($parsed_data)
  {
    $this->data = $parsed_data;
  }

  /**
   * Returns all the countries in the document
   *
   * @access public
   * @return    array   Array containg all the countries
   * @throws    Exception
   */
  public function getCountries()
  {
      $return = array();
      foreach($this->data as $country=>$value){
        $return[] = $country;
      }
      return (object)$return;
  }

  /**
   * Returns the closest value for seleced coutry
   *
   * @access public
   * @param    string
   * @throws   Exception
   */
  public function getClosest($year='',$limit=5)
  {
    if($this->country!==null){
      $source = $this->data[$this->country];

      // Seleced year found
      if(isset($source[$year])){
        return (object)array('year'=>$year, 'value'=> $source[$year]);
      }
      $i=1;
      while(true){
        if($i>$limit) break;
        $yearMinus = $year-($i);
        $yearPlus = $year+($i);
        if(isset($source[$yearPlus])){
          return (object)array('year'=>$yearPlus, 'value'=> $source[$yearPlus]);
          break;
        }
        if(isset($source[$yearMinus])){
          return (object)array('year'=>$yearMinus, 'value'=> $source[$yearMinus]);
          break;
        }

        $i++;
      }
      return false;

    }else{
      throw new Exception('No country selected');
    }
  }

  /**
   * Returns seleced countrys value for $year
   *
   * @access public
   * @param    string
   * @throws   Exception
   */
  public function getValue($year='')
  {
    if($this->country!==null){
      $source = $this->data[$this->country];
      if(isset($source[$year])){
        return $source[$year];
      }else{
        return NULL;
      }
    }else{
      throw new Exception('No country selected');
    }
  }

  /**
   * Returns the latest year for this document
   *
   * @access public
   * @return    string   the year
   * @throws    Exception
   */
  public function getRecentlyYear($value='')
  {
      $source = $this->data['Sweden'];
      $last = 0;
      foreach($source as $year=>$val){
        $last = $year;
      }
      return $last;
  }


  /**
   * Selects a country
   *
   * @access public
   * @param    string   the name of the country
   * @throws    Exception
   */
  public function country($country='')
  {
      if(isset($this->data[$country])){
        $this->country = $country;
        return $this;
      }else{
        throw new Exception('Country `'.$country.'`dosent exists');
      }

  }

  /**
   * Returns the most recenly data for seleced country
   *
   * @access public
   * @return    array   Array containg year and value
   * @throws    Exception
   */
  public function getRecently()
  {
    if($this->country!==null){
      $source = $this->data[$this->country];
      $lastValue = null;
      foreach ($source as $year=>$value) {
        if($value!==NULL){
          $lastValue = (object)array(
              'year'  => $year,
              'value' => $value
          );
        }
      }
      return $lastValue;

    }else{
      throw new Exception('No country selected');
    }
  }

  /**
   * Returns the mean value of the last $years
   *
   * @access public
   * @return    float   Array containg year and value
   * @param    int   the last number of data points
   * @throws    Exception
   */
  public function getMeanValue($years=5){

    $values = $this->lastValues($years);
    $sum = 0;
    $valuesWithNumbers=0;
    foreach($values as $val){
      if($val['value']!==NULL){
        $sum = $sum + $val['value'];
        $valuesWithNumbers++;
      }
    }
    return ($valuesWithNumbers!==0)? $sum/$valuesWithNumbers : null;
  }

  public function lastValues($limit='')
  {
    if($this->country!==null){
      $source = $this->data[$this->country];
      $queue = array();
      foreach ($source as $year=>$value) {
        array_push($queue,array('year'=>$year,'value'=>$value));
        if(count($queue)>$limit){
          array_shift($queue);
        }
      }
      return $queue;

    }else{
      throw new Exception('No country selected');
    }
  }

  public function show($value='')
  {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
  }

}
