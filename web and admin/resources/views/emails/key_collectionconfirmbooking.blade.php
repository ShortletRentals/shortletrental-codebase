

 <?php $main_address = ''; ?>
@if(isset($record->cardData->getProperty->getPropertyAddress[0]))

 @if($record->cardData->getProperty->getPropertyAddress[0]->street_name)
  <?php $main_address .= isset($record->cardData->getProperty->getPropertyAddress[0]->street_name) && !empty($record->cardData->getProperty->getPropertyAddress[0]->street_name) ? $record->cardData->getProperty->getPropertyAddress[0]->street_name.', ' : ''; ?>
  @endif
  
  @if($record->cardData->getProperty->getPropertyAddress[0]->getPropertyArea)
  <?php $main_address .= isset($record->cardData->getProperty->getPropertyAddress[0]->getPropertyArea->name) && !empty($record->cardData->getProperty->getPropertyAddress[0]->getPropertyArea->name) ? $record->cardData->getProperty->getPropertyAddress[0]->getPropertyArea->name.', ' : ''; ?>
  @endif

  @if($record->cardData->getProperty->getPropertyAddress[0]->getPropertyCity)
    <?php $main_address .= isset($record->cardData->getProperty->getPropertyAddress[0]->getPropertyCity->name) && !empty($record->cardData->getProperty->getPropertyAddress[0]->getPropertyCity->name) ? $record->cardData->getProperty->getPropertyAddress[0]->getPropertyCity->name.', ' : ''; ?>
  @endif

  @if($record->cardData->getProperty->getPropertyAddress[0]->getPropertyProvince)
  <?php $main_address .= isset($record->cardData->getProperty->getPropertyAddress[0]->getPropertyProvince->name) && !empty($record->cardData->getProperty->getPropertyAddress[0]->getPropertyProvince->name) ? $record->cardData->getProperty->getPropertyAddress[0]->getPropertyProvince->name.', ' : ''; ?>
  @endif

  @if($record->cardData->getProperty->getPropertyAddress[0]->getPropertyCountry)
  <?php $main_address .= isset($record->cardData->getProperty->getPropertyAddress[0]->getPropertyCountry->name) && !empty($record->cardData->getProperty->getPropertyAddress[0]->getPropertyCountry->name) ? $record->cardData->getProperty->getPropertyAddress[0]->getPropertyCountry->name.' ' : ''; ?>
  @endif
@endif
<table id="m_446318818945093154recepcionRecogidaLlaves" style="border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td style="padding-bottom:20px;padding-top:20px" colspan="2" align="left">
<h1 style="color:#ef6d3b;font-size:18px;font-weight:bold;line-height:26px;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif;margin:0px">KEY COLLECTION INFORMATION</h1>
</td>
</tr>
<tr>
<td style="vertical-align:top;padding-right:0px;width:50%"><img src="https://maps.googleapis.com/maps/api/staticmap?key=AIzaSyCUtZPXBKJuQ0GHmkMiQKuSvf3yV53lzXg&amp;center={{$record->cardData->getProperty->latitude ?? ''}},{{$record->cardData->getProperty->longitude ?? ''}}&amp;zoom=16&amp;size=250x210&amp;sensor=false&amp;markers={{$record->cardData->getProperty->latitude ?? ''}},{{$record->cardData->getProperty->longitude ?? ''}}" alt="" width="250" style="max-width: 100vw;"></td>
<td valign="top" style="width:50%">
<table border="0" align="center">
<tbody>
<tr>
<td colspan="2" style="padding:0px">
<h5 style="margin:0px;padding:0;font-size:12px;line-height:20px;font-weight:600;color:#393939;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Shortlet Rentals Ltd</strong></h5>

</td>
</tr>
<tr>
<td colspan="2" style="padding:0px">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><span style="text-decoration:underline"><strong>Check-in/out times<br></strong></span></h6>
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Arrival: </strong>from Every day: From {{date('h:i A',strtotime($record->cardData->getProperty->check_in_from_time) ?? '')}} to {{date('h:i A',strtotime($record->cardData->getProperty->check_in_to_time) ?? '')}}</h6>
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Check-out</strong>: {{date('h:i A',strtotime($record->cardData->getProperty->check_out_time) ?? '')}}</h6>
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Office hours:</strong> Every day: From 12:00 AM to 11:59 PM</h6>
</td>
</tr>
<tr>
<td colspan="2" style="padding:0px">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong><br><span style="text-decoration:underline">Contact details</span><br><br></strong></h6>
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Phone</strong>: + (234) 9122877657</h6>
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>E-mail</strong>: <a href="mailto:customersupport@shortletrentals.com" target="_blank" title="mailto:customersupport@shortletrentals.com">customersupport@shortletrentals.com</a></h6>
</td>
</tr>
<tr>
<td colspan="2" style="padding:0px">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>We speak: </strong>English (UK)</h6>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>