<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Helpers;

/**
 * Description of CSRFError
 *
 * @author eve
 */
class CSRFError extends \Errors\common_error {

    //put your code here
    const MESSAGE = "CSRF token does not match!";

}
