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
                                    <p style="display:block;-webkit-margin-before:1__qem;-webkit-margin-after:1__qem;-webkit-margin-start:0;-webkit-margin-end:0;font-size:14px">{!! $data->description ?? '-' !!}.</p>
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
                                   <b> Confirmation of new review for accommodation  :  {!! $booking->getProperty->title ?? '-'!!}</b>
                                 </td>
                              </tr>
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 
                                 <td align="center" style="display:table-cell;vertical-align:inherit;border:none;width:50%;width:50%" width="50%" valign="inherit">
                                    
                                 



                                 <table  border="0" width="100%" align="center" style="display:table;border-collapse:separate;border-spacing:2px;border-color:gray">
                                       <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit; text-align: left; color: #686666; font-size: 14px;" valign="middle">
                                        <tr>
                                            <th>Accommodation : </th>
                                            <td>{!! $booking->getProperty->title ?? '-'!!}</td>
                                        </tr>

                                        <tr>
                                            <th>Check-in :</th>
                                            <td>{!! date('d/m/Y',strtotime($booking->from_date)) ?? '-'!!}</td>
                                        </tr>   
                                        <tr>
                                            <th>Check-Out :</th>
                                            <td>{!!  date('d/m/Y',strtotime($booking->to_date)) ?? '-'!!}</td>
                                        </tr>   
                                        <tr>
                                            <th>Reservation number :</th>
                                            <td>{!! $booking->booking_id ?? '-'!!}</td>
                                        </tr> 
                                       
                                        <tr>
                                            <th>General rating::</th>
                                            <td>{!! $rating->rate ?? '-'!!}</td>
                                        </tr> 
                                        <tr>
                                            <th>Review :</th>
                                            <td>{!!$rating->review ?? '-'!!}</td>
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