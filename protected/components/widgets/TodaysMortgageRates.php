<?php
class TodaysMortgageRates extends CWidget {

	
	public $currentState;
	public $currentProgram;
	public $currentPurpose;
	public $currentAmount;
	
	//blank or null parameters will return default
	public function run() {
		$stateMdl = StatesCachedModel::getInstance();
		$states = $stateMdl->getStatesRecord();
		$amount = (is_numeric($amount))?$amount:'';
		$this->render('todaysMortgageRates', array(
				'states' => $states
		));
	}
}