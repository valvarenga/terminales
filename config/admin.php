<?php

return [
    /*
     * Administrative access is deliberately configured through environment
     * variables so no usable credential can be committed with the code.
     */
    'username' => env('ADMIN_USERNAME'),
    'password_hash' => env('ADMIN_PASSWORD_HASH'),
];
