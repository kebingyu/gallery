<div id="rateSearchForm">
	<script type="text/javascript"
		src="http://www.loanapp.com/jslib/string.js"></script>
	<script type="text/javascript"
		src="http://www.loanapp.com/jslib/formValidateRevise.js"></script>
	<script type="text/javascript"
		src="http://www.loanapp.com/jslib/loanAppUtils.js"></script>
	<script type="text/javascript">
        aMandatory["wState"] = new Array("State", "Select", "");
        aMandatory["wLoanProgram"] = new Array("Loan Program", "Select", "");
        aMandatory["wAmount"] = new Array("Amount", "Text", "");
    </script>
	<form name="rateWidget" id="fmRateWidget"
		onsubmit="doRateWidget(this); return false;">
		<input type="hidden" id="wPoints" name="wPoints" value="">
		<div id="formBody">
			<h1>Today's Mortgage Rates</h1>
			<div id="poweredby">
				Powered by<a href="http://www.loan.com" rel="nofollow"><img
					src="http://www.loan.com/images/poweredby_loancom.gif" border="0" />Loan.com</a>
			</div>
			<br class="cleaner" />
			<div id="formElements">
				<div class="frmCol1">See local mortgage rates</div>
				<div class="frmCol2">
					Loan Purpose<br /> <select id="wLoanPurpose" name="wLoanPurpose"
						onchange="changeLoanType(this,'wLoanProgram')">
						<option value="purch">Purchase</option>
						<option value="refi">Refinance</option>
						<option value="equity">Home Equity</option>
					</select>
				</div>
				<div class="frmCol3">
					State<br /> <select id="wState" name="wState">
						<option value="">-- Select --</option>
						<?php 
						$html = '';
						//skip this loop if currentState is set
						if($currentState = $this->currentState){
							foreach ($states as $s){
								$shortName = $s->getShortName();
								if(strcasecmp($currentState, $shortName)==0){
									$html .= '<option selected="selected" value="'.$s->getShortName().'">'.$s->getFullName().'</option>';
								}else{
									$html .= '<option value="'.$s->getShortName().'">'.$s->getFullName().'</option>';
								}
							}
						}else{
							foreach ($states as $s){
								$html .= '<option value="'.$s->getShortName().'">'.$s->getFullName().'</option>';
							}
						}
						echo $html;
						?>
					</select>
				</div>
				<br class="cleaner" />
			</div>
			<div id="formElements2">
				<div class="frmCol2">
					Loan Program<br /> <select id="wLoanProgram" NAME="wLoanProgram">
						<option value="">-- Select --</option>
						<option value="1">30 Year Fixed</option>
						<option value="2">20 Year Fixed</option>
						<option value="3">15 Year Fixed</option>
						<option value="4">3/1 ARM</option>
						<option value="5">5/1 ARM</option>
						<option value="6">7/1 ARM</option>
						<option value="7">10/1 ARM</option>
						<option value="8">FHA 30 Year Fixed</option>
						<option value="9">FHA 15 Year Fixed</option>
						<option value="11">VA 30 Year Fixed</option>
						<option value="12">VA 15 Year Fixed</option>
						<option value="16">10 Year Fixed</option>
					</select>
				</div>
				<div class="frmCol3">
					Amount<br /> <input type="text" id="wAmount" name="wAmount"
						value="$200,000" onkeyup="formatLoanAmount(this)" />
				</div>
				<br class="cleaner" />
			</div>
		</div>
		<div id="formElements3">
			<input type="submit" id="getRate" name="getRate"
				value="GET RATES NOW" /> <a class="search_guide"
				href="/search/guide" target="_blank">Guide</a>
		</div>
	</form>
	<br class="cleaner" />
</div>
