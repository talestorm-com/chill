<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

class AuthError extends \Errors\common_error {
    const NOT_AUTHORIZED = "not authorized";
    const ACCESS_DENIED = "access denied";
}
