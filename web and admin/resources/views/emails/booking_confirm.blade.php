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
                                 <p style="display:block;-webkit-margin-before:1__qem;-webkit-margin-after:1__qem;-webkit-margin-start:0;-webkit-margin-end:0;font-size:14px"><b>{!! $record->name ?? ''  !!}</b></p>
                                    <p style="display:block;-webkit-margin-before:1__qem;-webkit-margin-after:1__qem;-webkit-margin-start:0;-webkit-margin-end:0;font-size:14px">{!! $record->description ?? '-' !!}.</p>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     @include('emails.payment_schedule')

                     @include('emails.booking_info')


                     
                              </td>
                              </tr>
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td colspan="2" align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-bottom:20px;padding-top:20px; font-size: 14px;" valign="inherit">
                                 <hr>
                                    <b>
                                       
                                    You still haven't performed your <a href="{{$record->check_in_url}}" target="_blank">Check-in Online</a>. This must be done before you arrive.
                                    Click here to online check-In :- <br/><br/><a href="{{$record->check_in_url}}" target="_blank" style="height:30px;width:158px;border-radius:4px;border:0px;background-color:rgb(214 114 14);color:white;font-weight:bold;margin-top:25px;margin-bottom:30px;padding-top:7px;padding-left:15px;padding-right:15px;padding-bottom:7px;text-decoration:none;text-transform:uppercase;text-align:center;font-size:14px">CLICK HERE TO CHECK-IN</a></b>
                             <br>
                             We recommend that you have all your personal information to hand, including your Passport/ID information, in order to check-in online.
                             <br>
                             <span style="color: #000; font-size: 12px; ; font-size: 10pt; color: #e03e2d"><strong>
                                    Contact details :Call +234 9122877657 via whatzapp or direct call if you have any questions about your booking or email info@shortletrentals.com 
                                 </td>
                              </tr>
									</tbody> 
		           </table> 

				   <hr/>
				   <br/>

               @include('emails.payment_guest_deposit')
               

                        <hr/>
				   <br/>
                        @include('emails.priciing_brackdown')
						
                        @include('emails.key_collection')
						    
                        @include('emails.addi_info')
                        @include('emails.sami-footer')
                                            
                     </td>
                  </tr>
               </tbody>
            </table>
            @include('emails.footer')
         </td>
      </tr>
   </tbody>
</table>
@include('emails.other_info')
</body>
</html>

@endif