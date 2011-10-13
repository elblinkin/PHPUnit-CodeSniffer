<?php

class PHPUnitStandard_Sniffs_Testing_UnusedProviderSniff
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

        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if (!preg_match('@provide[A-Z_].*@', $functionName)) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $commentStackPtr = 1;
        while ($commentStackPtr = 
            $phpcsFile->findNext(T_DOC_COMMENT, ++$commentStackPtr)
        ) {
            $commentLine = $tokens[$commentStackPtr]['content'];
            if (preg_match("/\*\s+@dataProvider\s+$functionName/", $commentLine)) {
                return;
            }
        }
        $phpcsFile->addError(
            'Unused provider method: %s',
            $stackPtr,
            'Found',
            array($functionName)
        ); 
    }
}

