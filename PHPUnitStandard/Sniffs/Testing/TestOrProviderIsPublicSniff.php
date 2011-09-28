<?php

class PHPUnitStandard_Sniffs_Testing_TestOrProviderIsPublicSniff
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
        $filename = $phpcsFile->getFileName();
        preg_match("@.*/tests/phpunit/(.*).php@", $filename, $matches);
        $expected = str_replace("/", "_", $matches[1]);

        $classStackPtr = $phpcsFile->findPrevious(T_CLASS, $stackPtr);
        $className = $phpcsFile->getDeclarationName($classStackPtr);
        if ($className != $expected) {
            return;
        }

        $properties = $phpcsFile->getMethodProperties($stackPtr);
        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if (in_array($properties['scope'], array('private', 'protected'))) {
            if (!preg_match('@(test|provider)[A-Z_].*@', $functionName)) {
                return;
            }
            $phpcsFile->addWarning(
                'Found non-public test or provider function: %s',
                $stackPtr,
                'Found',
                array($functionName)
            );
        }
    }
}

