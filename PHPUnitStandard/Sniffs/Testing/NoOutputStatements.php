<?php
class PHPUnitStandard_Sniffs_Testing_NoOutputStatements implements PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return array(T_ECHO);
        return array(T_PRINT);
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addError(
          'The use of the "echo" and "print" keywords is forbidden in tests',
          $stackPtr,
          'NotAllowed',
          array()
        );
    }
}
