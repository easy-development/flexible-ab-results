<?php

abstract class FlexibleABResultsAbstractDatabase {

  public $wp_db;
  public $lastInsertID;

  final public function __construct() {
    global $wpdb;

    $this->wp_db = $wpdb;

    if(method_exists($this, 'init'))
      $this->init();
  }

  abstract public function getTableName();

  /**
   *  @param array $data
   *  @uses wrap_my_array
   *  @uses array_implode
   *  @return bool
   */
  public function insert($data){
    if(is_array($data) && !empty($data)){

      if(method_exists($this, '_beforeInsert'))
        $this->_beforeInsert($data);
      if(method_exists($this, '_beforeSave'))
        $this->_beforeSave($data);

      $keys = array_keys($data);

      $sql = 'INSERT INTO '. $this->getTableName() .' ('
          .implode("," , $this->_wrapMyArray($keys , '`'))
          .') VALUES ('
          .implode("," , $this->_wrapMyArray($data))
          .')';
      $this->wp_db->query($sql);

      $this->lastInsertID = $this->wp_db->insert_id;
      return true;
    }
    return false;
  }

  public function getMySQLInsertID() {
    return $this->lastInsertID;
  }

  /**
   *  @param array $data
   *  @param array/string $where
   *  @uses wrap_my_array
   *  @uses array_implode
   * @return bool
   */
  public function update($data = array() , $where = array()) {
    if(is_array($data) && !empty($data)){
      if(method_exists($this, '_beforeSave'))
        $this->_beforeSave($data);

      $data = $this->_wrapMyArray($data);

      $sql = 'UPDATE '. $this->getTableName() .' SET ';
      $sql .= $this->_arrayImplode("=" , "," , $data);

      if(!empty($where)){
        $sql .= ' WHERE ';
        if(is_array($where)){
          $where = $this->_wrapMyArray($where);
          $sql  .= $this->_arrayImplode("=" , "AND" , $where);
        }else{
          $sql  .= $where;
        }
      }

      $this->wp_db->query($sql);
      return true;
    }
    return false;
  }

  /**
   *  @param array/string where
   *  @uses wrap_my_array
   *  @uses array_implode
   */
  public function delete($where = array()){
    $sql = 'DELETE FROM ' . $this->getTableName() .' ';

    if(!empty($where)){
      $sql .= ' WHERE ';
      if(is_array($where)){
        $where = $this->_wrapMyArray($where);
        $sql  .= $this->_arrayImplode("=" , "AND" , $where);
      }else{
        $sql  .= $where;
      }
    }

    $this->wp_db->query($sql);
  }

  /**
   *  Wrap my array
   *  @param array the array you want to wrap
   *  @param string wrapper , default double-quotes(")
   *  @return an array with wrapped strings
   */
  private function _wrapMyArray($array , $wrapper = '"') {
    $new_array = array();
    foreach($array as $k=>$element){
      if(!is_array($element)){
        $new_array[$k] = $wrapper . $element . $wrapper;
      }
    }
    return $new_array;

  }
  /**
   * Implode an array with the key and value pair giving
   * a glue, a separator between pairs and the array
   * to implode.
   * @param string $glue The glue between key and value
   * @param string $separator Separator between pairs
   * @param array $array The array to implode
   * @return string The imploded array
   */
  private function _arrayImplode( $glue, $separator, $array ) {
    if ( ! is_array( $array ) ) return $array;
    $string = array();
    foreach ( $array as $key => $val ) {
      if ( is_array( $val ) )
        $val = implode( ',', $val );
      $string[] = "{$key}{$glue}{$val}";

    }
    return implode( $separator, $string );
  }

}