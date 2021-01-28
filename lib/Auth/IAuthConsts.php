<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

/**
 * настроечные константы модуля авторизации
 */
interface IAuthConsts {

    /**
     * срок валидности токена авторизации
     */
    const LIFETIME = 60 * 60 * 24 * 3; //3 пня
    
    /**
     * Значение бесконечного токена
     */
    const LIFETIME_UNSPECIFIED = -1;
    /**
     * срок валидности кешированных данных пользователя
     */
    const REV_TIME = 60 * 30; // полчаса
    
    /** ключ UserInfo в сессии */
    const session_user_info_marker = "ub1296bd734b84d8ca887f5edd16a4713";

    /** field where auth token transfers (cookie or header) */
    const auth_token_field_name = "x-auth-token";
    
    const auth_token_devuid_field = "x-dev-uid";
    


}
