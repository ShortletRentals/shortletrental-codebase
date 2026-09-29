<table id="m_446318818945093154desglosePrecios" style="border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td align="left" style="padding-top:20px;padding-bottom:20px">
<h1 style="color:#ef6d3b;font-size:18px;font-weight:bold;line-height:26px;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif;margin:0px">PRICING BREAKDOWN</h1>
</td>
</tr>
<tr>
<td>
<table style="letter-spacing:0.12px;line-height:17px;width:100%">
<tbody>
<tr>
<td style="font-weight:bold;padding-bottom:5px !important" colspan="4">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Accommodation</strong></h6>
</td>
</tr>


<tr>
<td style="box-sizing:border-box;padding:0px 0px 0px 5px !important;vertical-align:top">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">  {!! $record->cardData->getProperty->title !!}<br><small>{!! $record->cardData->getProperty->address !!}</small></h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">NGN{{ number_format($record->cardData->getProperty->price)}} / night</h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">x  {{$record->cardData->total_days}}</h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;text-align:right;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">NGN{{number_format($record->cardData->getProperty->price*$record->cardData->total_days)}}</h6>
</td>
</tr>

<tr>
<td style="box-sizing:border-box;padding:0px 0px 0px 5px !important;vertical-align:top">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">  Caution Fee</h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"></h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"></h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;text-align:right;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">
@if(isset($record->cardData->security_deposite) && $record->cardData->security_deposite != 0)
												   NGN{{number_format($record->cardData->security_deposite) }}
                                                @else
												NGN0
                                                @endif
                                            </h6>
</td>
</tr>

<tr>
<td style="box-sizing:border-box;padding:0px 0px 0px 5px !important;vertical-align:top">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">  Discount</h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"></h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"></h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;text-align:right;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">

@if(isset($record->cardData->discount_amount) && !empty($record->cardData->discount_amount))
NGN{{number_format($record->cardData->discount_amount)}}                                 
@else
NGN0
@endif

                                            </h6>
</td>
</tr>


@if(isset($record->cardData->selected_options))
<?php $service = json_decode($record->cardData->selected_options) ?>
<tr>
<td style="padding-bottom:0px !important" colspan="4"><hr style="border-top:0px;color:#eeeeee"></td>
</tr>
<tr>
<td style="font-weight:bold;padding-bottom:5px !important" colspan="4">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Extras and services</strong></h6>
</td>
</tr>
@foreach($service as $service)

<tr>
<td style="box-sizing:border-box;padding:0px 0px 0px 5px !important;vertical-align:top">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">{{$service->name}}</h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">Included</h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">x 1</h6>
</td>
<td style="box-sizing:border-box;vertical-align:top;text-align:right;padding:0px 0px 0px 5px !important;white-space:nowrap">
<h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"> NGN{{number_format($service->value)}}</h6>
</td>
</tr>
@endforeach

@endif
<tr>
<td style="padding-bottom:0px !important" colspan="4"><hr style="border-top:0px;color:#eeeeee"></td>
</tr>





<tr>
<td colspan="3">
<h3 style="margin:0;margin-bottom:5px;margin-top:3px;padding:0;font-size:14px;line-height:23px;font-weight:normal;color:#393939;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Total </strong></h3>
</td>
<td style="text-align:right;white-space:nowrap">
<h3 style="margin:0;margin-bottom:5px;margin-top:3px;padding:0;font-size:14px;line-height:23px;font-weight:normal;color:#393939;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><span style="color:black">NGN {{number_format($record->cardData->total_amount+$record->cardData->optional_service_amount - $record->cardData->discount_amount)}}</span></h3>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>