@if($record)
<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Open Sans', sans-serif;
  }
</style>
</head>
<body>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray;background-color:#ffffff" bgcolor="#ffffff">
   <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
      <tr style="display:table-row;vertical-align:inherit;border-color:inherit;background-color:#ebecee" bgcolor="#ebecee" valign="inherit">
         <td style="display:table-cell;border:none;border-left:10px solid rgb(235,236,238);border-right:10px solid rgb(235,236,238);border-bottom:10px solid rgb(235,236,238);vertical-align:top;padding:0;border-left:10px solid #ebecee;border-right:10px solid #ebecee;border-bottom:10px solid #ebecee" valign="top">
            <table  border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray;min-width:650px;max-width:650px;background-color:#ffffff;min-width:650px;max-width:650px;border-top:9px solid rgb(244,67,54)" bgcolor="#ffffff">
               <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                  <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                     <td style="display:table-cell;border:none;vertical-align:top;padding:0px" valign="top">
                        <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit;color:#2c333c;background-color:#ffffff" bgcolor="#ffffff" valign="inherit">
                                 <td align="center" valign="middle" style="display:table-cell;border:none;vertical-align:middle;color:#2c333c;background-color:#ffffff;padding-top:35px" bgcolor="#ffffff">
                                    <img alt="" style="max-width:220px;max-height:85px;margin:0;border:none;padding:0;display:block" src="{{asset('images/logo.png')}}">
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <table  border="0" cellspacing="0" cellpadding="20" align="center" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;padding-top:0px;margin-top:0px;width:100%;border-bottom:9px solid #ebecee;background-color:#ffffff;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px" width="100%" bgcolor="#ffffff">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit;color:#2c333c;background-color:#ffffff" bgcolor="#ffffff" valign="inherit">
                                 <td align="center" valign="middle" style="display:table-cell;border:none;vertical-align:middle;color:#2c333c;background-color:#ffffff;padding-top:0px" bgcolor="#ffffff">
                                    <p style="display:block;-webkit-margin-before:1__qem;-webkit-margin-after:1__qem;-webkit-margin-start:0;-webkit-margin-end:0;font-size:14px">{!! $record->description ?? '-' !!}.</p>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        
						<table  border="0" cellspacing="0" cellpadding="0" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;width:100%;color:#2c333c;border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px" width="100%">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td colspan="2" align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-top:20px;padding-bottom:0px" valign="inherit">
                                    <h1 style="font-size: 24px; margin: 0">
                                       PAYMENT SCHEDULE
                                    </h1>
                                 </td>
                              </tr>
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit;color:#2c333c" valign="inherit">
                                 <td colspan="2" style="display:table-cell;vertical-align:inherit;border:none" valign="inherit">
                                    <table border="0" cellspacing="0" cellpadding="0" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray;width:100%;color:#2c333c;padding:0px;margin:0px" width="100%">
                                       <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td style="display:table-cell;vertical-align:inherit;border:none;font-size:1px;text-align:center;padding:0px" align="center" valign="inherit">
                                               
                                             </td>
                                             <td rowspan="3" align="left" style="display:table-cell;border:none;padding-left:6px;font-size:13px;font-weight:bold;padding-top:5px;vertical-align:bottom;padding:0px" valign="bottom">
                                                <h6 style="margin:0">
                                                   <strong style="font-weight:bold">payment</strong>
                                                </h6>
                                             </td>
                                             <td rowspan="3" align="left" style="display:table-cell;border:none;padding-left:6px;font-size:13px;padding-top:5px;vertical-align:bottom;padding:0px" valign="bottom">
                                                <h6 style="margin:0">
                                                  {{ date('d/m/Y',strtotime($record->cardData->from_date))}}
                                                </h6>
                                             </td>
                                             <td rowspan="3" align="right" style="display:table-cell;border:none;padding-left:6px;font-size:13px;padding-top:5px;vertical-align:bottom;padding:0px" valign="bottom">
                                                <h6 style="margin:0">
                                                   {{ucwords(str_replace('_',' ',$record->cardData->payment_method))}}
                                                </h6>
                                             </td>
                                             <td rowspan="3" align="right" style="display:table-cell;border:none;padding-left:6px;font-size:13px;font-weight:bold;text-align:right;padding-top:5px;vertical-align:bottom;padding:0px" valign="bottom">
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

                        <table  border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td colspan="2" align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-bottom:20px;padding-top:20px" valign="inherit">
                                    <h1 style="font-size: 24px; margin: 0">
                                        BOOKING INFORMATION
                                       
                                    </h1>
                                   <b> Booking :  {{$record->subject}}</b>
                                 </td>
                              </tr>
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 
                                 <td align="center" style="display:table-cell;vertical-align:inherit;border:none;width:50%;width:50%" width="50%" valign="inherit">
                                    
                                 



                                 <table  border="0" width="100%" align="center" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray">
                                       <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit; text-align: left; color: #686666; font-size: 14px;" valign="middle">

									   <tr>
										<td>
											<table class="tableDatos" style="border: 1px solid #eeeeee ; text-align: justify" width="100%" cellspacing="0" cellpadding="0" border="0"> 
									<tbody> 
										<tr> 
											<td class="tituloDatos" style="padding: 0 ; background-color: #ffffff ; text-align: center ; font-weight: bold ; border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;" colspan="2">DETAILS OF THE BOOKING</td> 
										</tr> 
									<tr> 
										<th class="nombreDatos" style=" width:35%;  padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">
									
										<img src="{{$record->cardData->getProperty->image}}"style="width:250px; height:200px"/></th> 
										<td class="valorDatos"  style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">{{$record->property_name}} &nbsp;&nbsp;&nbsp;<br/><a href="{{ url('property-detail').'/'.$record->cardData->getProperty->id }}" target="_other" rel="nofollow">More information about the accommodation</a></td> 
									</tr> 
									
									<tr> 
										<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Type:</th> 
										<td class="valorDatos" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">&nbsp;{{$record->cardData->getProperty->type}}</td> 
									</tr>

									<tr>
										<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Capacity:</th> 
										<td class="valorDatos" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">&nbsp; {{$record->cardData->getProperty->max_guest}}</td> 
									</tr> 


									<tr> 
									<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Booking ref:</th> 
									<td class="valorDatos" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">&nbsp;{{$record->cardData->booking_id}}</td> 
									</tr>

									<tr>
									<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Number of occupants:</th> 
									<td class="valorDatos" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">&nbsp;Adults: {{$record->cardData->no_of_adult_guest}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Children: {{$record->cardData->no_of_children_guest}}</td> 
									</tr> 


								

									<tr>	
									<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Board:</th> 
									<td class="valorDatos" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">&nbsp;Stay only</td> 
									</tr>
									
									<tr> 
									<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Arrival date:</th> 
									<td class="valorDatos" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">&nbsp;{{ date('M d, Y', strtotime($record->cardData->from_date))}}</td> 
									</tr>


									<tr> 
									<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Departure date:</th> 
									<td class="valorDatos" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">&nbsp;{{ date('M d, Y', strtotime($record->cardData->to_date))}}</td>
									</tr>

								<tr>	 
									<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Nights:</th> 
									<td class="valorDatos" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">&nbsp;{{$record->cardData->total_days}}</td> 
									</tr> 

									
									<tr> 
									<th class="nombreDatos" style="padding: 7px 5px 2px 5px ; background-color: #ffffff ; border: 1px solid #fff ; color: #000; font-size: 12px;">Total Amount:</th> 
									<th class="valorDatos" colspan="2" style="border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;">NGN{{$record->cardData->total_amount + $record->cardData->optional_service_amount - $record->cardData->discount_amount}}</th> 
									</tr> 

                           <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td colspan="2" align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-bottom:20px;padding-top:20px" valign="inherit">
                                    
                                 Please CheckIn On your Booking Before 12 hours - <a href="{{$record->check_in_url}}" target="_blank">Click Here</a>
                             <br><br>
                             <span style="color: #000; font-size: 12px; ; font-size: 10pt"><strong>
                              <span style="color: #e03e2d">
                              <strong>
                                 <span>
                                    Contact details :Call +234 9122877657 via whatzapp or direct call if you have any questions about your booking or email info@shortletrentals.com 
                           </span></strong></span>

                                 </td>
                              </tr>
									</tbody> 
		           </table> 

				   <hr/>
				   <br/>



				   <table class="tableDatos" style="border: 1px solid #eeeeee ; text-align: justify" width="100%" cellspacing="0" cellpadding="10" border="0"> 
		            <tbody> 
		             <tr> 
		              <td class="tituloDatos" style="padding: 0 ; background-color: #ffffff ; text-align: center ; font-weight: bold ; border-bottom: 1px solid #eeeeee; padding-left:7px; color:#000; font-size: 12px;" colspan="4">OVERVIEW OF THE COSTS</td> 
		             </tr> 
		             <tr> 
		              <td style="text-align: center" colspan="4">
		               <table style="text-align: left" class="tableDesglosePrecios" width="85%" cellspacing="2px" cellpadding="2px 0px" border="0">
		                <tbody>
		                 <tr>
		                  <th width="50%">Accommodation <span style="font-size: 80%">(Stay only)</span></th>
		                  <td width="20%">{{$record->cardData->total_days}} nights</td>

		                  <td style="text-align: right" width="30%">NGN{{$record->cardData->per_night_price}}</td>
		            
		                 </tr>
		               
		              
		                 <tr>
		                  <td style="font-weight: bold">Total</td>
		                  <td>&nbsp;</td>
		                  <td style="text-align: right ; font-weight: bold" nowrap="">NGN{{$record->cardData->total_amount + $record->cardData->optional_service_amount - $record->cardData->discount_amount}}</td>
		               
		                 </tr>
		                </tbody>
		               </table>
					
  					<br/>

					
					
					</td> 
		             </tr> 
		            </tbody> 
		           </table> 


				   <br/>

				   




										</td>
									   </tr>
                                       

                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>

						<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-top:20px;padding-bottom:20px" valign="inherit">
                                    <h1 style="font-size: 24px; margin: 0">
                                       PRICING BREAKDOWN
                                    </h1>
                                 </td>
                              </tr>
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td style="display:table-cell;vertical-align:inherit;border:none" valign="inherit">
                                    <table style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray;letter-spacing:0.12px;line-height:17px;width:100%" width="100%">
                                       <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td colspan="4" style="display:table-cell;vertical-align:inherit;border:none;font-weight:bold;padding-bottom:5px" valign="inherit">
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">Accommodation</strong>
                                                </h6>
                                             </td>
                                          </tr>
										
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px" valign="top">
                                               <!--  <h6 style=" margin:10px 0">
                                                   {{$record->cardData->getProperty->location_of_television}}
                                                </h6> -->
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;white-space:nowrap;padding:0px 0px 0px 5px" valign="top">
                                                <h6 style=" margin:10px 0">
                                                   NGN {{$record->cardData->getProperty->price}} / night
                                                </h6>
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;white-space:nowrap;padding:0px 0px 0px 5px" valign="top">
                                                <h6 style=" margin:10px 0">
                                                   x {{$record->cardData->total_days}}
                                                </h6>
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;text-align:right;white-space:nowrap;padding:0px 0px 0px 5px" valign="top" align="right">
                                                <h6 style=" margin:10px 0">
                                                   NGN{{$record->cardData->getProperty->price*$record->cardData->total_days}}
                                                </h6>
                                             </td>
                                          </tr>

										  


                                        



                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px" valign="top">
                                                <h6 style=" margin:0">
												Caution Fee
                                                </h6>
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;white-space:nowrap;padding:0px 0px 0px 5px" valign="top">
                                                <h6 style=" margin:0">
                                               
                                                </h6>
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;white-space:nowrap;padding:0px 0px 0px 5px" valign="top">
                                                <h6 style=" margin:0">
												
                                                </h6>
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;text-align:right;white-space:nowrap;padding:0px 0px 0px 5px" valign="top" align="right">
                                                <h6 style=" margin:0">
                                                   @if(isset($record->cardData->security_deposite) && $record->cardData->security_deposite != 0)
												   NGN {{ $record->cardData->security_deposite }}
                                                @else
												NGN 0
                                                @endif
                                                </h6>
                                             </td>
                                          </tr>
									

  											@if(isset($record->cardData->selected_options))
											<?php $service = json_decode($record->cardData->selected_options) ?>
											
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td colspan="4" style="display:table-cell;vertical-align:inherit;border:none;font-weight:bold;padding-bottom:5px" valign="inherit">
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">Extras and services</strong>
                                                </h6>
                                             </td>
                                          </tr>



  											@foreach($service as $service)
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px" valign="top">
                                                <h6 style=" margin:0">
                                                   {{$service->name}}
                                                </h6>
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;white-space:nowrap;padding:0px 0px 0px 5px" valign="top">
                                                <h6 style=" margin:0">
                                                   
                                                </h6>
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;white-space:nowrap;padding:0px 0px 0px 5px" valign="top">
                                                <h6 style=" margin:0">
                                
                                                </h6>
                                             </td>
                                             <td style="display:table-cell;border:none;box-sizing:border-box;vertical-align:top;text-align:right;white-space:nowrap;padding:0px 0px 0px 5px" valign="top" align="right">
                                                <h6 style=" margin:0">
                                                   NGN{{$service->value}}
                                                </h6>
                                             </td>
                                          </tr>
										  @endforeach

										  @endif


                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td colspan="4" style="display:table-cell;vertical-align:inherit;border:none;padding-bottom:0px" valign="inherit">
                                                <hr style="display:block;-webkit-margin-before:0.5em;-webkit-margin-after:0.5em;-webkit-margin-start:auto;-webkit-margin-end:auto;border-style:inset;border-width:1px;border-top:0px;color:#eeeeee">
                                             </td>
                                          </tr>
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td colspan="3" style="display:table-cell;vertical-align:inherit;border:none" valign="inherit">
                                                <h3 style="margin: 5px 0">
                                                   <strong style="font-weight:bold">Total </strong>
                                                </h3>
                                             </td>
                                             <td style="display:table-cell;vertical-align:inherit;border:none;text-align:right;white-space:nowrap" align="right" valign="inherit">
                                                <h3 style="margin: 5px 0">
                                                   <span style="color:black">NGN {{$record->cardData->total_amount+$record->cardData->optional_service_amount - $record->cardData->discount_amount}}</span>
                                                </h3>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>

						<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td colspan="2" align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-bottom:20px;padding-top:20px" valign="inherit">
                                    <h1 style="font-size: 24px; margin: 0">
                                       KEY COLLECTION INFORMATION
                                    </h1>
                                 </td>
                              </tr>
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td style="display:table-cell;border:none;width:50%;vertical-align:top;padding-right:0px;width:50%" width="50%" valign="top">
                                    <img alt="" width="250" src="{{ URL::asset('assets/images/staticmap.png')}}" style="max-width: 100vw;">
                                 </td>
                                 <td valign="inherit" style="display:table-cell;vertical-align:inherit;border:none;width:50%;width:50%" width="50%">
                                    <table border="0" align="center" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray">
                                       <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td colspan="2" style="display:table-cell;vertical-align:inherit;border:none;padding:0px" valign="inherit">
                                                <h5 style="margin: 10px 0">
                                                   <strong style="font-weight:bold">Shortlet Rentals Ltd</strong>
                                                </h5>
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">Address:</strong>H6 Flat 5 Lekki Gardens Estate, 235 kunsela road, chisco bus stop . Lekki () Lekki (Nigeria)<br>
                                                   <br>
                                                </h6>
                                             </td>
                                          </tr>
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td colspan="2" style="display:table-cell;vertical-align:inherit;border:none;padding:0px" valign="inherit">
                                                <h6 style=" margin:10px 0">
                                                   <span style="text-decoration:underline"><strong style="font-weight:bold">Check-in/out times<br>
                                                   </strong></span>
                                                </h6>
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">Arrival: </strong>from Every day: From 15:00 to 18:00
                                                </h6>
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">Check-out</strong>: 12:00
                                                </h6>
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">Office hours:</strong> Every day: From 0:00 to 0:00
                                                </h6>
                                             </td>
                                          </tr>
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td colspan="2" style="display:table-cell;vertical-align:inherit;border:none;padding:0px" valign="inherit">
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold"><br>
                                                   <span style="text-decoration:underline">Contact details</span><br>
                                                   <br>
                                                   </strong>
                                                </h6>
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">Phone</strong>: + (234) 9122877657
                                                </h6>
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">E-mail</strong>: <a href="mailto:customersupport@shortletrentals.com" target="_blank" title="mailto:customersupport@shortletrentals.com">customersupport@shortletrentals.com</a>
                                                </h6>
                                             </td>
                                          </tr>
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td colspan="2" style="display:table-cell;vertical-align:inherit;border:none;padding:0px" valign="inherit">
                                                <h6 style=" margin:10px 0">
                                                   <strong style="font-weight:bold">We speak: </strong>English (UK)
                                                </h6>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                      
						<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td colspan="2" align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-top:20px;padding-bottom:20px" valign="inherit">
                                    <h1 style="font-size: 24px; margin: 0">
                                       ADDITIONAL INFO&nbsp;
                                    </h1>
                                 </td>
                              </tr>
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td style="display:table-cell;vertical-align:inherit;border:none" valign="inherit">
                                    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray">
                                       <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                                          <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                             <td style="display:table-cell;vertical-align:inherit;border:none;padding:0px" valign="inherit">
                                                <ul style="display:block;list-style-type:disc;-webkit-margin-before:1__qem;-webkit-margin-after:1em;-webkit-margin-start:0;-webkit-margin-end:0;-webkit-padding-start:40px;padding-left:5px;margin-top:0px;padding-left:5px;margin-top:0px">
                                                   <li style="display:list-item;text-align:-webkit-match-parent">Late arrivals: Make arrangements with the agency for late arrivals. The late arrival fee must be paid in cash upon arrival.</li>
                                                   <li style="display:list-item;text-align:-webkit-match-parent">Refund of security deposit to the credit card 24/48h after your departure</li>
                                                </ul>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        
                      
                        <table border="0" cellspacing="0" cellpadding="20" align="center" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray;width:100%;color:#2c333c" width="100%">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td align="center" style="display:table-cell;vertical-align:inherit;border:none" valign="inherit">
                                    <p style="display:block;-webkit-margin-before:1__qem;-webkit-margin-after:1__qem;-webkit-margin-start:0;-webkit-margin-end:0">Best regards, <br>
                                       &nbsp;Shortlet Rentals Ltd&nbsp;
                                    </p>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
            <br>
            <p style="display:block;-webkit-margin-before:1__qem;-webkit-margin-after:1__qem;-webkit-margin-start:0;-webkit-margin-end:0;color:#2d414c;font-family:Arial;font-size:12px;letter-spacing:0.21px;line-height:26px;text-align:center;margin-bottom:10px;margin-top:10px">
               &nbsp;<strong style="font-weight:bold">Shortlet Rentals Ltd</strong> <br>
               2349122877657 - <a href="mailto:info@shortletrentals.com" target="_blank" title="mailto:info@shortletrentals.com">info@shortletrentals.com</a> <br>
               Lagos,Nigeria
            </p>
         </td>
      </tr>
   </tbody>
</table>
</body>
</html>

@endif