<table id="m_446318818945093154fianza" style="border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td colspan="2" align="left" style="padding-bottom:20px">
<h1 style="color:#ef6d3b;font-size:18px;font-weight:bold;line-height:26px;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif;margin:0px">DEPOSIT</h1>
</td>
</tr>
<tr>
<td style="width:50%">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="padding:0px">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Amount:</strong> NGN{{$record->cardData->security_deposite ?? 0}}</h6>
</td>
</tr>
<tr>
<td style="padding:0px">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Payment Method:</strong>   {{ucwords(str_replace('_',' ',$record->cardData->payment_method))}}</h6>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width:50%">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="padding:0px">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Payment Date:</strong> {{date('d/m/Y',strtotime($record->cardData->created_at))}}</h6>
</td>
</tr>
<tr>
<td style="padding:0px">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>To be returned on:</strong> {{date('d/m/Y',strtotime($record->cardData->to_date))}}</h6>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>