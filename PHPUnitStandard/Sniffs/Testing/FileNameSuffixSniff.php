<?php

class PHPUnitStandard_Sniffs_Testing_FileNameSuffixSniff
implements PHP_CodeSniffer_Sniff {

    public $suffix = 'Test.php';

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register() {
        return array(T_OPEN_TAG);
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
        $fileName = $phpcsFile->getFileName();
        if (!preg_match("@.*$this->suffix@", $fileName)) {
            $phpcsFile->addError(
                "Test file must end with $this->suffix",
                 $stackPtr
            );
        }
    }
}

