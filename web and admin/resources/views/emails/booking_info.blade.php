<table  border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="display:table;border-spacing:2px;border-color:gray;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px;border-bottom:9px solid #ebecee;background:white;border-collapse:initial;padding-left:40px;padding-right:40px;padding-bottom:25px">
                           <tbody style="display:table-row-group;vertical-align:middle;border-color:inherit" valign="middle">
                              <tr style="display:table-row;vertical-align:inherit;border-color:inherit" valign="inherit">
                                 <td colspan="2" align="left" style="display:table-cell;vertical-align:inherit;border:none;padding-top:20px;padding-bottom:20px;padding-bottom:20px;padding-top:20px" valign="inherit">
                                    <h1 style="font-size: 18px; margin: 0 ;color: #ef6d3b;">
                                        BOOKING INFORMATION
                                       
                                    </h1>
                                  
                                 </td>
                              </tr>

                              <tr>
                              <td style="vertical-align:top;padding-right:0px;width:50%">
                              <img src="{{$record->cardData->getProperty->image}}" alt=""  style="width: 250px;"></td>
                              <td align="center" style="width:50%">
                              <table id="m_446318818945093154informacionReserva" border="0" width="100%" align="center">
                              <tbody>
                              <tr>
                              <td width="50%" style="padding:0px">
                              <h4 style="color:#9b9b9b;font-size:18px;font-weight:600;letter-spacing:0.17px;line-height:18px;margin:0px;text-align:justify;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">Arrival</h4>
                              <strong style="color:#393939;font-size:18px;font-weight:bold;letter-spacing:0.24px;line-height:24px"> {{ date('d/m/Y', strtotime($record->cardData->from_date))}}</strong></td>
                              <td width="50%" style="padding:0px">
                              <h4 style="color:#9b9b9b;font-size:18px;font-weight:600;letter-spacing:0.17px;line-height:18px;margin:0px;text-align:justify;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">Departure</h4>
                              <strong style="color:#393939;font-size:18px;font-weight:bold;letter-spacing:0.24px;line-height:24px"> {{ date('d/m/Y', strtotime($record->cardData->to_date))}} </strong></td>
                              </tr>
                              <tr>
                              <td colspan="2" style="padding:0px"><br>
                              <h5 style="margin:0px;padding:0;font-size:12px;line-height:20px;font-weight:600;color:#393939;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif">{{$record->property_name}}
                              <br/><a href="{{ url('property-detail').'/'.$record->cardData->getProperty->id }}" target="_other" rel="nofollow">More information about the accommodation</a>
                              </h5>
                              
                              <hr></td>
                              </tr>
                              <tr>
                              <td colspan="2" style="padding:0px">
                              <h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Nº of nights: </strong>{{$record->cardData->total_days}}</h6>
                              <h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Capacity: </strong>{{$record->cardData->getProperty->max_guest}}&nbsp;people</h6>
                              <h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Guests: </strong>
                              {{$record->cardData->no_of_adult_guest}} adults +  {{$record->cardData->no_of_children_guest}} children
                             </h6>
                              <hr></td>
                              </tr>

                              <tr>
                              <td colspan="2" style="padding:0px">
                              <h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Booking Reference Number: </strong> {{$record->cardData->booking_id}}</h6>
                              </td>
                              </tr>

                              <tr>
                              <td colspan="2" style="padding:0px">
                              <h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Booking Type: </strong> {{$record->cardData->getProperty->type}}</h6>
                              <hr>
                              </td>
                              </tr>

                              <tr>
                              <td colspan="2" style="padding:0px">
                              <h6 style="margin:0;padding:0;font-size:12px;font-weight:normal;color:#2c333c;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Capacity: </strong> {{$record->cardData->getProperty->max_guest}}</h6>
                              <hr>
                              </td>
                              </tr>

                           


                              <tr>
                              <td colspan="2" style="padding:0px"><br>
                              <h3 style="margin:0;margin-bottom:5px;margin-top:3px;padding:0;font-size:14px;line-height:23px;font-weight:normal;color:#393939;font-family:Open Sans,Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif"><strong>Total Amount: </strong><span style="font-weight:bold">NGN{{number_format($record->cardData->total_amount + $record->cardData->optional_service_amount - $record->cardData->discount_amount)}}</span></h3>
                              </td>
                              </tr>
                              </tbody>
                              </table>