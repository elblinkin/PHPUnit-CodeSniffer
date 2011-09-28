<?php

class PHPUnitStandard_Sniffs_Testing_ExtraneousClassSniff
implements PHP_CodeSniffer_Sniff {

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
        $filename = $phpcsFile->getFileName();
        preg_match("@.*/tests/phpunit/(.*).php@", $filename, $matches);
        $expected = str_replace("/", "_", $matches[1]);

        $declarationName = $phpcsFile->getDeclarationName($stackPtr);
        if ($declarationName != $expected) {
            $phpcsFile->addError(
                 'Unexpected extraneous class found: %s.  Expected: %s',
                 $stackPtr,
                 'Found',
                  array($declarationName, $expected)
            );
        }
    }
}

