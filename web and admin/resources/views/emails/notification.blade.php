<div class="table-responsive">
		<table style="border-collapse: collapse;padding: 0;margin: 20px auto;text-align: center;font-size: 17px;font-family: 'Poppins', sans-serif;
		" width="600px" bgcolor="#f7f7f7">
		  <thead>
			<tr>
			  <td style="padding: 15px 30px 10px;">
				<a href="#"><img src="{{ URL::asset('assets/web/img/logo.png')}}"></a>
			  </td>
			</tr>
		  </thead>
		  <tbody style="background: #fff;">
			<tr>
			  <td style="padding: 50px 30px;">
				 <p style="font-size:1.1em">Hi {{ ucfirst($record->username) }},</p>
    				<p>{!! $record->subject ?? '-' !!}</p>
    				<p>{!! $record->description ?? '-' !!}</p>
			  </td>
			</tr>
		  </tbody>
		  <tfoot>
		
		  </tfoot>
		</table>
	</div>
