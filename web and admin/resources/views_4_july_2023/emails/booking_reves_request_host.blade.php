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
               

                     @include('emails.booking_info_host')


                     
                              </td>
                              
									</tbody> 
		           </table> 


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