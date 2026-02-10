<?php

namespace App\enums;

class ErrorCode extends PhpEnum {

    const success = 200; // success response
    const error = 500; // error explained in content string
    const token_not_found = 2; // 401 error: auth token not sent
    const login_required = 3; // login required because wrong token or expired
    const account_not_verified = 4; // account needs verification
    const other = 5; //for later



}
