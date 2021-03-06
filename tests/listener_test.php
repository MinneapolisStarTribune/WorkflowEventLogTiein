<?php
/**
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package WorkflowEventLogTiein
 * @subpackage Tests
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

require_once 'case.php';

/**
 * Search for the dependent package's test loader. If we are in the vendor 
 * directory, it is in a sister directory. If we are the main package, it is in 
 * the subordinate vendor/ directory. 
 */
$target = "workflow/tests";
$parent = dirname(__DIR__);
$grandparent = dirname($parent);
$dir = "$parent/vendor/zetacomponents/$target";
if(!is_dir($dir)) {
    $dir = "$grandparent/$target";
}
require_once $dir.'/execution.php';

/**
 * @package WorkflowEventLogTiein
 * @subpackage Tests
 */
class ezcWorkflowEventLogTieinListenerTest extends ezcWorkflowEventLogTieinTestCase
{
    protected $execution;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite(
          'ezcWorkflowEventLogTieinListenerTest'
        );
    }

    public function testLogStartEnd()
    {
        $this->setUpStartEnd();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'StartEnd' ),
          $this->readActual()
        );
    }

    public function testLogStartEndVariableHandler()
    {
        $this->setUpStartEndVariableHandler();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'StartEndVariableHandler' ),
          $this->readActual()
        );
    }

    public function testLogStartInputEnd()
    {
        $this->setUpStartInputEnd();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $id = $this->execution->start();
        $this->setUpExecution( $id );
        $this->execution->resume( array( 'variable' => 'value' ) );

        $this->assertEquals(
          $this->readExpected( 'StartInputEnd' ),
          $this->readActual()
        );
    }

    public function testLogStartSetUnsetEnd()
    {
        $this->setUpStartSetUnsetEnd();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'StartSetUnsetEnd' ),
          $this->readActual()
        );
    }

    public function testLogIncrementingLoop()
    {
        $this->markTestIncomplete("Fails in workflow, Illegal string offset 'operand'");
        $this->setUpLoop( 'increment' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'IncrementingLoop' ),
          $this->readActual()
        );
    }

    public function testLogDecrementingLoop()
    {
        $this->markTestIncomplete("Fails in workflow, Illegal string offset 'operand'");
        $this->setUpLoop( 'decrement' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'DecrementingLoop' ),
          $this->readActual()
        );
    }

    public function testLogSetAddSubMulDiv()
    {
        $this->setUpSetAddSubMulDiv();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'SetAddSubMulDiv' ),
          $this->readActual()
        );
    }

    public function testLogAddVariables()
    {
        $this->setUpAddVariables();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'AddVariables' ),
          $this->readActual()
        );
    }

    public function testLogParallelSplitSynchronization()
    {
        $this->setUpParallelSplitSynchronization();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitSynchronization' ),
          $this->readActual()
        );
    }

    public function testLogParallelSplitSynchronization2()
    {
        $this->setUpParallelSplitSynchronization2();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();
        $this->execution->resume( array( 'foo' => 'bar' ) );
        $this->execution->resume( array( 'bar' => 'foo' ) );

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitSynchronization2' ),
          $this->readActual()
        );
    }

    public function testLogExclusiveChoiceSimpleMerge()
    {
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'ExclusiveChoiceSimpleMerge' ),
          $this->readActual()
        );
    }

    public function testLogExclusiveChoiceSimpleMerge2()
    {
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => false ) );
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'ExclusiveChoiceSimpleMerge2' ),
          $this->readActual()
        );
    }

    public function testLogExclusiveChoiceWithUnconditionalOutNodeSimpleMerge()
    {
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => false ) );
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge' ),
          $this->readActual()
        );
    }

    public function testLogExclusiveChoiceWithUnconditionalOutNodeSimpleMerge2()
    {
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge2' ),
          $this->readActual()
        );
    }

    public function testLogNestedExclusiveChoiceSimpleMerge()
    {
        $this->setUpNestedExclusiveChoiceSimpleMerge();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'NestedExclusiveChoiceSimpleMerge' ),
          $this->readActual()
        );
    }

    public function testLogNestedExclusiveChoiceSimpleMerge2()
    {
        $this->setUpNestedExclusiveChoiceSimpleMerge( true, false );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'NestedExclusiveChoiceSimpleMerge2' ),
          $this->readActual()
        );
    }

    public function testLogNestedExclusiveChoiceSimpleMerge3()
    {
        $this->setUpNestedExclusiveChoiceSimpleMerge( false );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'NestedExclusiveChoiceSimpleMerge3' ),
          $this->readActual()
        );
    }

    public function testLogMultiChoiceSynchronizingMerge()
    {
        $this->setUpMultiChoice( 'SynchronizingMerge' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'MultiChoiceSynchronizingMerge' ),
          $this->readActual()
        );
    }

    public function testLogMultiChoiceDiscriminator()
    {
        $this->setUpMultiChoice( 'Discriminator' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'MultiChoiceDiscriminator' ),
          $this->readActual()
        );
    }

    public function testLogNonInteractiveSubWorkflow()
    {
        $this->markTestIncomplete("Comparison fails on node numbers because node numbers are now database node_ids");
        $this->setUpStartEnd();
        $this->dbStorage->save( $this->workflow );
        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'NonInteractiveSubWorkflow' ),
          $this->readActual()
        );
    }

    public function testLogInteractiveSubWorkflow()
    {
        $this->markTestIncomplete("Comparison fails on node numbers because node numbers are now database node_ids");
        $this->setUpStartInputEnd();
        $this->dbStorage->save( $this->workflow );
        $this->setUpWorkflowWithSubWorkflow( 'StartInputEnd' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $id = $this->execution->start();
        $this->setUpExecution( $id );
        $this->execution->resume( array( 'variable' => 'value' ) );

        $this->assertEquals(
          $this->readExpected( 'InteractiveSubWorkflow' ),
          $this->readActual()
        );
    }

    public function testLogWorkflowWithSubWorkflowAndVariablePassing()
    {
        $this->markTestIncomplete("Fails in workflow, Illegal string offset 'operand'");
        $definition = new ezcWorkflowDefinitionStorageXml(
          dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'Workflow' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR
        );

        $workflow = $definition->loadByName( 'IncrementVariable' );
        $this->dbStorage->save( $workflow );

        $this->setUpWorkflowWithSubWorkflowAndVariablePassing();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'WorkflowWithSubWorkflowAndVariablePassing' ),
          $this->readActual()
        );
    }

    public function testLogWorkflowWithCancelCaseSubWorkflow()
    {
        $this->markTestIncomplete("Comparison fails on node numbers because node numbers are now database node_ids");
        $this->setUpCancelCase( 'last' );
        $this->dbStorage->save( $this->workflow );
        $this->setUpWorkflowWithSubWorkflow( 'ParallelSplitActionActionCancelCaseSynchronization' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'WorkflowWithCancelCaseSubWorkflow' ),
          $this->readActual()
        );
    }

    public function testLogNestedLoops()
    {
        $this->markTestIncomplete("Fails in workflow, Illegal string offset 'operand'");
        $this->setUpNestedLoops();
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'NestedLoops' ),
          $this->readActual()
        );
    }

    public function testLogParallelSplitCancelCaseActionActionSynchronization()
    {
        $this->setUpCancelCase( 'first' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitCancelCaseActionActionSynchronization' ),
          $this->readActual()
        );
    }

    public function testLogParallelSplitActionActionCancelCaseSynchronization()
    {
        $this->setUpCancelCase( 'last' );
        $this->dbStorage->save( $this->workflow );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertEquals(
          $this->readExpected( 'ParallelSplitActionActionCancelCaseSynchronization' ),
          $this->readActual()
        );
    }
}
?>
