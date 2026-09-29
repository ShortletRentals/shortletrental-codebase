              
						<table  border="0" cellspacing="0" cellpadding="0" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;width:100%;color:#2c333c;border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px" width="100%">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td colspan="2" align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-top:20px;padding-bottom:0px" valign="inherit">
                                    <h1 style="font-size: 18px; margin: 0;     color: #ef6d3b;">
                                       PAYMENT SCHEDULE
                                    </h1>
                                 </td>
                              </tr>
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit;color:#2c333c" valign="inherit">
                                 <td colspan="2" style="display:table-cell;vertical-align:inherit;border:none" valign="inherit">
                                    <table border="0" cellspacing="0" cellpadding="0" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray;width:100%;color:#2c333c;padding:0px;margin:0px" width="100%">
                                       <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td style="display:table-cell;vertical-align:inherit;border:none;font-size:18px;text-align:center;padding:0px" align="center" valign="inherit">
                                               
                                             </td>
                                             <td rowspan="3" align="left" style="display:table-cell;border:none;padding-left:6px;font-size:18px;font-weight:bold;padding-top:5px;vertical-align:bottom;padding:0px" valign="bottom">
                                                <h6 style="margin:0">
                                                   <strong style="font-weight:bold">Payment</strong>
                                                </h6>
                                             </td>
                                             <td rowspan="3" align="left" style="display:table-cell;border:none;padding-left:6px;font-size:18px;padding-top:5px;vertical-align:bottom;padding:0px" valign="bottom">
                                                <h6 style="margin:0">
                                                  {{ date('d/m/Y',strtotime($record->cardData->from_date))}}
                                                </h6>
                                             </td>
                                             <td rowspan="3" align="right" style="display:table-cell;border:none;padding-left:6px;font-size:18px;padding-top:5px;vertical-align:bottom;padding:0px" valign="bottom">
                                                <h6 style="margin:0">
                                                   @if($record->cardData->payment_method=='card')
                                                   Credit Card
                                                   @elseif($record->cardData->payment_method=='bank_account')
                                                   Bank transfer
                                                   @else
                                                   Reserved
                                                   @endif
                                                </h6>
                                             </td>
                                             <td rowspan="3" align="right" style="display:table-cell;border:none;padding-left:6px;font-size:18px;font-weight:bold;text-align:right;padding-top:5px;vertical-align:bottom;padding:0px" valign="bottom">
                                                <h6 style="margin:0">
                                                   <strong style="font-weight:bold">NGN {{$record->cardData->total_amount + $record->cardData->optional_service_amount - $record->cardData->discount_amount}} </strong>
                                                </h6>
                                             </td>
                                          </tr>
                                        
                                          
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>