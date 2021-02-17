<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DB\errors;

class MySQLError extends MYSQLEWarn {

    CONST COMMAND = "SHOW ERRORS LIMIT 1;";

}
