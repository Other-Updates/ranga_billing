				<table class="table table-striped table-bordered responsive dataTable no-footer dtr-inline search_table" style="display:none;">
                	<tr>
                    	<td width="10%">RP #</td>
                        <td>
                        	<?php 
								if($search_data['rp_no']!='')
									echo $search_data['rp_no'];
								else
									echo "-";	
							?>
                        </td>
                        <td  width="10%">Customer</td>
                        <td>
                        	<?php 
								if($search_data['customer']!='')
								{
									$this->load->model('customer/customer_model');
									$name=$this->customer_model->get_customer_by_id($search_data['customer']);
									echo $name[0]['store_name'];
								}
								else
									echo "-";	
							?>
                        </td>
                        <td  width="15%">From Date</td>
                        <td>
                        	<?php 
								if($search_data['from_date']!='')
									echo $search_data['from_date'];
								else
									echo "-";	
							?>
                        </td>
                        <td  width="10%">To Date</td>
                        <td>
                        	<?php 
								if($search_data['to_date']!='')
									echo $search_data['to_date'];
								else
									echo "-";	
							?>
                        </td>
                    </tr>
                </table>
                  <table class="table table-striped table-bordered responsive dataTable no-footer dtr-inline">
                  	<thead>
                    	<tr>
                        	<td>S.No</td>
                        	<td>RP #</td>
                            <td>Customer Name</td>
                            <td>Due Date</td>
                            <td>Invoice Amount</td>
                            <td>Discount</td>
                            <td>Paid Amount</td>
                            <td>Balance</td>
                            <td>Payment Status</td>
                            <?php 
							if(empty($search_data))
							{
							?>
                            <td class="hide_class">Action</td>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php
							$paid=$bal=0;
                        	if(isset($all_receipt) && !empty($all_receipt))
							{
								$i=1;
								//echo "<pre>";
							//	print_r($all_receipt);
								foreach($all_receipt as $val)
								{
									$paid=$paid+$val['receipt_bill'][0]['receipt_paid'];
									$bal=$bal+($val['total_amount']-$val['receipt_bill'][0]['receipt_balance'])-$val['receipt_bill'][0]['receipt_paid'];
									?>
                                    <tr>
                                    <td class='first_td'><?=$i?></td>
                                    <td><?=$val['receipt_no']?></td>	
                                    <td><?=$val['store_name']?></td>
                                    <td><?=date('d-M-Y',strtotime($val['due_date']))?></td>
                                    <td class="text_right"><?=number_format($val['total_amount'], 2, '.', ',')?></td>
                                    <td class="text_right"><?=number_format($val['receipt_bill'][0]['receipt_balance'], 2, '.', ',')?></td>
                                    <td class="text_right"><?=number_format($val['receipt_bill'][0]['receipt_paid'], 2, '.', ',')?></td>
                                    <td class="text_right">
									<?php 
											
											$b=(round($val['total_amount'],2)-round($val['receipt_bill'][0]['receipt_balance'],2))-round($val['receipt_bill'][0]['receipt_paid'],2);
											if($b==0 || $b<0)
											{
												echo "0.00";
											}
											else
											{
												echo number_format($b, 2, '.', ',');
											}
											
											
										?>
                                    </td>
                                    <td>
										<?php 
										if($val['complete_status']==0)
										{
											echo '<a href="#" data-toggle="modal" class="tooltips ahref" title="In-Complete"><span class="fa fa-thumbs-down blue">&nbsp;</span></a>';
										}
										else
										{
											echo '<a href="#" data-toggle="modal" class="tooltips ahref" title="Complete"><span class="fa fa-thumbs-up green">&nbsp;</span></a>';
										}
										
										?>
                                   </td>
									<?php 
									if(empty($search_data))
									{
									?>
									<td  class="hide_class">
                                      <a href="<?php echo $this->config->item('base_url').'sales_receipt/view_receipt/'.$val['id']?>" data-toggle="tooltip" class="fa fa-eye tooltips" title="" data-original-title="View">&nbsp;</a>
                                    <?php 
									if($val['complete_status']==0)
									{
									?>
                                    	<a href="<?php echo $this->config->item('base_url').'sales_receipt/update_receipt/'.$val['id']?>" data-toggle="tooltip" class="fa fa-edit tooltips" title="" data-original-title="Edit">&nbsp;</a>
                                        <?php }?>
                                      
                                    </td>
                                    <?php }?>
                                    </tr>
                                    <?php
									$i++;
								}
								?>
                                <tfoot>
                                	<tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text_right"><?=number_format($paid, 2, '.', ',')?></td>
                                        <td class="text_right"><?=number_format($bal, 2, '.', ',')?></td>
                                        <td></td>
                                        <td class="hide_class"></td>
                                    </tr>
                                </tfoot>
                                <?php
							}
							else
							{
								?>
                                <tr><td colspan="9">No data found...</td></tr>
                                <?php
							}
						?>
                    </tbody>
                  </table>
                 