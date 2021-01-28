<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers;

/**
 * Description of HttpCodeVoc
 *
 * @author eve
 */
class HttpCodeVoc {

    //put your code here


    protected static $states = [
        "C100" => "Continue",
        "C101" => "Switching Protocols",
        "C102" => "Processing",
        "C200" => "OK",
        "C201" => "Created",
        "C202" => "Accepted",
        "C203" => "Non-Authoritative Information",
        "C204" => "No Content",
        "C205" => "Reset Content",
        "C206" => "Partial Content",
        "C207" => "Multi-Status",
        "C208" => "Already Reported",
        "C226" => "IM Used",
        "C300" => "Multiple Choices",
        "C301" => "Moved Permanently",
        "C302" => "Moved Temporarily",
        "C302" => "Found",
        "C303" => "See Other",
        "C304" => "Not Modified",
        "C305" => "Use Proxy",
        "C307" => "Temporary Redirect",
        "C308" => "Permanent Redirect",
        "C400" => "Bad Request",
        "C401" => "Unauthorized",
        "C402" => "Payment Required",
        "C403" => "Forbidden",
        "C404" => "Not Found",
        "C405" => "Method Not Allowed",
        "C406" => "Not Acceptable",
        "C407" => "Proxy Authentication Required",
        "C408" => "Request Timeout",
        "C409" => "Conflict",
        "C410" => "Gone",
        "C411" => "Length Required",
        "C412" => "Precondition Failed",
        "C413" => "Payload Too Large",
        "C414" => "URI Too Long",
        "C415" => "Unsupported Media Type",
        "C416" => "Range Not Satisfiable",
        "C417" => "Expectation Failed",
        "C418" => "I’m a teapot",
        "C419" => "Authentication Timeout",
        "C421" => "Misdirected Request",
        "C422" => "Unprocessable Entity",
        "C423" => "Locked",
        "C424" => "Failed Dependency",
        "C426" => "Upgrade Required",
        "C428" => "Precondition Required",
        "C429" => "Too Many Requests",
        "C431" => "Request Header Fields Too Large",
        "C449" => "Retry With",
        "C451" => "Unavailable For Legal Reasons",
        "C499" => "Client Closed Request",
        "C500" => "Internal Server Error",
        "C501" => "Not Implemented",
        "C502" => "Bad Gateway",
        "C503" => "Service Unavailable",
        "C504" => "Gateway Timeout",
        "C505" => "HTTP Version Not Supported",
        "C506" => "Variant Also Negotiates",
        "C507" => "Insufficient Storage",
        "C508" => "Loop Detected",
        "C509" => "Bandwidth Limit Exceeded",
        "C510" => "Not Extended",
        "C511" => "Network Authentication Required",
        "C520" => "Unknown Error",
        "C521" => "Web Server Is Down",
        "C522" => "Connection Timed Out",
        "C523" => "Origin Is Unreachable",
        "C524" => "A Timeout Occurred",
        "C525" => "SSL Handshake Failed",
        "C526" => "Invalid SSL Certificate",
    ];

    public static function get(int $code) {
        $key = "C{$code}";
        return array_key_exists($key, static::$states) ? static::$states[$key] : "unknown state";
    }

}
