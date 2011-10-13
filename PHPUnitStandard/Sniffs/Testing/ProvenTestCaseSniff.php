<?php

class PHPUnitStandard_Sniffs_Testing_ProvenTestCaseSniff
implements PHP_CodeSniffer_Sniff {

    public $classNameWhitelist = array(
        'PHPUnit_Extensions_Database_TestCase',
        'PHPUnit_Extensions_MultipleDatabase_TestCase',
        'PHPUnit_Extensions_OutputTestCase',
        'PHPUnit_Extensions_PhptTestCase',
        'PHPUnit_Extensions_RepeatedTest',
        'PHPUnit_Framework_TestCase',
    );

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register() {
        return array(
            T_CLASS,
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
        $declarationName = $phpcsFile->getDeclarationName($stackPtr);
        $extendedClassName = $phpcsFile->findExtendedClassName($stackPtr);
        if (!($extendedClassName
            && in_array($extendedClassName, $this->classNameWhitelist))
        ) {
            $phpcsFile->addError(
                'Must extend a proven PHPUnit_Framework_TestCase class; found %s.',
                $stackPtr,
                'Found',
                array($extendedClassName)
            );
        }
    }
}

