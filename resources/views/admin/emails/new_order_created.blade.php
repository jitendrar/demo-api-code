<tr>
	<td align="left">
		<table border="0" cellspacing="0" cellpadding="0" style="min-width:200px">
			<tbody>
				<tr>
					<td style="font-size:14px;color:#8a8a8a;padding-top:14px;font-family:'PT Sans',Arial,sans-serif;min-width:auto!important;line-height:19px;text-align:left;display:block">
						Dear Admin, 
						New Oder created on BopalDaily. Please find below further details.
						<br><br>
						<table border="1" style="width: 100%">
							<tr>
								<td> User ID </td>
								<td> <?php echo $content['user_id']?> </td>
							</tr>
							<tr>
								<td> User Name </td>
								<td> <?php echo $content['first_name'].' '.$content['last_name'];?> </td>
							</tr>
							<tr>
								<td> User Phone </td>
								<td> <?php echo $content['phone'];?> </td>
							</tr>
							<tr>
								<td> User Order Id </td>
								<td> <?php echo $content['user_id'];?> </td>
							</tr>
							<tr>
								<td> User Order Total Price </td>
								<td> <?php echo $content['totalOrderPrice'];?> </td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
