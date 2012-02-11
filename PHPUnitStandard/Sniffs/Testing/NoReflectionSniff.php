<?php
class PHPUnitStandard_Sniffs_Testing_NoReflectionSniff implements PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return array(T_NEW);
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $className = $phpcsFile->findNext(
                       T_WHITESPACE, ($stackPtr + 1), NULL, TRUE
                     );

        if ($tokens[$className]['code'] === T_STRING &&
            strpos($tokens[$className]['content'], 'Reflection') === 0) {
            $phpcsFile->addError(
              'Reflection API usage found',
              $stackPtr,
              'NotAllowed',
              array()
            );
        }
    }
}
