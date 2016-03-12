<?php setlocale(LC_MONETARY,"en_IN.UTF-8"); ?>
<style type="text/css">
	table td {
		text-align: center;
	}
</style>
<div class="container">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th></th>
				<th>New Leads</th>			
				<th>CRM Dispositions</th>			
				<th>Hot Pipelines</th>
				<th>Hot Pipelines Created</th>
				<th>Conversions</th>
				<th>Amount</th>
				<th>Reference Sourced</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Today</th>
				<td>
					{{Lead::leadCount()}}
				</td>				
				<td>
					{{CallDisposition::getCount()}}
				</td>						
				<td>
					{{CallDisposition::getHotCount()}}
				</td>
				<td>
					
				</td>
				<td>
					{{Fee::conversionCount()}}
				</td>
				<td>					
					₹ {{money_format('%!i', Fee::amount())}}
				</td>
				<td>
					{{LeadSource::referenceCount(NULL)}}
				</td>
			</tr>
			<tr>
				<th>Yesterday</th>
				<td>
					{{Lead::leadCount(date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
				</td>				
				<td>
					{{CallDisposition::getCount(NULL, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
				</td>						
				<td>
					{{CallDisposition::getHotCount(NULL, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
				</td>
				<td></td>
				<td>
					{{Fee::conversionCount(NULL, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
				</td>
				<td>					
					₹ {{money_format('%!i', Fee::amount(NULL, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days"))))}}
				</td>
				<td>
					{{LeadSource::referenceCount(NULL, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
				</td>
			</tr>
			<tr>
				<th>1 Week</th>
				<td>
					{{Lead::leadCount(date('Y/m/d 0:0:0', strtotime("-8 days"))) }}
				</td>				
				<td>
					{{CallDisposition::getCount(NULL, date('Y/m/d 0:0:0', strtotime("-8 days")))}}
				</td>						
				<td>
					{{CallDisposition::getHotCount(NULL, date('Y/m/d 0:0:0',strtotime("-8 days")))}}
				</td>
				<td></td>
				<td>
					{{Fee::conversionCount(NULL, date('Y/m/d 0:0:0',strtotime("-8 days")))}}
				</td>
				<td>					
					₹ {{money_format('%!i', Fee::amount(NULL, date('Y/m/d 0:0:0', strtotime("-8 days"))))}}
				</td>
				<td>
					{{LeadSource::referenceCount(NULL, date('Y/m/d 0:0:0',strtotime("-8 days")))}}
				</td>
			</tr>
			<tr>
				<th>MTD</th>
				<td>
					{{Lead::leadCount(date('Y/m/01 0:0:0'))}}
				</td>				
				<td>
					{{CallDisposition::getCount(NULL, date('Y/m/01 0:0:0'))}}
				</td>						
				<td>
					{{CallDisposition::getHotCount(NULL, date("Y-m-01 0:0:0"))}}
				</td>
				<td></td>
				<td>
					{{Fee::conversionCount(NULL, date("Y-m-01 0:0:0"))}}
				</td>
				<td>					
					₹ {{money_format('%!i', Fee::amount(NULL, date("Y-m-01 0:0:0")))}}
				</td>
				<td>
					{{LeadSource::referenceCount(NULL, date("Y-m-01 0:0:0"))}}
				</td>
			</tr>			
			<tr>
				<th>Trending</th>
				<td>
					{{round(Lead::leadCount(date('Y/m/01 0:0:0'))*date("t")/date('d'))}}
				</td>				
				<td>
					{{round(CallDisposition::getCount(NULL, date('Y/m/01 0:0:0'))*date("t")/date('d'))}}
				</td>						
				<td>
					{{round(CallDisposition::getHotCount(NULL, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
				</td>
				<td></td>
				<td>
					{{round(Fee::conversionCount(NULL, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
				</td>
				<td>					
					₹ {{money_format('%!i', Fee::amount(NULL, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
				</td>
				<td>
					{{round(LeadSource::referenceCount(NULL, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
				</td>
			</tr>
			<tr>
				<th>Previous Month</th>
				<td>
					{{Lead::leadCount(date("Y-m-01 0:0:0", strtotime("-1 months"))), date("Y-m-t 23:59:59", strtotime("-1 months"))}}
				</td>				
				<td>
					{{CallDisposition::getCount(NULL, date("Y-m-01 0:0:0", strtotime("-1 months")), date("Y-m-t 23:59:59", strtotime("-1 months")))}}
				</td>						
				<td>
					{{CallDisposition::getHotCount(NULL, date("Y-m-01 0:0:0", strtotime("-1 months")), date("Y-m-t 23:59:59", strtotime("-1 months")))}}
				</td>
				<td></td>
				<td>
					{{Fee::conversionCount(NULL, date("Y-m-01 0:0:0", strtotime("-1 months")), date("Y-m-t 23:59:59", strtotime("-1 months")))}}				
				</td>
				<td>					
					₹ {{money_format('%!i', Fee::amount(NULL, date("Y-m-01 0:0:0", strtotime("-1 months")), date("Y-m-t 23:59:59", strtotime("-1 months"))))}}
				</td>
				<td>
					{{LeadSource::referenceCount(NULL, date("Y-m-01 0:0:0", strtotime("-1 months")), date("Y-m-t 23:59:59", strtotime("-1 months")))}}
				</td>
			</tr>
		</tbody>	
	</table>
</div>