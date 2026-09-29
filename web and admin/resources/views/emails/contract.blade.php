@if($record)
<div class="table-responsive">
   <table style="border-collapse: collapse;padding: 0;margin: 20px auto;text-align: center;font-size: 17px;font-family: 'Poppins', sans-serif;
      " width="600px" bgcolor="#f7f7f7">
      <thead>
         <tr>
            <td style="padding: 15px 30px 10px;">
               <a href="#"><img src="{{ URL::asset('assets/images/logo-img.png')}}"></a>
            </td>
         </tr>
      </thead>
      <tbody style="background: #fff;">
         <tr>
            <td valign="top">
               <table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                     <tr>
                        <td class="Cabecera" style="background-color: #ffffff" height="50" align="left"><img alt="" border="0"></td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td>
               <table class="temail" style="background-color: #ffffff" width="100%" cellspacing="5" cellpadding="10" border="0">
                  <tbody>
                     <!-- <tr>
                        <td class="Asunto" style="background-color: #ffffff ; font-size: 12px ; font-weight: bold ; text-align: justify">Subject: <span style="text-decoration: underline">{{$record->subject}}</span></td>
                     </tr> -->
                     <tr>
                        <td class="texto" align="left">Dear Admin,&nbsp;{{$record->admin_name}}<br><br>{{$record->username}}({{$record->useremail}}), contract is expire on {{$record->end_date}}.</td>
                     </tr>
                  </tbody>
               </table>
               <div style="text-align: center ; width: 600px ; margin: 0 auto">
                  <p>Thank you.&nbsp;</p>
               </div>
            </td>
         </tr>
      </tbody>
      <tfoot>
         <tr>
            <td style="padding: 30px;font-size: 14px;">{{ $record->footer }}</td>
         </tr>
      </tfoot>
   </table>
</div>
@endif
