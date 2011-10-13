<?php

class PHPUnitStandard_Sniffs_Testing_ClassNameSniff
implements PHP_CodeSniffer_Sniff {

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register() {
        return array(
            T_OPEN_TAG,
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
        $origStackPtr = $stackPtr;

        $filename = $phpcsFile->getFileName();
        if(!preg_match("@.*/tests/phpunit/(.*).php@", $filename, $matches)) {
            $phpcsFile->addWarning(
                'File path does not match expected convention.',
                $stackPtr
            );
            return;
        }
        
        $expected = str_replace("/", "_", $matches[1]);

        $found = array();
        while ($stackPtr = $phpcsFile->findNext(T_CLASS, ++$stackPtr)) {
            $className = $phpcsFile->getDeclarationName($stackPtr);
            if ($className == $expected) {
                return; // SUCCESS
            }
            $found[] = $className;
        }
        $classNames = '[NONE]';
        if (!empty($found)) {
            $classNames = implode(', ', $found);
        }
        $phpcsFile->addError(
            'File must contain a class named: %s; found %s',
            $origStackPtr,
            'Found',
            array($expected, $classNames)
        );
    }
}

