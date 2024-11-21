<?php
// Table-specific class - extends the data_operations class to apply to a specific database table.

class logon extends data_operations {

  // Constructor - must have same name as class.
  function logon() {

    $table = LOGON_TABLE;              // Constant defined in init.php
    $id_field = 'logon_token';               // Primary Key field
    $id_field_is_ai = false;             // Is Primary Key Auto Increment?
    $fields = array(                    // Array of all non-PK fields
      'logon_users_id',
      'logon_time',
      'logon_last',
      'logon_ip'
    );

    // Parent class constructor
    // Sending it table-specific information enables this class to generate Active Record objects.
    parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
  }


  //////////////////////////////////////////////////////////////////////////////////////////////
} //end class

?>
