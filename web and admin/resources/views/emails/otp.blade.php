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
				 <p style="font-size:1.1em">Hi {{$user['name']}},</p>
    <p>Thank you for choosing Shortletrental.com. Use the following OTP to complete your procedures. OTP is valid for 5 minutes</p>
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">{{$otp}}</h2>
			  </td>
			</tr>
		  </tbody>
		  <tfoot>
		
		  </tfoot>
		</table>
	</div>
