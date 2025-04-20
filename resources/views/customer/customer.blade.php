<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRService</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Preloader styles */
    #preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 50;
      opacity: 1;
      transition: opacity 0.5s ease;
    }

    #preloader.hide {
      opacity: 0;
      visibility: hidden;
    }

    .spinner {
      border: 4px solid rgba(255, 255, 255, 0.3);
      border-top: 4px solid #007bff;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>

<body class="font-sans bg-gray-100 text-gray-800 flex flex-col min-h-screen">

  <!-- Preloader -->
  <div id="preloader" class="fixed top-0 left-0 w-full h-full bg-white bg-opacity-90 flex justify-center items-center z-50">
    <span class="text-xl font-bold text-blue-500 animate-pulse">Loading...</span>
  </div>

  <!-- Header -->
  <header class="app-header flex justify-between items-center p-4 bg-blue-500 text-white shadow-md">
    <div class="flex flex-col items-start">
      <img src="assets/images/competitive.png" alt="" class="thumb-md rounded-circle" style="width:180px;">
      {{-- <span class="header-title text-lg font-semibold">CRServices</span> --}}
    </div>
    <div id="moving-top" class="text-xs text-right">
      <p>MOVING: <span class="font-semibold">#001 | Local - Residential</span></p>
      <p>MOVING LOAD: <span class="font-semibold">#001</span></p>
    </div>
  </header>
  
  <!-- Main Content -->
  <main class="flex-grow p-3 text-center border border-gray-300 m-2 mb-18 rounded-lg shadow-md" id="main-content">

    <!-- Placeholder for the selected menu page -->
    
  </main>

  <!-- Footer Navigation -->
  <footer class="w-full fixed bottom-0 bg-blue-500 flex justify-around items-center py-2 shadow-md">
    <a href="#customer-info" class="flex flex-col items-center text-white text-xs hover:text-yellow-400 hover:scale-110 transition-transform duration-300" onclick="navigate('customer-info')">
      <span class="text-xl mb-1">👤</span>
      <span>CUSTOMER INFO</span>
    </a>
    <a href="#estimates" class="flex flex-col items-center text-white text-xs hover:text-yellow-400 hover:scale-110 transition-transform duration-300" onclick="navigate('estimates')">
      <span class="text-xl mb-1">📊</span>
      <span>ESTIMATES</span>
    </a>
    <a href="#services" class="flex flex-col items-center text-white text-xs hover:text-yellow-400 hover:scale-110 transition-transform duration-300" onclick="navigate('services')">
      <span class="text-xl mb-1">🛠️</span>
      <span>SERVICES</span>
    </a>
    <a href="#payments" class="flex flex-col items-center text-white text-xs hover:text-yellow-400 hover:scale-110 transition-transform duration-300" onclick="navigate('payments')">
      <span class="text-xl mb-1">💳</span>
      <span>PAYMENTS</span>
    </a>
    <a href="#support" class="flex flex-col items-center text-white text-xs hover:text-yellow-400 hover:scale-110 transition-transform duration-300" onclick="navigate('support')">
        <span class="text-xl mb-1">💬</span>
        <span>CONTACT</span>
      </a>
  </footer>

  <script>
    // Preloader functionality
    document.addEventListener("DOMContentLoaded", () => {
      const preloader = document.getElementById("preloader");
      const footer = document.querySelector("footer");    
      const movingtop = document.getElementById("moving-top");
      const main = document.querySelector("main");
      
    //   movingtop.style.display = "none";
    //   main.style.display = "none";
    //   footer.style.display = "none";
      
      setTimeout(() => {
        preloader.classList.add("hide"); // Hide preloader after 2 seconds (or when content is ready)
      }, 2000); // Adjust time as needed for your content
    });

    const pages = {
      'customer-info': `
        <div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-md mb-20">
          <!-- Customer Details -->
          <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 px-2">Customer Details</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-x-8">
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div class="text-gray-600 font-medium">Full Name:</div>
                  <div>Jon Doe</div>
                  <div class="text-gray-600 font-medium">Email:</div>
                  <div class="break-all">email@email.com</div>
                  <div class="text-gray-600 font-medium">Move Date:</div>
                  <div>July 21, 2021</div>
                </div>
              </div>
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div class="text-gray-600 font-medium">Phone:</div>
                  <div>+1 236 254 4568</div>
                  <div class="text-gray-600 font-medium">Mobile Phone:</div>
                  <div>+1 236 254 4568</div>
                  <div class="text-gray-600 font-medium">Work Phone:</div>
                  <div>+1 236 254 4568</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Move From and Move To -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-8">
            <!-- Move From -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h2 class="text-xl font-bold text-gray-800 mb-4">Move From</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-gray-600 font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="text-gray-600 font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="text-gray-600 font-medium">City:</div>
                <div>New Jersey</div>
                <div class="text-gray-600 font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="text-gray-600 font-medium">State:</div>
                <div>Florida</div>
              </div>
            </div>

            <!-- Move To -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h2 class="text-xl font-bold text-gray-800 mb-4">Move To</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-gray-600 font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="text-gray-600 font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="text-gray-600 font-medium">City:</div>
                <div>New Jersey</div>
                <div class="text-gray-600 font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="text-gray-600 font-medium">State:</div>
                <div>Florida</div>
              </div>
            </div>
          </div>

          <!-- Inventory Items -->
          <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 px-2">Inventory Items</h2>
            <div class="bg-[#f0f7f7] rounded-lg overflow-x-auto">
              <table class="w-full min-w-[300px]">
                <thead>
                  <tr class="bg-[#f0f7f7]">
                    <th class="py-3 px-4 text-left w-24 font-semibold">Quantity</th>
                    <th class="py-3 px-4 text-left font-semibold">Item Name</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-t border-gray-200">
                    <td class="py-3 px-4">1</td>
                    <td class="py-3 px-4">Bed</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Moving Supplies Items -->
          <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 px-2">Moving Supplies Items</h2>
            <div class="bg-[#f0f7f7] rounded-lg overflow-x-auto">
              <table class="w-full min-w-[300px]">
                <thead>
                  <tr class="bg-[#f0f7f7]">
                    <th class="py-3 px-4 text-left w-24 font-semibold">Quantity</th>
                    <th class="py-3 px-4 text-left font-semibold">Item Name</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-t border-gray-200">
                    <td class="py-3 px-4">1</td>
                    <td class="py-3 px-4">Medium Boxes</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Comments -->
          <div>
            <h2 class="text-xl font-bold text-gray-800 mb-4 px-2">Comments</h2>
            <div class="border border-gray-200 rounded-lg p-4 min-h-[100px] text-gray-600 bg-gray-50">
              No Comments
            </div>
          </div>
        </div>
      `,
      'estimates': `
        <div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-md mb-20">
          <!-- Top Section: Customer and Relocation Details -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-8">
            <!-- Customer Details -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h2 class="text-xl font-bold text-gray-800 mb-4">Customer Details</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-gray-600 font-medium">Full Name:</div>
                <div>Jon Doe</div>
                <div class="text-gray-600 font-medium">Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-gray-600 font-medium">Mobile Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-gray-600 font-medium">Work Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-gray-600 font-medium">Email:</div>
                <div class="break-all">email@email.com</div>
              </div>
            </div>

            <!-- Relocation Details -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h2 class="text-xl font-bold text-gray-800 mb-4">Relocation Details</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-gray-600 font-medium">Estimated Created on:</div>
                <div>July 21th 2021 4:00 PM</div>
                <div class="text-gray-600 font-medium">Pickup Date:</div>
                <div>July 25th 2021</div>
                <div class="text-gray-600 font-medium">Approx Weight:</div>
                <div>128.15 CF ( 8000 lbs )</div>
                <div class="text-gray-600 font-medium">Rate:</div>
                <div>$70</div>
                <div class="text-gray-600 font-medium">Parking:</div>
                <div>-</div>
                <div class="text-gray-600 font-medium">Additional Stop:</div>
                <div>-</div>
                <div class="text-gray-600 font-medium">Men Needed:</div>
                <div>3</div>
                <div class="text-gray-600 font-medium">Hours Minimum:</div>
                <div>-</div>
              </div>
            </div>
          </div>

          <!-- Move From and Move To -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-8">
            <!-- Move From -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h2 class="text-xl font-bold text-gray-800 mb-4">Move From</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-gray-600 font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="text-gray-600 font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="text-gray-600 font-medium">City:</div>
                <div>New Jersey</div>
                <div class="text-gray-600 font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="text-gray-600 font-medium">State:</div>
                <div>Florida</div>
              </div>
            </div>

            <!-- Move To -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h2 class="text-xl font-bold text-gray-800 mb-4">Move To</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-gray-600 font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="text-gray-600 font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="text-gray-600 font-medium">City:</div>
                <div>New Jersey</div>
                <div class="text-gray-600 font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="text-gray-600 font-medium">State:</div>
                <div>Florida</div>
              </div>
            </div>
          </div>

          <!-- Billing Estimates -->
          <div class="mb-8">
            <div class="bg-blue-600 text-white py-2 px-4 rounded-t-lg">
              <h2 class="font-bold">Billing Estimates</h2>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full min-w-[600px]">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="py-3 px-4 text-left font-semibold text-gray-700 w-2/3">Description</th>
                    <th class="py-3 px-4 text-right font-semibold text-gray-700 w-1/3">Price</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="py-3 px-4">Initial Rate - $70/hr / 128.15 CF ( 8000 lbs ) / 3 Men @ 8hours</td>
                    <td class="py-3 px-4 text-right text-red-500">Labor Total</td>
                  </tr>
                  <tr>
                    <td class="py-3 px-4">Moving Supplies</td>
                    <td class="py-3 px-4 text-right text-red-500">Moving Supplies</td>
                  </tr>
                  <tr>
                    <td class="py-3 px-4">Additional Services</td>
                    <td class="py-3 px-4 text-right text-red-500">Additional Services</td>
                  </tr>
                  <tr>
                    <td class="py-3 px-4">Additional Charges</td>
                    <td class="py-3 px-4 text-right text-red-500">Additional Charges</td>
                  </tr>
                  <tr>
                    <td class="py-3 px-4">Truck Fee Charges</td>
                    <td class="py-3 px-4 text-right text-red-500">Truck Fee</td>
                  </tr>
                  <tr>
                    <td class="py-3 px-4">Transaction Fee</td>
                    <td class="py-3 px-4 text-right text-red-500">Transaction Fee</td>
                  </tr>
                  <tr>
                    <td class="py-3 px-4">Discount</td>
                    <td class="py-3 px-4 text-right text-red-500">Discount</td>
                  </tr>
                  <tr class="bg-gray-50">
                    <td class="py-3 px-4 font-semibold">Initial Deposit</td>
                    <td class="py-3 px-4 text-right font-semibold text-green-500">$625.00</td>
                  </tr>
                  <tr class="bg-gray-50">
                    <td class="py-3 px-4 font-bold">Total</td>
                    <td class="py-3 px-4 text-right font-bold text-green-600">$1,250.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Inventory and Supplies Section -->
          <div class="grid grid-cols-1 gap-6 mb-8">
            <!-- Inventory Items -->
            <div>
              <h2 class="text-xl font-bold text-gray-800 mb-4">Inventory Items</h2>
              <div class="overflow-x-auto">
                <table class="w-full min-w-[400px]">
                  <thead class="bg-[#f0f7f7]">
                    <tr>
                      <th class="py-2 px-4 text-left w-32">Quantity</th>
                      <th class="py-2 px-4 text-left">Item Name</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white">
                    <tr class="border-t border-gray-200">
                      <td class="py-2 px-4">1</td>
                      <td class="py-2 px-4">Bed</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Moving Supplies Items -->
            <div>
              <h2 class="text-xl font-bold text-gray-800 mb-4">Moving Supplies Items</h2>
              <div class="overflow-x-auto">
                <table class="w-full min-w-[400px]">
                  <thead class="bg-[#f0f7f7]">
                    <tr>
                      <th class="py-2 px-4 text-left w-32">Quantity</th>
                      <th class="py-2 px-4 text-left">Item Name</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white">
                    <tr class="border-t border-gray-200">
                      <td class="py-2 px-4">1</td>
                      <td class="py-2 px-4">Medium Boxes</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Understanding Your Estimate -->
          <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Understanding Your Estimate</h2>
            <div class="bg-white border rounded-lg p-6">
              <h3 class="font-bold mb-4">VALUATION/$ COVERAGE</h3>
              <p class="mb-4">The following is a description of the options for protecting your belongings during the moving process.</p>
              
              <div class="mb-4">
                <p class="font-semibold">Option 1: Released Value</p>
                <p class="mb-2">$0.60 per pound per item pick up to a maximum of $2,500.00 per truck provided. Provider has a maximum liability under State Law for loss or damage to your property that will be based at the time of the job. Any damages not documented while the movers are present will not be the responsibility of the mover or Service Provider.</p>
              </div>

              <div class="mb-4">
                <p class="font-semibold">Option 2: Full Replacement Value</p>
                <p class="mb-2">Additional Charges Apply for This Option</p>
                <ol class="list-decimal pl-5 mb-4">
                  <li>Repair the article to the extent necessary to restore it to the same condition as when it was received by Service Provider or pay the cost of such repairs.</li>
                  <li>Replace the article with an article of like kind and quality or pay you the cost of such a replacement.</li>
                </ol>
              </div>

              <p class="mb-6">Any and all claims must be submitted in writing within 15 days of completed move. See Terms & Condition Services for more information.</p>

              <div class="mb-6">
                <p class="font-semibold mb-2">Exclusions:</p>
                <ul class="list-disc pl-5 space-y-1">
                  <li>Items of extraordinary value not listed or claimed on the High Value Inventory Form</li>
                  <li>Lamps, lamp shades, artwork, pictures, mirrors, artificial plants and statues not packed by Service Provider</li>
                  <li>Any public or private road, in excess of 50ft to Service Provider</li>
                  <li>Items found in boxes not crated, packed or unpacked by Service Provider</li>
                  <li>Any items packed and/or unpacked by Service Provider where they (Service Provider) were not the sole transporter</li>
                  <li>Any item not put in appropriate boxes or crates, when Service Provider recommended (plasma televisions, grandfather clocks, etc.)</li>
                  <li>Mechanical condition of audio/visual or electronic equipment</li>
                  <li>Computers and battery operated items in transit or in storage</li>
                  <li>Missing hardware not disassembled by Service Provider</li>
                  <li>Gold leaf or plaster frames and chandeliers not crated by Service Provider</li>
                  <li>Pressboard or particle board furniture</li>
                </ul>
              </div>

              <!-- Signature Section -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 pt-8 border-t">
                <div class="text-center">
                  <div class="border-b border-gray-400 pb-2 mb-2">
                    <span class="text-gray-600"></span>
                  </div>
                  <span class="text-sm text-gray-600">Customer Name</span>
                </div>
                <div class="text-center">
                  <div class="border-b border-gray-400 pb-2 mb-2">
                    <span class="text-gray-600"></span>
                  </div>
                  <span class="text-sm text-gray-600">Signature</span>
                </div>
                <div class="text-center">
                  <div class="border-b border-gray-400 pb-2 mb-2">
                    <span class="text-gray-600"></span>
                  </div>
                  <span class="text-sm text-gray-600">Date</span>
                </div>
              </div>

              <!-- Confirm Button -->
              <div class="text-center mt-6">
                <button class="bg-blue-600 text-white py-2 px-8 rounded-lg hover:bg-blue-700 transition-colors">
                  Confirm
                </button>
              </div>
            </div>
          </div>
        </div>
      `,
      'services': `
        <div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-md mb-20">
          <!-- Header Section -->
          <div class="flex justify-between items-start mb-6">
            <div>
              <h2 class="text-xl font-bold mb-2">Services / Bill of Lading</h2>
              <div class="text-sm">
                <p>DATE: 02 / 24 / 2021</p>
                <p>TIME: 04:02 PM</p>
                <p>Reference No: #001 | Local - Residential</p>
              </div>
              <div class="text-sm mt-2">
                <p>USDOT #1877921</p>
                <p>MC #077599</p>
                <p>ADDRESS: 519 cascade ct Sewell NJ 08080</p>
                <p>PHONE: #877-385-2919</p>
              </div>
            </div>
            <div>
              <img src="assets/images/competitive.png" alt="CRServices Logo" class="w-32">
            </div>
          </div>

          <!-- Move Details Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Move From -->
            <div class="bg-gray-100 p-4 rounded-lg">
              <h3 class="font-bold mb-4 bg-gray-200 p-2">MOVE FROM</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="font-medium">Customer Name:</div>
                <div>Jon Doe</div>
                <div class="font-medium">Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="font-medium">Mobile Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="font-medium">Work Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="font-medium">Email:</div>
                <div class="break-all">email@email.com</div>
                <div class="font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="font-medium">City:</div>
                <div>New Jersey</div>
                <div class="font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="font-medium">State:</div>
                <div>Florida</div>
                <div class="font-medium">Parking:</div>
                <div>Yes</div>
                <div class="font-medium">Additional Stop:</div>
                <div>No</div>
              </div>
            </div>

            <!-- Move To -->
            <div class="bg-gray-100 p-4 rounded-lg">
              <h3 class="font-bold mb-4 bg-gray-200 p-2">MOVE TO</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="font-medium">Customer Name:</div>
                <div>Jon Doe</div>
                <div class="font-medium">Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="font-medium">Mobile Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="font-medium">Work Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="font-medium">Email:</div>
                <div class="break-all">email@email.com</div>
                <div class="font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="font-medium">City:</div>
                <div>New Jersey</div>
                <div class="font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="font-medium">State:</div>
                <div>Florida</div>
              </div>
            </div>
          </div>

          <!-- Rates Section -->
          <div class="mb-6">
            <h3 class="font-bold mb-4 bg-gray-200 p-2">RATES</h3>
            <div class="overflow-x-auto">
              <table class="w-full min-w-[600px]">
                <thead>
                  <tr class="bg-gray-50">
                    <th class="text-left p-2">Description</th>
                    <th class="text-right p-2">Price</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="p-2">Initial Rate - $70/hr / 128.15 CF ( 8000 lbs ) / 3 Men @ 8hours</td>
                    <td class="p-2 text-right text-red-500">Labor Total</td>
                  </tr>
                  <tr>
                    <td class="p-2">Moving Supplies</td>
                    <td class="p-2 text-right text-red-500">Moving Supplies + Packing Sub Total</td>
                  </tr>
                  <tr>
                    <td class="p-2">Additional Services</td>
                    <td class="p-2 text-right text-red-500">Additional Services</td>
                  </tr>
                  <tr>
                    <td class="p-2">Additional Charges</td>
                    <td class="p-2 text-right text-red-500">Additional Charges</td>
                  </tr>
                  <tr>
                    <td class="p-2">Truck Fee Charges</td>
                    <td class="p-2 text-right text-red-500">Truck Fee</td>
                  </tr>
                  <tr>
                    <td class="p-2">Transaction Fee</td>
                    <td class="p-2 text-right text-red-500">Transaction Fee</td>
                  </tr>
                  <tr>
                    <td class="p-2">Discount</td>
                    <td class="p-2 text-right text-red-500">Discount</td>
                  </tr>
                  <tr class="bg-gray-50">
                    <td class="p-2 font-bold">Initial Deposit</td>
                    <td class="p-2 text-right font-bold">$625.00</td>
                  </tr>
                  <tr class="bg-gray-50">
                    <td class="p-2 font-bold">Total</td>
                    <td class="p-2 text-right font-bold">$1,250.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p class="text-sm text-gray-600 mt-2">NOTES: ANY ADDITIONAL TIME NEEDED IN 1/2 HOUR INCREMENTS</p>
            <p class="text-sm text-red-500 mt-1">PAYMENT TERMS: INITIAL PAYMENT: $625.00</p>
            <p class="text-sm font-medium mt-1">REMAINING BALANCES IS DUE CASH OR ONLINE PAYMENT. PLEASE HAVE YOUR PAYMENT READY WHEN CREW DELIVERS.</p>
          </div>

          <!-- Trip Details Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-100 p-4 rounded-lg">
              <h3 class="font-bold mb-4">TRIP DETAILS</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="font-medium">Mileage Between:</div>
                <div>10</div>
                <div class="font-medium">Estimated Weight:</div>
                <div>8000 lbs</div>
              </div>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
              <h3 class="font-bold mb-4">TOTAL HOURS</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="font-medium">Start Time:</div>
                <div>01:04 PM 02 / 24 / 2021</div>
                <div class="font-medium">Finish Time:</div>
                <div>01:04 PM 02 / 24 / 2021</div>
                <div class="font-medium">Total Hours:</div>
                <div>48 Hours</div>
              </div>
            </div>
          </div>

          <!-- Valuation Coverage -->
          <div class="mb-6">
            <h3 class="font-bold mb-4">VALUATION COVERAGE:</h3>
            <div class="bg-gray-100 p-4 rounded-lg">
              <p class="text-sm mb-4">CUSTOMERS DECLARED VALUE AND LIMIT OF COMPANY'S LIABILITY</p>
              <p class="text-sm">The customer agrees on the declared value of the property, and the customer (shipper) is required to declare in writing the released value of the property. Unless the customer specifically stated to be not exceeding $0.60 cents per pound per article with a limit of $2,000.00 while being handled by the carrier.</p>
            </div>
          </div>

          <!-- Credit Card Authorization Notice -->
          <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <p class="font-bold">NOTE: INSERT CREDIT CARD AUTHORIZATION IN THIS PART</p>
          </div>

          <!-- High Value Inventory -->
          <div class="mb-6">
            <h3 class="font-bold mb-4">HIGH VALUE INVENTORY</h3>
            <div class="overflow-x-auto">
              <table class="w-full min-w-[600px]">
                <thead class="bg-gray-200">
                  <tr>
                    <th class="text-left p-2">ITEMS</th>
                    <th class="text-left p-2">QUANTITY</th>
                    <th class="text-left p-2">PRICE</th>
                    <th class="text-left p-2">COMMENTS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-t border-gray-200">
                    <td class="p-2">Gold Earings</td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Shipper Signature -->
          <div class="mb-6">
            <h3 class="font-bold mb-4">SHIPPER SIGNATURE ON PICK UP</h3>
            <p class="text-sm mb-4">This is to certify that the above named materials are properly classified, packaged, marked, and labeled, and are in proper condition for transportation according to the applicable regulations of the DOT. This contract is non negotiable and all monies are due COD are described in your contract.</p>
          </div>

          <!-- Additional Comments -->
          <div class="mb-6">
            <h3 class="font-bold mb-4">Additional Comments</h3>
            <textarea class="w-full p-2 border rounded-lg min-h-[100px]"></textarea>
          </div>

          <!-- Signature Section -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="text-center">
              <div class="border-b border-gray-400 pb-2 mb-2"></div>
              <span class="text-sm text-gray-600">Customer Name</span>
            </div>
            <div class="text-center">
              <div class="border-b border-gray-400 pb-2 mb-2"></div>
              <span class="text-sm text-gray-600">Signature</span>
            </div>
            <div class="text-center">
              <div class="border-b border-gray-400 pb-2 mb-2"></div>
              <span class="text-sm text-gray-600">Date</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-between items-center">
            <button class="bg-blue-600 text-white py-2 px-8 rounded-lg hover:bg-blue-700 transition-colors">
              Complete
            </button>
            <button class="bg-gray-100 text-gray-700 py-2 px-8 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
              </svg>
              Print
            </button>
          </div>
        </div>
      `,
      'payments': `
        <div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-md mb-20">
          <h2>PAYMENTS</h2>
          <p>Track payments and transactions here.</p>
        </div>
      `,
      'support': `
      <div class="border border-gray-300 p-6 rounded-lg shadow-md mb-20">
        <h2>Support</h2>
        <p>Need help? Contact support here.</p>
        <div class="mt-6">
          <button class="w-full mb-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Assigned Sales Rep.: Love </button>
          <button class="w-full mb-4 px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300">Assigned Driver: Lamar Reyes</button>
        </div>
      </div>
    `,
    };

    // Function to navigate between menu options
    function navigate(page) {
      const mainContent = document.getElementById("main-content");
      mainContent.innerHTML = pages[page] || "<p>Page not found.</p>";
    }
  </script>
</body>
</html>
