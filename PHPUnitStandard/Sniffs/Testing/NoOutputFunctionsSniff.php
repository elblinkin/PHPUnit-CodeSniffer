<?php
class PHPUnitStandard_Sniffs_Testing_NoOutputFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{
    protected $forbiddenFunctions = array(
      'printf' => NULL,
      'print_r' => NULL,
      'var_dump' => NULL,
    );
}
