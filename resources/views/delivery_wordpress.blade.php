<?php
/* Template Name: Custom Delivery Services - New Features V2 */

// Exit if accessed directly.
// if (!defined('ABSPATH')) {
//     exit;
// }

// Get header
get_header(); 
?>
 <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet"><link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/delivery.css">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfJAEOOESD7IvZy6qBYvOz7opcYVnDksw&libraries=places"></script>
<style>
    input[type="number"] {
    -moz-appearance: textfield; /* Ensures Firefox shows the arrows */
}

input[type="number"]::-webkit-inner-spin-button, 
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: inner-spin-button; /* Ensures Chrome shows them */
    appearance: auto;
}
</style>
<div class="">
   
<div class="content">
  <!--content inner-->
  <div class="content__inner">
    <div class="container">
      <!--content title-->
      <h2 class="content__title content__title--m-sm">Delivery Services</h2>
      <!--animations form-->
      <div class="pick-animation my-4" style="display:none;">
        <div class="form-row">
          <div class="col-5 m-auto">
            <select class="pick-animation__select form-control">
              <option value="scaleIn">ScaleIn</option>
              <option value="scaleOut">ScaleOut</option>
              <option value="slideHorz" selected="selected">SlideHorz</option>
              <option value="slideVert">SlideVert</option>
              <option value="fadeIn">FadeIn</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="container overflow-hidden">
      <!--multisteps-form-->
      <div class="multisteps-form">
        <!--progress bar-->
        <div class="row">
          <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
            <div class="multisteps-form__progress">
              <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">Address Info</button>
              <button class="multisteps-form__progress-btn" type="button" title="Address">User Info</button>
              <button class="multisteps-form__progress-btn" type="button" title="Order Info">Costs</button>
              <button class="multisteps-form__progress-btn" type="button" title="Comments">Summary        </button>
            </div>
          </div>
        </div>
        <!--form panels-->
        <div class="row">
          <div class="col-12 col-lg-12 m-auto">
            <form class="multisteps-form__form"  method="POST" action="">
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideHorz">
                <h4 class="multisteps-form__title">Address Info</h4>
                <div class="multisteps-form__content">
					<div class="form-row mt-4">
						<div class="col-12 col-sm-4">
							<label for="pickup-location" class="form-label">Pick Up Location <span style="color: red;">*</span></label>
							<input id="pickup-location" name="pickup-location" class="multisteps-form__input form-control" type="text" placeholder="Enter Pick Up Location"  required/>
						</div>
						<div class="col-12 col-sm-4 mt-4 mt-sm-0">
							<label for="dropoff-address" class="form-label">Drop Off Address <span style="color: red;">*</span></label>
							<input id="dropoff-address"  name="dropoff-address"  class="multisteps-form__input form-control" type="text" placeholder="Enter Drop Off Address" required />
						</div>
						<div class="col-12 col-sm-4 mt-2 mt-sm-0">
							<label for="calculated-miles" class="form-label">Calculated Miles</label>
							<input id="calculated-miles" name="calculated-miles" class="multisteps-form__input form-control" type="text" placeholder="Enter Calculated Miles" disabled/>
						</div>

						<!-- Center the small text -->
						<div class="col-12 mt-2" style="display:none;">
							<small class="form-text text-muted text-center d-block mx-auto">Anything over 12.5 charge 3.41 per mile charge round trip in miles</small>
						</div>
					</div>
					<div id="map" style="width: 100%; height: 300px; margin-top: 20px;"></div>

                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next" id="first">Next</button>
                  </div>
                </div>
              </div>
             <!-- single form panel -->
				<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="slideHorz">
				  <h4 class="multisteps-form__title">User Info</h4>
				  <div class="multisteps-form__content">
					
					  <!-- User Info Form Fields -->
					  <div class="form-row mt-4">
              <div class="col-12 col-sm-4">
                <label for="first-name" class="form-label">First Name <span style="color: red;">*</span></label>
                <input id="first-name"  name="first-name"  class="multisteps-form__input form-control" type="text"  placeholder="First Name" required/>
              </div>
              <div class="col-12 col-sm-4 mt-sm-0">
                <label for="last-name" class="form-label">Last Name <span style="color: red;">*</span></label>
                <input id="last-name" name="last-name" class="multisteps-form__input form-control" type="text"  placeholder="Last Name"  required/>
              </div>
						  
						<div class="col-12 col-sm-4 mt-4 mt-sm-0">
							<label for="added-mile-rate" class="form-label">Approx Move Date <span style="color: red;">*</span></label>
							<input id="move-date" name="move-date"  class="multisteps-form__input form-control" type="date" placeholder="Approx Move Date"  required/>
						</div>
					  </div>
					  <div class="form-row mt-4">
						  <div class="col-12 col-sm-6">
							<label for="phone-number" class="form-label">Phone Number <span style="color: red;">*</span></label>
							<input id="phone-number" name="phone-number" class="multisteps-form__input form-control" type="text"  placeholder="Phone Number"  required />
							<small>By providing a telephone number and submitting the form, you are consenting to be contacted by SMS text message (our message frequency may vary). Message & data rates may apply. Reply STOP to opt-out of further messaging. Reply HELP for more information. See our Privacy Policy.</small>
						  </div>
						  <div class="col-12 col-sm-6 mt-4 mt-sm-0">
							<label for="email" class="form-label">Email <span style="color: red;">*</span></label>
							<input id="email" name="email" class="multisteps-form__input form-control" type="email"  placeholder="Email"  required />
						  </div>
					  </div>
					  
					  <div class="form-row mt-4">
						  <div class="col-12 col-sm-4">
							<label for="sales_name" class="form-label">Sales Reps Name <span style="color: red;">*</span></label>
							<input id="sales_name" name="sales_name" class="multisteps-form__input form-control" type="text"  placeholder="Sales Reps Name"  required />
						  </div>
						  <div class="col-12 col-sm-4  mt-4 mt-sm-0">
							<label for="sales_email" class="form-label">Sales Reps Email <span style="color: red;">*</span></label>
							<input id="sales_email" name="sales_email" class="multisteps-form__input form-control" type="email"  placeholder="Sales Reps Email"  required />
						  </div>
						  <div class="col-12 col-sm-4  mt-4 mt-sm-0">
							<label for="sales_location" class="form-label">Store Location <span style="color: red;">*</span></label>
							<input id="sales_location" name="sales_location" class="multisteps-form__input form-control" type="text"  placeholder="Store Location"  required />
						  </div>
					  </div>
					  
            <div class="form-row mt-4">
				
				<div class="col-12 col-sm-6">
				  <label for="noofitems" class="form-label">No. of Moving or Delivery or Removal <span style="color: red;">*</span></label>
				  <input id="noofitems" name="noofitems" class="multisteps-form__input form-control" type="number" placeholder="No. of Moving or Delivery or Removal Items" value="0" required />
				</div>
				
             	<div class="col-12 col-sm-6  mt-4 mt-sm-0">
<!-- 				  <label for="phone-number" class="form-label">Upload Images <span style="color: red;">*</span></label> -->
				  <div id="image-container">
					<!-- Dynamic file inputs will be appended here -->
				  </div>
				</div>


					  </div>
            
            

              <!-- Button Row -->
              <div class="button-row d-flex mt-4">
                <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next"  id="second">Next</button>
              </div>
				  </div>
				</div>

              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="slideHorz">
                <h4 class="multisteps-form__title">Costs</h4>
                <div class="multisteps-form__content">
                  
					<div class="form-row mt-4">
						<div class="col-12 col-sm-6 mt-4 mt-sm-0">
							<label for="added-miles" class="form-label">Added Miles</label>
							<input id="added-miles"  name="added-miles"  class="multisteps-form__input form-control" type="text" placeholder="Added Miles" disabled/>
						</div>
						<div class="col-12 col-sm-6 mt-4 mt-sm-0">
							<label for="added-mile-rate" class="form-label">Added Mile Rate</label>
    						<span class="dollar-sign">$</span>
							<input id="added-mile-rate" name="added-mile-rate"  class="multisteps-form__input form-control" type="text" placeholder="Added Mile Rate" disabled/>
						</div>

						<!-- Center the small text -->
						<div class="col-12 mt-2" style="display:none;">
							<small class="form-text text-muted text-center d-block mx-auto">Anything over 12.5 charge 3.41 per mile charge round trip in miles</small>
						</div>
					</div>
					 <div class="">
							<div class="form-row mt-4">
<!-- 							<button class="btn btn-primary btn-sm btn-services" id="add-removal-and-delivery" >REMOVAL AND DELIVERY</button> -->
							<button class="btn btn-primary btn-sm btn-services" id="add-college-room-move" >COLLEGE ROOM MOVE</button>
							<button class="btn btn-primary btn-sm btn-services" id="add-removal" >REMOVAL</button>
							<button class="btn btn-primary btn-sm btn-services" id="add-delivery" >DELIVERY</button>
							<button class="btn btn-primary btn-sm btn-services" id="add-hoisting" >HOISTING</button>
							<button class="btn btn-primary btn-sm btn-services" id="add-mattress-removal" >MATTRESS REMOVAL</button>
							<button class="btn btn-primary btn-sm btn-services" id="add-re-arranging-service" >RE ARRANGING SERVICE</button>
							<button class="btn btn-primary btn-sm btn-services" id="add-cleaning-services" >CLEANING SERVICES</button>
							<button class="btn btn-primary btn-sm btn-services" id="add-exterminator-washing-replacing-moving-blankets" >Exterminator, Washing and Replacing Moving Blankets</button>
							<button class="btn btn-primary btn-sm btn-services" id="add-moving-services" >MOVING SERVICES</button>
						 </div>
						<div class="form-row mt-4" style="display:none;">
							<div class="col-12 col-sm-3">
								<label for="service-select" class="form-label">Services <span style="color: red;">*</span></label>
								<select id="service-select" class="form-control">
									<option value="">Select Here</option>
<!-- 									<option value="removal-and-delivery" data-rate="75">REMOVAL AND DELIVERY</option> -->
									<option value="college-room-move" data-rate="325">COLLEGE ROOM MOVE</option>
									<option value="removal" data-rate="125">REMOVAL</option>
									<option value="delivery" data-rate="0">DELIVERY</option>
									<option value="hoisting" data-rate="350">HOISTING</option>
									<option value="mattress-removal" data-rate="125">MATTRESS REMOVAL</option>
									<option value="re-arranging-service" data-rate="150">RE ARRANGING SERVICE</option>
									<option value="cleaning-services" data-rate="135">CLEANING SERVICES</option>
									<option value="exterminator-washing-replacing-moving-blankets" data-rate="650">Exterminator, Washing and Replacing Moving Blankets</option>
									<option value="moving-services" data-rate="0">MOVING SERVICES</option>
								</select>
							</div>
							<div class="col-12 col-sm-3  mt-4 mt-sm-0">
								<label for="service-rate" class="form-label">Service Rate</label>
								<span class="dollar-sign">$</span>
								<input id="service-rate" class="form-control" type="text" placeholder="Service Rate" disabled />
							</div>
							<div class="col-12 col-sm-3  mt-4 mt-sm-0">
								<label for="no-of-crew" class="form-label">No. of Crew</label>
								<input id="no-of-crew" class="form-control" type="number" placeholder="No. of Crew" min="1" />
							</div>
							<div class="col-12 col-sm-3  mt-4 mt-sm-0">
								<label for="crew-rate" class="form-label">Crew Rate</label>
								<span class="dollar-sign">$</span>
								<input id="crew-rate" class="form-control" type="text" placeholder="Crew Rate" value="50" disabled />
							</div>
						</div>
						<button class="btn btn-primary mt-3" id="add-service"  style="display:none;">Add Service</button>


						<h4 class="mt-3">Services Table</h4>
						 <div style="overflow-y: auto;overflow-x: auto;">							 
							<table class="table table-bordered mt-3" id="services-table">
								<thead>
									<tr>
										<th>Service</th>
										<th>Rate</th>
										<th>Crew</th>
										<th>Crew Rate</th>
										<th style="color:red;">Purchased Amount</th>
										<th>Delivery Cost</th>
										<th>Subtotal</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<!-- Rows will be dynamically added here -->
								</tbody>
								<tfoot>
									<tr>
										<td colspan="6" class="text-right"><strong>Grand Total:</strong></td>
										<td  colspan="2" id="total-amount">$0</td>
									</tr>
								</tfoot>
							</table>
						 </div>
					</div>
                  <div class="form-row mt-4" style="display:none;">
					<div class="col-6 col-sm-4 mt-4 mt-sm-0" style="display:none;">
						<label for="dropoff-address" class="form-label">Sub Total</label>
    						<span class="dollar-sign">$</span>
						<input id="sub-total" name="sub-total" class="multisteps-form__input form-control" type="text" placeholder="Sub Total" disabled/>
					</div>
					<div class="col-12 col-sm-6">
<!-- 						<label for="dropoff-address" class="form-label" >Delivery Rate</label>
    						<span class="dollar-sign">$</span> -->
						<input id="delivery-rate" name="delivery-rate" class="multisteps-form__input form-control" type="text" placeholder="Delivery Rate" disabled  style="display:none;" />
					</div>
					<div class="col-6 col-sm-6 mt-4 mt-sm-0">
						<label for="dropoff-address" class="form-label">Grand Total</label>
    						<span class="dollar-sign">$</span>
						<input id="grand-total"   name="grand-total"  class="multisteps-form__input form-control" type="text" placeholder="Grand Total" disabled/>
						<input id="downpayment"   name="downpayment"  class="multisteps-form__input form-control" type="text" placeholder="Grand Total" disabled/>
					</div>
				</div>
                  <div class="row">
                    <div class="button-row d-flex mt-4 col-12">
                      <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                      <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next" id="third">Next</button>
                    </div>
                  </div>
                </div>
              </div>
            <!-- Single Form Panel with Summary -->
				<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="slideHorz">
				  <h4 class="multisteps-form__title">Summary & Review</h4>
				  <div class="multisteps-form__content">
					<!-- Summary Table -->
					<div class="form-row mt-4">
					  <table class="table table-bordered">
						<thead>
						  <tr>
							<th></th>
							<th>Details</th>
						  </tr>
						</thead>
						<tbody id="summary-table">
						  <!-- Summary rows will be dynamically populated -->
						</tbody>
					  </table>
					</div>
					  
					  
					  <h5 class="multisteps-form__title">To confirm your delivery, call  <a href="tel:18483598030">+1-848-359-8030</a><br><br>
					For Full Assembly cost may apply.<br><br>
					We call the night before your delivery between 530pm and 830pm to schedule a time for delivery.<br><br>
					We accept the following forms of payment: Cash, Zelle, Venmo, Cash App, and Credit Cards. Credit cards are subject to additional processing fees.</h5>

				
					<!-- Navigation Buttons -->
					<div class="button-row d-flex mt-4">
					  <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
					  <button  id="openModalBtn" class="btn btn-success ml-auto" type="button" title="Send">Submit Now !</button>
					</div>
					  
					  
				  </div>
				</div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>


<div id="customModal_moving_services" class="modal-overlay">
  <div class="modal-content">
    <span class="close-btn" id="close-btn-x-moving">&times;</span>
    <h3>Movers</h3>
	<p>3 hours minimum (2 hours labor, 1 hour travel) </p>
    
					  <div class="form-row mt-4 ">
						<div class="col-12 col-sm-6 mt-sm-0">
							  <div class="form-row">
								<button class="btn btn-primary btn-sm btn-moving" id="add-2men" style="width: 100%;">2 MEN CREW</button>
								<button class="btn btn-primary btn-sm btn-moving" id="add-3men" style="width: 100%;">3 MEN CREW</button>
								<button class="btn btn-primary btn-sm btn-moving" id="add-4men" style="width: 100%;">4 MEN CREW</button>
								<button class="btn btn-primary btn-sm btn-moving" id="add-5men" style="width: 100%;">5 MEN CREW</button>
								<button class="btn btn-primary btn-sm btn-moving" id="add-3menspecial" style="width: 100%;">3 MEN SPECIAL</button>
							 </div>
						 </div>
						<div class="col-12 col-sm-6 mt-sm-0">
						  <div class="form-row" style="text-align: left;">
        						<div class="col-12 col-sm-12 mt-sm-0">
        								<label for="added-miles" class="form-label">Additional Hours</label>
        								<!--<input id="added-hours" name="added-hours" class="multisteps-form__input form-control" type="number" placeholder="Additional Hours"  min="2" step="1" value="2">	-->
        									<div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" id="decrease-hours">-</button>
                                                <input id="added-hours" name="added-hours" 
                                                    class="multisteps-form__input form-control text-center" 
                                                    type="number" 
                                                    placeholder="Additional Hours" 
                                                    min="2" step="1" value="2">
                                                <button type="button" class="btn btn-outline-secondary" id="increase-hours">+</button>
                                            </div>
        							</div>
							  <div class="col-12 col-sm-12  mt-2 mt-sm-0"  style="margin-top: 14px !important;">
								<label for="no-of-crew" class="form-label">No. of Crew</label>
								<input id="numbercrew" name="numbercrew" class="multisteps-form__input form-control" type="number" placeholder="Number Crew" readonly>
							  </div>
							  <div class="col-12 col-sm-12  mt-2 mt-sm-0"  style="margin-top: 14px !important;">
								<label for="no-of-crew" class="form-label">Total Costs</label>
								<input id="total-hours-added" name="total-hours-added" class="multisteps-form__input form-control" type="text" placeholder="Total Costs" disabled="">	
								<input id="men-selected" name="men-selected" class="multisteps-form__input form-control" type="text" placeholder="men-selected" disabled="" style="display:none;">	
							  </div>
							  <div class="col-12 col-sm-12  mt-2 mt-sm-0"  style="margin-top: 14px !important;">				
								<button class="btn btn-primary btn-sm" id="add-sevice-moving" name="add-sevice-moving" style="width: 100%;">Add Service</button>
							  </div>
						  </div>
						 </div>
					</div>
  </div>
</div>

<div id="customModal" class="modal-overlay">
  <div class="modal-content">
    <span class="close-btn" id="close-btn-x" >&times;</span>
    <h3>Card Payment</h3>
    
					  <div class="container">
						 <!-- Stripe Card Element -->
							  <div id="payment-element">
								<!--Stripe.js injects the Payment Element-->
							  </div>
							  <button id="submit-now-online"  style="width:100%" class="btn btn-primary btn-lg mb-3 mt-3">
								<div class="spinner hidden" id="spinner"></div>
								<span id="button-text">Pay now</span>
							  </button>
							  <div id="payment-message" class="hidden"></div>
						
						  
<!-- 								<button class="btn btn-primary btn-lg mb-3" type="button" id="submit-now" name="submit-now" style="width:100%">Submit Now !</button> -->
					</div>
	  
<!--   								<button id="closeModalBtn" class="btn  btn-danger btn-lg"  style="width:100%">Close Modal</button> -->
  </div>
</div>

<!-- <button id="openModalBtn" class="btn">Open Modal</button> -->

 <script src="https://js.stripe.com/v3/"></script>
<script>
window.onload = function() {
        document.getElementById("noofitems").value = 1;
    };
    
    
document.getElementById("increase-hours").addEventListener("click", function() {
    let input = document.getElementById("added-hours");
    input.stepUp();  // Increases by 1
    input.dispatchEvent(new Event('change')); // Manually trigger onchange
});

document.getElementById("decrease-hours").addEventListener("click", function() {
    let input = document.getElementById("added-hours");
    if (input.value > input.min) {
        input.stepDown();  // Decreases by 1
        input.dispatchEvent(new Event('change')); // Manually trigger onchange
    }
});

// Ensure minimum value enforcement on manual input
document.getElementById('added-hours').addEventListener('change', function() {
    let value = parseInt(this.value);
    if (isNaN(value) || value < 2) {
        this.value = 2;  // Reset to min value if invalid
    }
});

	
	const serviceSelect = document.getElementById("service-select");
	const addServiceButton = document.getElementById("add-service");

	// Attach a single click event listener for all buttons with the class 'btn-services'
	document.querySelectorAll(".btn-services").forEach((button) => {
	  button.addEventListener("click", (event) => {
		  event.preventDefault();
		// Check if the button is NOT the "add-moving-services" button
		if (button.id !== "add-moving-services") {
		  // Extract the service value from the button's id
		  const serviceValue = button.id.replace("add-", ""); // Remove "add-" prefix to match the option value

		  // Set the dropdown value to match the service
		  serviceSelect.value = serviceValue;

		  // Dispatch the change event to trigger any related listeners
		  const event = new Event("change");
		  serviceSelect.dispatchEvent(event);

		  // Simulate clicking the "Add Service" button
		  addServiceButton.click();
		}else if(button.id === "add-sevice-moving"){
		} else {
		  // Handle the specific case for "add-moving-services" button
		document.getElementById("customModal_moving_services").style.display = "flex";
		}
	  });
	});

	
	
	
jQuery(document).ready(function($) {
	
	// Get modal, open button, close button, and close icon
const modal = document.getElementById("customModal");
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtn");
const closeModalIcon = document.getElementById("close-btn-x");

	
// This is your test publishable API key.
const stripe = Stripe("pk_live_51PYqsoIv2nL0m0UJFFW1W6ibO4xSZfGppAluS99Hd7LXA2O8z1f7QX2mDoxvg7u8xxfZncuddOFHx0NkJewIFZUD00AMo2KMRV");


let elements;
// Function to open modal
function openModal() {
	
		
		 // Collect services table data
        const servicesTable = document.getElementById('services-table');
		const itemsnumber = document.getElementById('noofitems');
		const fileInput = document.getElementById('images');
		const validServices = [
			  'removal and delivery', 
			  'removal', 
			  'delivery', 
			  'mattress removal', 
			  'COLLEGE ROOM MOVE', 
			  'hoisting', 
			  're arranging service', 
			  'cleaning services',
			  'exterminator, washing and replacing moving blankets',
			  'moving services'
			];
    	const progressButton = document.querySelector('button[title="Address"]');
	
	
	
	const requiredFields = [
        document.getElementById('first-name'),
        document.getElementById('last-name'),
        document.getElementById('move-date'),
        document.getElementById('phone-number'),
        document.getElementById('email')
    ];

    // Check if all required fields are filled
    const allFilled = requiredFields.every(field => field.value.trim() !== "");

    if (!allFilled) {
        Swal.fire({
            title: 'Validation Error',
            text: 'Please fill in all required fields.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
		
		  if (progressButton) {
        progressButton.click(); // Simulate button click
    }

    return;
    }
	
	const services = [];
if (servicesTable) {
    const serviceRows = servicesTable.querySelectorAll('tr');
    serviceRows.forEach((row) => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 7) { // Ensure the row has enough cells
            services.push({
                serviceName: cells[0].textContent.trim(),
                serviceRate: cells[1].textContent.trim(),
                noOfCrew: cells[2].textContent.trim(),
                crewRate: cells[3].textContent.trim(),
                purchasedAmount: cells[4].querySelector('input.purchased-amount')?.value || null,
                deliveryCost: cells[5].textContent.trim(),
                serviceTotal: cells[6].textContent.trim(),
            });
        }
    });
}
	
const hasValidService = services.some(service => {
    return validServices.includes(service.serviceName.toLowerCase());
});

const itemsValue = parseInt(itemsnumber.value, 10);
const fileCount = fileInput ? fileInput.files.length : 0; // Default to 0 if fileInput is null


// Validate if any valid service is selected and the number of items is zero or other conditions
if (hasValidService && (itemsValue === 0 || !itemsnumber.value || fileCount === 0)) {
    let message = '';

    if (!itemsnumber.value || itemsValue === 0) {
        message = 'Number of items is missing or zero. Please enter a valid number.';
    } else if (fileCount === 0) {
        message = 'No Image Added. Please upload at least one image.';
    } else if (hasValidService && itemsValue === 0) {
//     message = 'For the "REMOVAL", "DELIVERY", "MATTRESS REMOVAL", or "COLLEGE ROOM MOVE" service, the number of items cannot be zero.';
    message = 'The number of items cannot be zero.';
    }

    Swal.fire({
        title: 'Validation Error',
        text: message,
        icon: 'warning',
        confirmButtonText: 'OK',
    });

    if (progressButton) {
        progressButton.click(); // Simulate button click
    }

    return;
}
	
	
	
	  		 // Check if services array is empty and show Swal if no data
    if (services.length === 0) {
        Swal.fire({
            title: 'No Services Added',
            text: 'There are no services available in the table.',
            icon: 'warning',
            confirmButtonText: 'OK',
        });
		const progressButton = document.querySelector('button[title="Order Info"]');
        if (progressButton) {
            progressButton.click();  // Simulate button click
        }
		return;
    }
 

  // Show loading Swal
  const loadingSwal = Swal.fire({
    title: 'Loading...',
    text: 'Please wait while we prepare the payment details.',
    allowOutsideClick: false, // Prevent closing the modal during loading
    didOpen: () => {
      Swal.showLoading(); // Show the loading spinner
    }
  });
	
	const servicesTableBody = document.querySelector("#services-table tbody");
	let containsMovingServices = false; 
	
    // Loop through all rows in the table to calculate the subtotal
    servicesTableBody.querySelectorAll("tr").forEach((row) => {
       
		 // Check if the row contains "MOVING SERVICES"
        const serviceName = row.cells[0].textContent.trim(); // Assuming service name is in the first column
        if (serviceName === "MOVING SERVICES") {
            containsMovingServices = true;
        }
    });
	
let  grandTotal = 0;
	
	if(containsMovingServices){		
	  let price = document.getElementById('downpayment').value;
	  grandTotal = price;
	}
	else{
	  // Get the value from the grand-total field
	  grandTotal = parseFloat(document.getElementById('grand-total').value);
	}

  // The items the customer wants to buy
  const items = [{ id: "Competitive Relocation Services", amount: grandTotal * 100 }];  // Use the value from the grand-total field

  // Get the customer input values
  const firstName = $("#first-name").val();
  const lastName = $("#last-name").val();
  const phoneNumber = $("#phone-number").val();
  const email = $("#email").val();

  $.ajax({
    url: ajaxurl, // WordPress AJAX URL
    type: 'POST',
    data: {
      action: 'fetch_payment_intent', // Custom action to fetch the payment intent
      items: JSON.stringify(items),
      customer_email: email,   // Send customer email
      customer_first_name: firstName, // Send customer first name
      customer_last_name: lastName,   // Send customer last name
      customer_phone: phoneNumber,    // Send customer phone number
    },
    success: function(response) {

      if (response.success) {
        const { clientSecret } = response.data;
        elements = stripe.elements({ clientSecret });

        const paymentElementOptions = {
          layout: "accordion",
        };

        const paymentElement = elements.create("payment", paymentElementOptions);
        paymentElement.mount("#payment-element");
		  
  
		  setTimeout(() => {
        // Close the loading Swal once the response is received
        loadingSwal.close();
        modal.style.display = "flex"; // Display as flex for centering
      }, 2000); // 2000 milliseconds = 2 seconds
		  
      } else {
        Swal.fire({
          title: 'Error',
          text: response.data.error,
          icon: 'error',
          confirmButtonText: 'OK',
        });
      }
    },
    error: function() {
      // Close the loading Swal if there's an error
      loadingSwal.close();
      Swal.fire({
        title: 'Error',
        text: 'An error occurred while initializing the payment.',
        icon: 'error',
        confirmButtonText: 'OK',
      });
    }
  });
}


// Function to close modal
function closeModal() {
  modal.style.display = "none";
}

document.getElementById('close-btn-x-moving').addEventListener('click', function() {
  document.getElementById('customModal_moving_services').style.display = 'none';
});

document.getElementById('close-btn-x').addEventListener('click', function() {
  modal.style.display = 'none';
});


// Event listeners for open and close actions
openModalBtn.addEventListener("click", openModal);



 initialize();
	
document
  .querySelector("#submit-now-online") // Select the submit button with id="submit-now-online"
  .addEventListener("click", handleSubmit); // Use the click event instead of form submit

// Fetches a payment intent and captures the client secret
async function initialize() {
	
}
	
			
function initiateTransfer(paymentId) {
    $.ajax({
        url: ajaxurl, // WordPress AJAX URL
        type: 'POST',
        data: {
            action: 'initiate_transfer', // WordPress action hook
            paymentId: paymentId // Payment ID passed to the handler
        },
        success: function(response) {
            if (response.success) {
                console.log('Transfer Successful!');
               
										
										Swal.fire({
											title: 'Data Submitted!',
											text: 'Your data has been successfully submitted and saved to our calendar. We will call you as soon as possible.',
											icon: 'success',
											confirmButtonText: 'OK'
										}).then(() => {
											$('.multisteps-form__form')[0].reset();
											window.location.href = 'https://competitiverelocation.com/delivery-services/';  
										});
				
            } else {
                // Show error message if the response indicates a failure
                Swal.fire({
                    title: 'Transfer Error!',
                    text: response.data.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function() {
            // If there's an unexpected error, show the error message
            Swal.fire({
                title: 'Transfer Error!',
                text: 'An unexpected error occurred. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}


async function handleSubmit(e) {
  e.preventDefault();
  setLoading(true);

const { error, paymentIntent } = await stripe.confirmPayment({
    elements,
    confirmParams: {
  
    },
    redirect: 'if_required' 
  });

 if (error) {
//     showMessage(error.message);
	    Swal.fire({
			  title: 'Error',
			  text: error.message,
			  icon: 'error',
			  confirmButtonText: 'OK',
			});
  } else if (paymentIntent) {
//     showMessage("An unexpected error occurred.");
	    const paymentId = paymentIntent.id; // The Payment ID (PaymentIntent ID)	  
	    let softwareFee =0;	  
	    let truckFee =0;
	    let estimated =0;	  
	    let downpayment =0;
	    let remaining =0;
	    let containsMovingServices = false; 
		const servicesTableBody = document.querySelector("#services-table tbody");
            // Loop through all rows in the table to calculate the subtotal
            servicesTableBody.querySelectorAll("tr").forEach((row) => {
               
        		 // Check if the row contains "MOVING SERVICES"
                const serviceName = row.cells[0].textContent.trim(); // Assuming service name is in the first column
                if (serviceName === "MOVING SERVICES") {
                    containsMovingServices = true;
                }
            });
            
            if(containsMovingServices){
                
            softwareFee = parseFloat(document.getElementById('software_fee').textContent.replace('$', ''));
            truckFee = parseFloat(document.getElementById('truck_fee').textContent.replace('$', ''));
            estimated = parseFloat(document.getElementById('estimated').textContent.replace('$', ''));
            downpayment = (document.getElementById('grand-total').value *  0.315).toFixed(2);
            remaining = parseFloat(document.getElementById('remaining_table').textContent.replace('$', ''));
                
                
            }
      
	  
		// Collect form data
        var formData = new FormData(); // Use FormData for file uploads
        formData.append('action', 'save_transaction_data'); // WordPress AJAX action
		// Append payment ID to form data
		formData.append('payment_id', paymentId);
		
		  if(containsMovingServices){
                
        		formData.append('software_fee', softwareFee);
        		formData.append('truck_fee', truckFee);
        		formData.append('estimated', estimated);
        		formData.append('downpayment', downpayment);
        		formData.append('remaining', remaining);
                
            }
	  
	  
        // Get agent ID from URL, default to 0 if not present
        const urlParams = new URLSearchParams(window.location.search);
        const agentId = urlParams.get('agent') || '0';
	  
        formData.append('agent_id', agentId);
	  
        // Append text fields
        var fields = [
            'pickup-location','dropoff-address', 'calculated-miles', 
            'first-name', 'last-name', 'phone-number', 'email',  'sales_name',  'sales_email',  'sales_location', 
            'added-miles', 'added-mile-rate', 'delivery-rate', 'grand-total', 'move-date', 'noofitems', 'nonce'
        ];

        fields.forEach(field => {
            formData.append(field, $(`#${field}`).val());
        });

		const imageInput = $('#images')[0];
	if (imageInput && imageInput.files.length > 0) {
		console.log("Files selected: ", imageInput.files); // Check if files are selected
		Array.from(imageInput.files).forEach((file) => {
			formData.append('images[]', file); // Append files to FormData
		});
	} else {
		console.log("No files selected or invalid input.");
	}
		
	
		
		 // Collect services table data
        const servicesTable = document.getElementById('services-table');
        const services = [];
        if (servicesTable) {
            const serviceRows = servicesTable.querySelectorAll('tr');
            serviceRows.forEach((row) => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 7) { // Ensure the row has enough cells
                    services.push({
                        serviceName: cells[0].textContent.trim(),
                        serviceRate: cells[1].textContent.trim(),
                        noOfCrew: cells[2].textContent.trim(),
                        crewRate: cells[3].textContent.trim(),
                        purchasedAmount: cells[4].querySelector('input.purchased-amount')?.value || null,
                        deliveryCost: cells[5].textContent.trim(),
                        serviceTotal: cells[6].textContent.trim(),
                    });
                }
            });
        }
	  
	  		 // Check if services array is empty and show Swal if no data
    if (services.length === 0) {
        Swal.fire({
            title: 'No Services Added',
            text: 'There are no services available in the table.',
            icon: 'warning',
            confirmButtonText: 'OK',
        });
		const progressButton = document.querySelector('button[title="Order Info"]');
        if (progressButton) {
            progressButton.click();  // Simulate button click
        }
		return;
    }
		
		
        // Add services array to form data
        formData.append('services', JSON.stringify(services));
		
		
			Swal.fire({
								title: 'Submitting...',
								text: 'Please wait while we process your request.',
								allowOutsideClick: false,
								allowEscapeKey: false,
								didOpen: () => {
									Swal.showLoading();
								}
							});

							// AJAX request
							$.ajax({
								url: ajaxurl, // WordPress AJAX URL
								type: 'POST',
								processData: false,
								contentType: false,
								data: formData,
								success: function(response) {
									if (response.success) {
										
										initiateTransfer(paymentId);
									} else {
										// Display error messages
										const errorMessages = response.data.errors.join('\n');
										Swal.fire('Upload Error!', errorMessages, 'error');
									}
								},
								error: function() {
									Swal.fire('Error!', 'An unexpected error occurred. Please try again.', 'error');
								}
							});
    
  }

  setLoading(false);
}

// ------- UI helpers -------

function showMessage(messageText) {
  const messageContainer = document.querySelector("#payment-message");

  messageContainer.classList.remove("hidden");
  messageContainer.textContent = messageText;

  setTimeout(function () {
    messageContainer.classList.add("hidden");
    messageContainer.textContent = "";
  }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector("#submit-now-online").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("#submit-now-online").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
    document.querySelector("#button-text").classList.remove("hidden");
  }
}
	
    });


</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
 document.addEventListener("DOMContentLoaded", () => {
    const serviceSelect = document.getElementById("service-select");
    const serviceRate = document.getElementById("service-rate");
    const noOfCrew = document.getElementById("no-of-crew");
    const noOfItems = document.getElementById("noofitems");
    const crewRate = document.getElementById("crew-rate");
    const addServiceBtn = document.getElementById("add-service");
    const servicesTableBody = document.querySelector("#services-table tbody");
    const totalAmountCell = document.getElementById("total-amount");

    let totalAmount = 0;

    // Update service rate when a service is selected
//     serviceSelect.addEventListener("change", () => {
//         const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
//         const rate = selectedOption.getAttribute("data-rate") || 0;
//         serviceRate.value = rate;
//     });
     if (serviceSelect && serviceRate) {
    serviceSelect.addEventListener("change", () => {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        
        // Check if the selected option exists and has the "data-rate" attribute
        const rate = selectedOption && selectedOption.hasAttribute("data-rate") 
            ? selectedOption.getAttribute("data-rate") 
            : 0;

        serviceRate.value = rate;
    });
}

    // Function to calculate equivalent delivery cost
    function calculateDeliveryCost(subTotal) {
        if (subTotal < 750) return 79.99;
        if (subTotal >= 750 && subTotal < 1500) return 159.99;
        if (subTotal >= 1500 && subTotal < 2000) return 179.99;
        if (subTotal >= 2000 && subTotal < 2750) return 194.99;
        if (subTotal >= 2750 && subTotal < 4000) return 224.99;
        if (subTotal >= 4000 && subTotal < 6000) return 284.99;
        if (subTotal >= 6000 && subTotal < 8000) return 314.99;
        if (subTotal >= 8000 && subTotal < 10000) return 334.99;
        if (subTotal >= 10000) return 384.99;
        return 0;
    }

 // Function to recalculate total from all subtotals in the table
    function recalculateTotal() {		
		
		let containsMovingServices = false; 
		
    const addedMileRate = parseFloat(document.getElementById("added-mile-rate").value) || 0;
        let total = 0;
        servicesTableBody.querySelectorAll("tr").forEach((row) => {
            const subtotalCell = row.cells[6]; // Subtotal is in the 6th column (index 5)
            const subtotal = parseFloat(subtotalCell.textContent.replace("$", "")) || 0;
            total += subtotal;
			
		 // Check if the row contains "MOVING SERVICES"
        const serviceName = row.cells[0].textContent.trim(); // Assuming service name is in the first column
        if (serviceName === "MOVING SERVICES") {
            containsMovingServices = true;
        }
        
		});
		
		
		
		 // If "MOVING SERVICES" is found, apply transaction fee and truck fee
			if (containsMovingServices) {
				const truckFee = 198.80 + addedMileRate; // Fixed Truck Fee
				const transactionFee = (total + 198.80) * 0.12; // 12% of Grand Total
				total += transactionFee + truckFee; // Add both fees to Grand Total
			}
		else{
			total += addedMileRate;
		}
		
        totalAmountCell.textContent = `$${total.toFixed(2)}`;
    }
	 
    // Add a new service row to the table
    addServiceBtn.addEventListener("click", (event) => {
		
    event.preventDefault(); // Prevent page refresh
		
		const serviceSelect = document.getElementById("service-select");
        const serviceName = serviceSelect.options[serviceSelect.selectedIndex].text;
        const serviceValue = serviceSelect.value;
        const rate = parseFloat(serviceRate.value) || 0;
        const noofitems = parseFloat(noOfItems.value) || 0;
        const crewCount = parseInt(noOfCrew.value) || 0;
        const ratePerCrew = parseFloat(crewRate.value) || 0;

        let removaladd = 0;
        let subtotal = 0;
		
// Validation: Check if required fields are filled
    if (serviceSelect.selectedIndex === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Service Required',
            text: 'Please select a service',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
        return; // Stop execution if validation fails
    }

        const isEditable =
            serviceValue === "delivery" || serviceValue === "removal-and-delivery";
        const purchasedAmountInput = isEditable
            ? `<input style="color:red;" type="number" class="form-control purchased-amount" placeholder="Enter Amount" />`
            : `<input style="color:red;" type="number" class="form-control purchased-amount" placeholder="Not Applicable" disabled />`;
		
        const deliveryCost = calculateDeliveryCost(purchasedAmountInput.value);
//         const subtotal = rate +  ratePerCrew + deliveryCost + removaladd;

		if(serviceValue === "removal"){
			if(noofitems < 4){
				removaladd = (noofitems - 1 ) * 75;
        		subtotal =  rate +  ratePerCrew + deliveryCost + removaladd;
			}
			else{
				removaladd = (noofitems ) * 75;
        		subtotal = ratePerCrew + deliveryCost + removaladd;
			}
		}
		else{
        		subtotal =  rate +  ratePerCrew + deliveryCost;
		}


        totalAmount += subtotal;

        // Add row to table
        const row = document.createElement("tr");
        row.innerHTML = `
            <td style="vertical-align: middle;">${serviceName}</td>
            <td style="vertical-align: middle;">$${rate.toFixed(2)}</td>
            <td style="vertical-align: middle;">${crewCount}</td>
            <td style="vertical-align: middle;">$${ratePerCrew.toFixed(2)}</td>
            <td style="vertical-align: middle;">${purchasedAmountInput}</td>
            <td style="vertical-align: middle;">$${deliveryCost.toFixed(2)}</td>
            <td style="vertical-align: middle;">$${subtotal.toFixed(2)}</td>
            <td style="vertical-align: middle;"><button class="btn btn-danger btn-sm delete-row" style="padding: 5px 16px !important;font-size: 14px !important;">Delete</button></td>
        `;
        servicesTableBody.appendChild(row);

       recalculateTotal();
		updateDeliveryRate();

        // Reset form fields
        serviceSelect.value = "";
        serviceRate.value = "";
        noOfCrew.value = "";
		
		 // Simulate clicking the "Order Info" button (first tab)
    const orderInfoButton = document.querySelector('button[title="Order Info"]');
    if (orderInfoButton) {
        orderInfoButton.click();
    }
		
    });

    // Delete a row from the table
    servicesTableBody.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-row")) {
            const row = e.target.closest("tr");
            row.remove();
            recalculateTotal();
			updateDeliveryRate();
        }
    });

    // Update total and equivalent delivery cost when "Purchased Amount" is edited
    servicesTableBody.addEventListener("input", (e) => {
        if (e.target.classList.contains("purchased-amount")) {
            const row = e.target.closest("tr");
            const purchasedAmount = parseFloat(e.target.value) || 0;
            const rate = parseFloat(row.cells[1].textContent.replace("$", "")) || 0;
            const crewCount = parseInt(row.cells[2].textContent) || 0;
            const crewRate = parseFloat(row.cells[3].textContent.replace("$", "")) || 0;

            const newDeliveryCost = calculateDeliveryCost(purchasedAmount);
            const newSubtotal = rate +  crewRate + newDeliveryCost;

            // Update subtotal and delivery cost
            row.cells[6].textContent = `$${newSubtotal.toFixed(2)}`;
            row.cells[5].textContent = `$${newDeliveryCost.toFixed(2)}`;

            // Recalculate total
            recalculateTotal();
			updateDeliveryRate();
        }
    });
});
    </script>
<script>
  

	// Get the current date
    const today = new Date().toISOString().split('T')[0];
    // Set the min attribute of the date input field
    document.getElementById('move-date').setAttribute('min', today)


const dollarFields = ['added-mile-rate', 'grand-total'];
	
  fields.forEach((fieldId) => {
    const element = document.getElementById(fieldId);
    if (element) {
		let value = element.value || 'Not provided'; // Get the value or use default if empty
		if (dollarFields.includes(fieldId) && value !== 'Not provided') {
		  value = `$${value}`; // Add dollar sign for specific fields
		}
      formData[fieldId] = value; // Store the value in formData using the field ID as the key
    }
  });

  return formData;
}


// // Create a mapping of services to their corresponding rates
const serviceRates = {
    "removal-and-delivery": 75.00,
    "college-room-move": 325.00,
    "removal": 125.00,
    "delivery": 0.00,
    "hoisting": 350.00,
    "mattress-removal": 125.00,
    "re-arranging-service": 150.00,
    "cleaning-services": 135.00,
    "exterminator-washing-replacing-moving-blankets": 650.00,
    "moving-services": 650.00
};

const serviceCrewCount = {
    "removal-and-delivery": 2,
    "college-room-move": 2,
    "removal": 2,
    "delivery": 2,
    "hoisting": 4,
    "mattress-removal": 2,
    "re-arranging-service": 2,
    "cleaning-services": 2,
    "exterminator-washing-replacing-moving-blankets": 2,
    "moving-services": 2
};


// // Function to update Delivery Rate based on Sub Total
function updateDeliveryRate() {
    const addedMileRate = parseFloat(document.getElementById("added-mile-rate").value) || 0;
    const servicesTableBody = document.querySelector("#services-table tbody");
    let tableSubTotal = 0;
    let hasDeliveryService = false;

    // Loop through all rows in the table
    servicesTableBody.querySelectorAll("tr").forEach((row) => {
        const serviceCell = row.cells[0]; // Service column (1st column, index 0)
        const subtotalCell = row.cells[5]; // Subtotal column (6th column, index 5)
        const subtotal = parseFloat(subtotalCell.textContent.replace("$", "")) || 0;

        // Check if the service matches "delivery" or "removal-and-delivery"
        if (serviceCell.textContent.trim() === "DELIVERY" || serviceCell.textContent.trim() === "REMOVAL AND DELIVERY") {
            hasDeliveryService = true;
        }

        // Add subtotal to the total
        tableSubTotal += subtotal;
    });

    // Add the added-mile-rate to the subtotal
    const subTotal = tableSubTotal + addedMileRate;

    if (hasDeliveryService) {
        document.getElementById("delivery-rate").value = "0.00";
    } else {
        document.getElementById("delivery-rate").value = "0.00"; // Default to 0 if no matching service is found
    }
	
    updateGrandTotal();
}

function updateGrandTotal() {
    const addedMileRate = parseFloat(document.getElementById("added-mile-rate").value) || 0;
    const servicesTableBody = document.querySelector("#services-table tbody");
    const deliveryRate = parseFloat(document.getElementById("delivery-rate").value) || 0;
    let tableSubTotal = 0;
	let containsMovingServices = false; 

    // Loop through all rows in the table to calculate the subtotal
    servicesTableBody.querySelectorAll("tr").forEach((row) => {
        const subtotalCell = row.cells[6]; // Subtotal column (6th column, index 5)
        const subtotal = parseFloat(subtotalCell.textContent.replace("$", "")) || 0;
        tableSubTotal += subtotal;
		
		 // Check if the row contains "MOVING SERVICES"
        const serviceName = row.cells[0].textContent.trim(); // Assuming service name is in the first column
        if (serviceName === "MOVING SERVICES") {
            containsMovingServices = true;
        }
    });

	 // If "MOVING SERVICES" is found, apply transaction fee and truck fee
			if (containsMovingServices) {
				const truckFee = 198.80 + addedMileRate; // Fixed Truck Fee
				const transactionFee = (tableSubTotal + 198.80) * 0.12; // 11.3% of Grand Total
				tableSubTotal += transactionFee + truckFee; // Add both fees to Grand Total
			}
		else{
			tableSubTotal += addedMileRate;
		}

    // Update the Grand Total field
    document.getElementById("grand-total").value = tableSubTotal.toFixed(2);
	
}

// Add event listener for changes in the Service selection
document.getElementById("service-select").addEventListener("change", function() {
    const selectedService = this.value;
    const serviceRateInput = document.getElementById("service-rate");
    const crewCountInput = document.getElementById("no-of-crew");
    const crewRateInput = document.getElementById("crew-rate");
    const items = document.getElementById("noofitems");
	
    // If a valid service is selected, update the rate and number of crew
    if (serviceRates[selectedService]) {
        crewCountInput.value = serviceCrewCount[selectedService];
		
 		 if (selectedService !== "college-room-move" && selectedService !== "removal" && selectedService !== "removal-and-delivery" && selectedService !== "mattress-removal"  && selectedService !== "cleaning-services"  && selectedService !== "exterminator-washing-replacing-moving-blankets") {
       		 crewRateInput.value = (serviceCrewCount[selectedService] * 48.75).toFixed(2); // Crew rate based on number of crew
        	 serviceRateInput.value = serviceRates[selectedService].toFixed(2); 
		 }		
		else{
			if(items.value > 1){
				const servicetotal = serviceRates[selectedService] + (items.value * 75) 
				serviceRateInput.value = servicetotal.toFixed(2);
			}
			else{
				serviceRateInput.value = serviceRates[selectedService].toFixed(2) ; 
			}
			crewRateInput.value = 0;
		}
    } else {
        serviceRateInput.value = "";
        crewCountInput.value = "";
        crewRateInput.value = "";
    }

    // Recalculate Sub Total, Delivery Rate, and Grand Total
//     updateSubTotal();
    updateDeliveryRate();
    updateGrandTotal();
});

// Add event listener for changes in the Number of Crew input
document.getElementById("no-of-crew").addEventListener("input", function() {
    const selectedService = document.getElementById("service-select").value;
    const crewCount = parseInt(this.value) || 0;
    const crewRateInput = document.getElementById("crew-rate");

    // Calculate the crew rate (number of crew * 48.75)
    if (serviceCrewCount[selectedService]) {
        const crewRate = crewCount * 48.75;
        crewRateInput.value = crewRate.toFixed(2);
    }

    // Recalculate Sub Total, Delivery Rate, and Grand Total
//     updateSubTotal();
    updateDeliveryRate();
    updateGrandTotal();
});
	
	
 // Define base costs for each crew size
  const crewCosts = {
    '2men': 175,
    '3men': 201.50,
    '4men': 253.50,
    '5men': 316.88,
    '3menspecial': 125
  };
	
	  const crewNumber = {
    '2men': 2,
    '3men': 3,
    '4men': 4,
    '5men': 5,
    '3menspecial': 3
  };

  // Reference to the total hours added field
  const totalHoursField = document.getElementById('total-hours-added');
  const numbercrew = document.getElementById('numbercrew');

  // Track selected base cost
  let baseCost = 0;

  // Attach event listeners to crew buttons
  document.querySelectorAll('.btn-moving').forEach(buttons => {
    buttons.addEventListener('click', () => {
      // Get the crew size from the button ID (e.g., '2men', '3men', etc.)
      const crewSize = buttons.id.replace('add-', '');
	  totalHoursField.value=0;
	  
	  
	   document.getElementById('added-hours').value = '2';
	   
	   document.getElementById('men-selected').value = crewSize;
	   
		if(crewSize == "3menspecial")
		{
		    numbercrew.value=crewNumber[crewSize];
            baseCost = crewCosts[crewSize];
            // Update total cost display
            totalHoursField.value = (baseCost * document.getElementById('added-hours').value).toFixed(2); // Show the base cost in the total cost field
		}
		else{
		    
      // Update the base cost based on selected crew size
      if (crewCosts[crewSize]) {		  
		numbercrew.value=crewNumber[crewSize];
        baseCost = crewCosts[crewSize];
        // Update total cost display
        totalHoursField.value = (baseCost * crewNumber[crewSize]).toFixed(2); // Show the base cost in the total cost field
      }
		}
    });
  });

  // Attach event listener to added hours input
  document.getElementById('added-hours').addEventListener('change', (e) => {
    // Get the number of additional hours
		let addedHours = parseInt(e.target.value, 10);

	  // If addedHours is NaN, less than 1, or negative, reset to 0
	  if (isNaN(addedHours) || addedHours < 2) {
		addedHours = 2;
		e.target.value = '2';  // Clear input if it's invalid, negative, or less than 1
	  }

	  // If a valid number of hours (whole number >= 1) is entered, calculate total cost
	  if (addedHours >= 2) {
  	    const totalCost = (baseCost * addedHours).toFixed(2);
		totalHoursField.value = totalCost; // Update the total cost field
	  } else {
		totalHoursField.value = baseCost.toFixed(2); // Show only the base cost if no valid hours are entered
	  }
  });
	
	
	
  document.getElementById('add-sevice-moving').addEventListener('click', function (e) {
		
	const serviceSelect = document.getElementById("service-select");
	  
	  if (document.getElementById('numbercrew').value === '' || document.getElementById('numbercrew').value === '0') {
		Swal.fire({
			title: 'No Crew Selected',
			text: 'Please select the number of crew before proceeding.',
			icon: 'warning',
			confirmButtonText: 'OK',
		});

		return;
	}

		  // Set the dropdown value to match the service
		  serviceSelect.value = 'moving-services';

		  // Dispatch the change event to trigger any related listeners
		  const event = new Event("change");
		  serviceSelect.dispatchEvent(event);
	  
	  		document.getElementById('no-of-crew').value = document.getElementById('numbercrew').value;
	  		document.getElementById('crew-rate').value = document.getElementById('total-hours-added').value;
	  
		  // Simulate clicking the "Add Service" button
 		  addServiceButton.click();	  
  		  document.getElementById('customModal_moving_services').style.display = 'none';
});


</script>


<?php 
// Get footer
get_footer();