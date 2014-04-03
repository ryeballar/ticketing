<div style="background-color: #FFFF99">
	Congratulations! You have succcessfully bought a ticket for the flight.
	Please take note of this airline ticket number:
	<h1 style="text-align: center">Ticket# <?php echo $at_id ?></h1><hr>
	<h2>Summary</h2>
	<h3>Flight Details</h3>
	<label>Airline Company</label><br>
	<?php echo $flight['ac_name'] ?><br>
	<label>Airline</label><br>
	<?php echo $flight['al_name'] ?><br>
	<label>Departure Time Stamp</label><br>
	<?php echo $flight['fs_dep'] ?><br>
	<label>Origin - Destination</label><br>
	<?php echo $flight['place_from'] ?> - 
	<?php echo $flight['place_to'] ?><br>
	<label>Total Cost</label><br>
	P<?php echo $accom['fare'] ?>

	<h3>Passenger Information</h3>
	<label>Passenger Name:</label>
	<?php echo $passenger['ps_name'] ?><br>
	<label>Address:</label>
	<?php echo $passenger['ps_address'] ?><br>
	<label>Telehpone #:</label>
	<?php echo $passenger['ps_tel'] ?><br>
	<label>Fax #:</label>
	<?php echo $passenger['ps_fax'] ?><br>
	<label>Email:</label>
	<?php echo $passenger['ps_email'] ?><br>
	<label>Bith Date:</label>
	<?php echo $passenger['ps_bdate'] ?><br>
	<label>Contact Person:</label>
	<?php echo $passenger['ps_contact_person'] ?><br>
	<label>Contact Telephone #:</label>
	<?php echo $passenger['ps_contact_tel'] ?>
</div>