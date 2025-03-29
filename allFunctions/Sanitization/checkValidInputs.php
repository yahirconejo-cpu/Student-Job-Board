<?php
    function sanitizeString($str) {
        // Use a regular expression to check for only letters (both cases) and numbers
        return preg_match('/^[a-zA-Z0-9]+$/', $str) === 1;
    }

    function sanitizePW($str) {
        return preg_match('/^[a-zA-Z0-9\!\?\#\^\*]+$/', $str) === 1;
    }

    function sanitizeDescription($str){
        return preg_match_all('/[^a-zA-Z0-9.?_%+-:, ]/', $str) == 0;
    }