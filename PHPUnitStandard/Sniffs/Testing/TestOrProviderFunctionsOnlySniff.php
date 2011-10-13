<?php

class PHPUnitStandard_Sniffs_Testing_TestOrProviderFunctionsOnlySniff
implements PHP_CodeSniffer_Sniff {

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register() {
        return array(
            T_FUNCTION,
        );
    }

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $classStackPtr = $phpcsFile->findPrevious(T_CLASS, $stackPtr);
        $className = $phpcsFile->getDeclarationName($classStackPtr);
        $properties = $phpcsFile->getMethodProperties($stackPtr);
        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if (!in_array($properties['scope'], array('private', 'protected'))) {
            if (preg_match('@(test|provide)[A-Z_].*@', $functionName)) {
                return;
            }
            $phpcsFile->addError(
                'Found a public method that is not a test or a provider: %s',
                $stackPtr,
                'Found',
                array($functionName)
            );
        }
    }
}

