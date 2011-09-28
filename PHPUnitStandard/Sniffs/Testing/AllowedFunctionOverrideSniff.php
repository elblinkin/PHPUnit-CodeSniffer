<?php

class PHPUnitStandard_Sniffs_Testing_AllowedFunctionOverrideSniff
implements PHP_CodeSniffer_Sniff {

    public $functionWhitelist = array(
        'PHPUnit_Extensions_Database_TestCase' => 
            array(
                'setUp',
                'tearDown',
                'closeConnection',
                'getConnection',
                'getDatabaseTester',
                'getDataSet',
                'getSetUpOperation',
                'getTearDownOperation',
                'newDatabaseTester',
                'createDefaultDBConnection',
                'createFlatXMLDataSet',
                'createXMLDataSet',
                'createMySQLXMLDataSet',
                'getOperations',
            ),
        'PHPUnit_Extensions_MultipleDatabase_TestCase' => 
            array(
                'setUp',
                'tearDown',
                'getDatabaseConfigs',
            ),
        'PHPUnit_Extensions_OutputTestCase' => 
            array(
                'setUp',
                'tearDown',
                'runTest',
            ),
        'PHPUnit_Extensions_PhptTestCase' => 
            array(
                'setUp',
                'tearDown'
            ),
        'PHPUnit_Extensions_RepeatedTestCase' => 
            array(
                'setUp',
                'tearDown'
            ),
        'PHPUnit_Framework_TestCase' => 
            array(
                'setUp',
                'tearDown'
            ),
    );

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
        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        $classStackPtr = $phpcsFile->findPrevious(T_CLASS, $stackPtr);
        $className = $phpcsFile->findExtendedClassName($classStackPtr);
        $properties = $phpcsFile->getMethodProperties($stackPtr);

        if (!in_array($className, array_keys($this->functionWhitelist))) {
            return;
        }

        $allowedFunctions = $this->functionWhitelist[$className];

        $isProtected = $properties['scope'] === 'protected';
        $isWhitelisted = in_array($functionName, $allowedFunctions);

        if ($isProtected && !$isWhitelisted) {
            $phpcsFile->addError(
                'Unexpected protected function encountered.  Not a whitelisted override: %s',
                $stackPtr,
                'Found',
                array($functionName)
            );
        } else if (!$isProtected && $isWhitelisted) {
            $phpcsFile->addWarning(
                'Expected function to be protected: %s',
                $stackPtr,
                'Found',
                array($functionName)
            );
        }
    }
}

