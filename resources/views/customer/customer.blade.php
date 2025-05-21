<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRService</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Material Design 3 Color System -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#1061B1', // Brand blue
              container: '#E3F2FD',
              on: '#FFFFFF',
              onContainer: '#001E3C'
            },
            secondary: {
              DEFAULT: '#FFA71E', // Brand yellow
              container: '#FFF3E0',
              on: '#FFFFFF',
              onContainer: '#3E2723'
            },
            tertiary: {
              DEFAULT: '#7D5260',
              container: '#FFD8E4',
              on: '#FFFFFF',
              onContainer: '#370B1E'
            },
            error: {
              DEFAULT: '#B3261E',
              container: '#F9DEDC',
              on: '#FFFFFF',
              onContainer: '#410E0B'
            },
            surface: {
              DEFAULT: '#FFFBFE',
              variant: '#E7E0EC',
              on: '#1C1B1F',
              onVariant: '#49454F'
            },
            outline: '#79747E',
            background: '#FFFBFE'
          }
        }
      }
    }
  </script>
  <style>
    /* Material Design 3 Typography */
    body {
      font-family: 'Roboto', sans-serif;
      letter-spacing: 0.15px;
    }

    /* Material Design 3 Elevation */
    .elevation-1 {
      box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }
    .elevation-2 {
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    }
    .elevation-3 {
      box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }

    /* Material Design 3 State Layers */
    .state-layer {
      position: relative;
    }
    .state-layer::after {
      content: '';
      position: absolute;
      inset: 0;
      background: currentColor;
      opacity: 0;
      transition: opacity 0.2s;
    }
    .state-layer:hover::after {
      opacity: 0.08;
    }
    .state-layer:active::after {
      opacity: 0.12;
    }

    /* Material Design 3 Shape */
    .shape-medium {
      border-radius: 16px;
    }
    .shape-small {
      border-radius: 8px;
    }

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
      border: 4px solid rgba(16, 97, 177, 0.3);
      border-top: 4px solid #1061B1;
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

<body class="bg-background text-surface-on min-h-screen">

  <!-- Preloader -->
  <div id="preloader" class="fixed top-0 left-0 w-full h-full bg-background bg-opacity-90 flex justify-center items-center z-50">
    <span class="text-xl font-medium text-primary animate-pulse">Loading...</span>
  </div>

  <!-- Header -->
  <header class="bg-primary text-primary-on p-4 elevation-2">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
    <div class="flex flex-col items-start">
        <img src="assets/images/competitive.png" alt="" class="h-12">
    </div>
      <div id="moving-top" class="text-sm text-right">
        <p>MOVING: <span class="font-medium">#001 | Local - Residential</span></p>
        <p>MOVING LOAD: <span class="font-medium">#001</span></p>
      </div>
    </div>
  </header>
  
  <!-- Main Content -->
  <main class="max-w-7xl mx-auto p-4">
    <div id="main-content" class="bg-surface rounded-lg elevation-1 p-4 mb-20">
    <!-- Placeholder for the selected menu page -->
    </div>
  </main>

  <!-- Footer Navigation -->
  <footer class="fixed bottom-0 left-0 right-0 bg-primary elevation-3">
    <div class="max-w-7xl mx-auto">
      <div class="flex justify-around items-center py-2">
        <a href="#customer-info" class="flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" onclick="navigate('customer-info')">
          <span class="material-icons text-2xl">person</span>
          <span class="text-xs mt-1 hidden sm:block">CUSTOMER INFO</span>
        </a>
        <a href="#estimates" class="flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" onclick="navigate('estimates')">
          <span class="material-icons text-2xl">assessment</span>
          <span class="text-xs mt-1 hidden sm:block">ESTIMATES</span>
        </a>
        <a href="#services" class="flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" onclick="navigate('services')">
          <span class="material-icons text-2xl">build</span>
          <span class="text-xs mt-1 hidden sm:block">SERVICES</span>
        </a>
        <a href="#payments" class="flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" onclick="navigate('payments')">
          <span class="material-icons text-2xl">payments</span>
          <span class="text-xs mt-1 hidden sm:block">PAYMENTS</span>
        </a>
        <a href="#support" class="flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" onclick="navigate('support')">
          <span class="material-icons text-2xl">support</span>
          <span class="text-xs mt-1 hidden sm:block">CONTACT</span>
        </a>
      </div>
    </div>
  </footer>

  <script>
    // Preloader functionality
    document.addEventListener("DOMContentLoaded", () => {
      const preloader = document.getElementById("preloader");
      const footer = document.querySelector("footer");    
      const movingtop = document.getElementById("moving-top");
      const main = document.querySelector("main");
      
      setTimeout(() => {
        preloader.classList.add("hide"); // Hide preloader after 2 seconds (or when content is ready)
        // Show Customer Details by default
        navigate('customer-info');
      }, 2000); // Adjust time as needed for your content
    });

    const pages = {
      'customer-info': `
        <div class="max-w-4xl mx-auto">
          <!-- Customer Details -->
          <div class="mb-8">
            <h2 class="text-2xl font-medium mb-4">Customer Details</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-x-8">
              <div class="bg-surface-variant rounded-lg p-4 elevation-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div class="text-surface-onVariant font-medium">Full Name:</div>
                  <div>Jon Doe</div>
                  <div class="text-surface-onVariant font-medium">Email:</div>
                  <div class="break-all">email@email.com</div>
                  <div class="text-surface-onVariant font-medium">Move Date:</div>
                  <div>July 21, 2021</div>
                </div>
              </div>
              <div class="bg-surface-variant rounded-lg p-4 elevation-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div class="text-surface-onVariant font-medium">Phone:</div>
                  <div>+1 236 254 4568</div>
                  <div class="text-surface-onVariant font-medium">Mobile Phone:</div>
                  <div>+1 236 254 4568</div>
                  <div class="text-surface-onVariant font-medium">Work Phone:</div>
                  <div>+1 236 254 4568</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Move From and Move To -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-8">
            <!-- Move From -->
            <div class="bg-surface-variant rounded-lg p-4 elevation-1">
              <h2 class="text-xl font-medium mb-4">Move From</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-surface-onVariant font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="text-surface-onVariant font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="text-surface-onVariant font-medium">City:</div>
                <div>New Jersey</div>
                <div class="text-surface-onVariant font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="text-surface-onVariant font-medium">State:</div>
                <div>Florida</div>
              </div>
            </div>

            <!-- Move To -->
            <div class="bg-surface-variant rounded-lg p-4 elevation-1">
              <h2 class="text-xl font-medium mb-4">Move To</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-surface-onVariant font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="text-surface-onVariant font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="text-surface-onVariant font-medium">City:</div>
                <div>New Jersey</div>
                <div class="text-surface-onVariant font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="text-surface-onVariant font-medium">State:</div>
                <div>Florida</div>
              </div>
            </div>
          </div>

          <!-- Inventory Items -->
          <div class="mb-8">
            <h2 class="text-2xl font-medium mb-4">Inventory Items</h2>
            <div class="bg-surface-variant rounded-lg overflow-hidden elevation-1">
              <table class="w-full min-w-[300px]">
                <thead>
                  <tr class="bg-surface-variant">
                    <th class="py-3 px-4 text-left w-24 font-medium text-surface-onVariant">Quantity</th>
                    <th class="py-3 px-4 text-left font-medium text-surface-onVariant">Item Name</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant">
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="py-3 px-4">1</td>
                    <td class="py-3 px-4">Bed</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Moving Supplies Items -->
          <div class="mb-8">
            <h2 class="text-2xl font-medium mb-4">Moving Supplies Items</h2>
            <div class="bg-surface-variant rounded-lg overflow-hidden elevation-1">
              <table class="w-full min-w-[300px]">
                <thead>
                  <tr class="bg-surface-variant">
                    <th class="py-3 px-4 text-left w-24 font-medium text-surface-onVariant">Quantity</th>
                    <th class="py-3 px-4 text-left font-medium text-surface-onVariant">Item Name</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant">
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="py-3 px-4">1</td>
                    <td class="py-3 px-4">Medium Boxes</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Comments -->
          <div>
            <h2 class="text-2xl font-medium mb-4">Comments</h2>
            <div class="bg-surface-variant rounded-lg p-4 min-h-[100px] text-surface-onVariant elevation-1">
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
        <div class="max-w-4xl mx-auto">
          <!-- Header Section -->
          <div class="flex justify-between items-start mb-6">
            <div>
              <h2 class="text-2xl font-medium mb-2">Services / Bill of Lading</h2>
              <div class="text-sm text-surface-onVariant">
                <p>DATE: 02 / 24 / 2021</p>
                <p>TIME: 04:02 PM</p>
                <p>Reference No: #001 | Local - Residential</p>
              </div>
              <div class="text-sm text-surface-onVariant mt-2">
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
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4 bg-surface-variant/50 p-2 rounded-lg">MOVE FROM</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Customer Name:</div>
                <div>Jon Doe</div>
                <div class="text-surface-onVariant font-medium">Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-surface-onVariant font-medium">Mobile Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-surface-onVariant font-medium">Work Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-surface-onVariant font-medium">Email:</div>
                <div class="break-all">email@email.com</div>
                <div class="text-surface-onVariant font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="text-surface-onVariant font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="text-surface-onVariant font-medium">City:</div>
                <div>New Jersey</div>
                <div class="text-surface-onVariant font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="text-surface-onVariant font-medium">State:</div>
                <div>Florida</div>
                <div class="text-surface-onVariant font-medium">Parking:</div>
                <div>Yes</div>
                <div class="text-surface-onVariant font-medium">Additional Stop:</div>
                <div>No</div>
              </div>
            </div>

            <!-- Move To -->
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4 bg-surface-variant/50 p-2 rounded-lg">MOVE TO</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Customer Name:</div>
                <div>Jon Doe</div>
                <div class="text-surface-onVariant font-medium">Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-surface-onVariant font-medium">Mobile Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-surface-onVariant font-medium">Work Phone:</div>
                <div>+1 236 254 4568</div>
                <div class="text-surface-onVariant font-medium">Email:</div>
                <div class="break-all">email@email.com</div>
                <div class="text-surface-onVariant font-medium">Pick-up Address:</div>
                <div>123 Street</div>
                <div class="text-surface-onVariant font-medium">Floor:</div>
                <div>Ground Floor</div>
                <div class="text-surface-onVariant font-medium">City:</div>
                <div>New Jersey</div>
                <div class="text-surface-onVariant font-medium">Zipcode:</div>
                <div>90211</div>
                <div class="text-surface-onVariant font-medium">State:</div>
                <div>Florida</div>
              </div>
            </div>
          </div>

          <!-- Rates Section -->
          <div class="mb-6">
            <h3 class="font-medium mb-4 bg-surface-variant/50 p-2 rounded-lg">RATES</h3>
            <div class="bg-surface-variant rounded-lg overflow-hidden elevation-1">
              <table class="w-full min-w-[600px]">
                <thead>
                  <tr class="bg-surface-variant">
                    <th class="text-left p-2 font-medium text-surface-onVariant">Description</th>
                    <th class="text-right p-2 font-medium text-surface-onVariant">Price</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant">
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2">Initial Rate - $70/hr / 128.15 CF ( 8000 lbs ) / 3 Men @ 8hours</td>
                    <td class="p-2 text-right text-error">Labor Total</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2">Moving Supplies</td>
                    <td class="p-2 text-right text-error">Moving Supplies + Packing Sub Total</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2">Additional Services</td>
                    <td class="p-2 text-right text-error">Additional Services</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2">Additional Charges</td>
                    <td class="p-2 text-right text-error">Additional Charges</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2">Truck Fee Charges</td>
                    <td class="p-2 text-right text-error">Truck Fee</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2">Transaction Fee</td>
                    <td class="p-2 text-right text-error">Transaction Fee</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2">Discount</td>
                    <td class="p-2 text-right text-error">Discount</td>
                  </tr>
                  <tr class="bg-surface-variant/50">
                    <td class="p-2 font-medium">Initial Deposit</td>
                    <td class="p-2 text-right font-medium">$625.00</td>
                  </tr>
                  <tr class="bg-surface-variant/50">
                    <td class="p-2 font-medium">Total</td>
                    <td class="p-2 text-right font-medium">$1,250.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p class="text-sm text-surface-onVariant mt-2">NOTES: ANY ADDITIONAL TIME NEEDED IN 1/2 HOUR INCREMENTS</p>
            <p class="text-sm text-error mt-1">PAYMENT TERMS: INITIAL PAYMENT: $625.00</p>
            <p class="text-sm font-medium mt-1">REMAINING BALANCES IS DUE CASH OR ONLINE PAYMENT. PLEASE HAVE YOUR PAYMENT READY WHEN CREW DELIVERS.</p>
          </div>

          <!-- Trip Details Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4">TRIP DETAILS</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Mileage Between:</div>
                <div>10</div>
                <div class="text-surface-onVariant font-medium">Estimated Weight:</div>
                <div>8000 lbs</div>
              </div>
            </div>
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4">TOTAL HOURS</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Start Time:</div>
                <div>01:04 PM 02 / 24 / 2021</div>
                <div class="text-surface-onVariant font-medium">Finish Time:</div>
                <div>01:04 PM 02 / 24 / 2021</div>
                <div class="text-surface-onVariant font-medium">Total Hours:</div>
                <div>48 Hours</div>
              </div>
            </div>
          </div>

          <!-- Valuation Coverage -->
          <div class="mb-6">
            <h3 class="font-medium mb-4">VALUATION COVERAGE:</h3>
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <p class="text-sm mb-4">CUSTOMERS DECLARED VALUE AND LIMIT OF COMPANY'S LIABILITY</p>
              <p class="text-sm text-surface-onVariant">The customer agrees on the declared value of the property, and the customer (shipper) is required to declare in writing the released value of the property. Unless the customer specifically stated to be not exceeding $0.60 cents per pound per article with a limit of $2,000.00 while being handled by the carrier.</p>
            </div>
          </div>

          <!-- Credit Card Authorization Form -->
          <div class="mb-6">
            <h3 class="font-medium mb-4">CREDIT CARD AUTHORIZATION</h3>
            <div class="bg-surface-variant p-6 rounded-lg elevation-1">
              <form class="space-y-6">
                <!-- Customer Information -->
                <div>
                  <label class="block text-sm font-medium text-surface-onVariant mb-2">Customer Information</label>
                  <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="Full Name">
                </div>

                <!-- Authorization Statement -->
                <div class="text-sm text-surface-onVariant space-y-4">
                  <p class="flex items-start">
                    <span class="mr-2">I,</span>
                    <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center mb-2">
                      <input type="text" class="flex-1 p-2 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-sm" placeholder="(Name)">
                      <input type="text" class="flex-1 p-2 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-sm" placeholder="(Title)">
                    </div>
                  </p>
                  <p>am the authorized cardholder for the below listed credit card and hereby authorizes Competitive Relocation Services to charge the card, the cost for services purchased from Competitive Relocation Services when I am not present in person or cannot make an impression of the card because the numbers are not raised.</p>
                  <p>By signing this form, I agree not to initiate a chargeback proceeding with my credit card company for charges by Competitive Relocation Services, on the credit card below, and understand than any chargeback will constitute a breach of contract. I agree to waive any chargeback right. I may have and will contact Competitive Relocation Services to resolve any dispute regarding charges by Competitive Relocation Services on the card.</p>
                </div>

                <!-- Credit Card Information -->
                <div>
                  <label class="block text-sm font-medium text-surface-onVariant mb-4">Credit Card Information</label>
                  <div class="space-y-6">
                    <div>
                      <label class="block text-sm font-medium text-surface-onVariant mb-2">Credit Card Type</label>
                      <div class="flex flex-wrap gap-x-4 gap-y-2">
                        <label class="inline-flex items-center">
                          <input type="radio" name="card-type" class="form-radio h-4 w-4 text-primary">
                          <span class="ml-2">Visa</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" name="card-type" class="form-radio h-4 w-4 text-primary">
                          <span class="ml-2">Mastercard</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" name="card-type" class="form-radio h-4 w-4 text-primary">
                          <span class="ml-2">Discover</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" name="card-type" class="form-radio h-4 w-4 text-primary">
                          <span class="ml-2">Amex</span>
                        </label>
                      </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-6">
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">Last 8 Digits of Card Number</label>
                        <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="12345678" maxlength="8">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">CVC Code</label>
                        <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="123" maxlength="4">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">Expiration Date</label>
                        <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="MM/YY" maxlength="5">
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-surface-onVariant mb-2">Cardholder Name (as it appears on the card)</label>
                      <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="John Doe">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">Phone Number</label>
                        <input type="tel" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="(123) 456-7890">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">Email</label>
                        <input type="email" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="john@example.com">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Billing Address -->
                <div>
                  <label class="block text-sm font-medium text-surface-onVariant mb-4">Billing Address</label>
                  <p class="text-sm text-surface-onVariant mb-4">As it appears on the credit card statement (where the statements are mailed to)</p>
                  
                  <div class="space-y-6">
                    <div>
                      <label class="block text-sm font-medium text-surface-onVariant mb-2">Street Address</label>
                      <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="123 Main Street">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-6">
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">City</label>
                        <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="New York">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">State</label>
                        <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="NY">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">ZIP Code</label>
                        <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="10001">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Signature and Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-surface-onVariant mb-2">Cardholder Signature</label>
                    <div class="relative w-full">
                      <input type="text" id="signature-input" class="w-full p-2 sm:p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-xs sm:text-sm" placeholder="Click to sign" readonly>
                      <input type="hidden" id="signature-data" name="signature">
                      <button type="button" onclick="openSignaturePad()" class="absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2 text-primary hover:text-primary/80 font-medium text-xs sm:text-sm">
                        Sign
                      </button>
                    </div>
                    <div id="signature-preview" class="mt-2 sm:mt-3 hidden flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3">
                      <div class="border border-surface-variant rounded-lg bg-surface p-1 sm:p-2 flex items-center justify-center" style="min-width:90px; min-height:32px; max-width:160px; max-height:60px;">
                        <img id="signature-img" src="" alt="Signature" class="max-h-10 sm:max-h-16 max-w-full object-contain" />
                      </div>
                      <span class="inline-flex items-center px-1.5 py-0.5 sm:px-2 sm:py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Signed</span>
                      <button type="button" id="clear-signature" class="text-xs text-primary hover:underline ml-1 sm:ml-2">Clear</button>
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-surface-onVariant mb-2">Date</label>
                    <input type="text" class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="MM/DD/YYYY">
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                  <button type="submit" class="w-full md:w-auto px-8 py-3 bg-primary text-primary-on rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors mt-4 md:mt-0">
                    Submit Authorization
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- High Value Inventory -->
          <div class="mb-6">
            <h3 class="font-medium mb-4">HIGH VALUE INVENTORY</h3>
            <div class="bg-surface-variant rounded-lg overflow-hidden elevation-1">
              <table class="w-full min-w-[600px]">
                <thead>
                  <tr class="bg-surface-variant">
                    <th class="text-left p-2 font-medium text-surface-onVariant">ITEMS</th>
                    <th class="text-left p-2 font-medium text-surface-onVariant">QUANTITY</th>
                    <th class="text-left p-2 font-medium text-surface-onVariant">PRICE</th>
                    <th class="text-left p-2 font-medium text-surface-onVariant">COMMENTS</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant">
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2">Gold Earings</td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                    <td class="p-2"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- SHIPPER SIGNATURE ON PICK UP -->
          <div class="mb-6">
            <h3 class="font-bold mb-2">SHIPPER SIGNATURE ON PICK UP</h3>
            <p class="text-sm text-surface-onVariant mb-4">This is to certify that the above named materials are properly classified, packaged, marked, and labeled, and are in proper condition for transportation according to the applicable regulations of the DOT. This contract is non negotiable and all monies are due COD are described in your contract.</p>
            <label class="block text-sm font-medium text-surface-onVariant mb-2">Additional Comments</label>
            <textarea class="w-full p-2 border border-surface-variant rounded-lg min-h-[100px] mb-6"></textarea>
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
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
              <button class="bg-primary text-primary-on py-2 px-8 rounded-lg hover:bg-primary/90 transition-colors mb-2 md:mb-0">Complete</button>
              <button class="flex items-center gap-2 bg-surface-variant text-surface-onVariant py-2 px-6 rounded-lg hover:bg-surface-variant/80 transition-colors">
                <span class="material-icons">print</span> Print
            </button>
            </div>
          </div>
        </div>
      `,
      'payments': `
        <div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-md mb-20">
          <h2 class="text-xl font-bold text-gray-800 mb-4">Payment Information</h2>
          
          <!-- Payment Summary -->
          <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-semibold mb-3">Payment Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Initial Deposit</p>
                <p class="text-lg font-bold">$625.00</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Remaining Balance</p>
                <p class="text-lg font-bold">$625.00</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Total Amount</p>
                <p class="text-lg font-bold">$1,250.00</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Payment Status</p>
                <p class="text-lg font-bold text-green-600">Partially Paid</p>
              </div>
            </div>
          </div>
          
          <!-- Payment History -->
          <div class="mb-6">
            <h3 class="font-semibold mb-3">Payment History</h3>
            <div class="overflow-x-auto">
              <table class="w-full min-w-[600px]">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="text-left p-2">Date</th>
                    <th class="text-left p-2">Description</th>
                    <th class="text-left p-2">Amount</th>
                    <th class="text-left p-2">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="p-2">July 21, 2021</td>
                    <td class="p-2">Initial Deposit</td>
                    <td class="p-2">$625.00</td>
                    <td class="p-2 text-green-600">Paid</td>
                  </tr>
                  <tr>
                    <td class="p-2">-</td>
                    <td class="p-2">Remaining Balance</td>
                    <td class="p-2">$625.00</td>
                    <td class="p-2 text-yellow-600">Pending</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Payment Instructions -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold mb-3">Payment Instructions</h3>
            <p class="text-sm mb-2">The remaining balance of $625.00 is due upon delivery.</p>
            <p class="text-sm mb-2">Payment methods accepted:</p>
            <ul class="list-disc pl-5 text-sm mb-4">
              <li>Cash</li>
              <li>Credit Card</li>
              <li>Debit Card</li>
            </ul>
            <p class="text-sm font-medium">Please have your payment ready when the crew delivers your items.</p>
          </div>
        </div>
      `,
      'support': `
        <div class="max-w-4xl mx-auto">
          <h2 class="text-2xl font-medium mb-4">Support</h2>
          <p class="text-surface-onVariant mb-6">Need help? Contact support here.</p>
          <div class="space-y-4">
            <button class="w-full p-4 bg-primary text-primary-on rounded-lg hover:bg-primary/90 state-layer transition-colors">
              <div class="flex items-center justify-between">
                <span class="font-medium">Assigned Sales Rep.: Love</span>
                <span class="material-icons">chevron_right</span>
            </div>
                      </button>
            <button class="w-full p-4 bg-primary text-primary-on rounded-lg hover:bg-primary/90 state-layer transition-colors">
              <div class="flex items-center justify-between">
                <span class="font-medium">Assigned Driver: Lamar Reyes</span>
                <span class="material-icons">chevron_right</span>
                    </div>
                </button>
        </div>
      </div>
    `,
    };

    // Function to navigate between menu options
    function navigate(page) {
      const mainContent = document.getElementById("main-content");
      mainContent.innerHTML = pages[page] || "<p>Page not found.</p>";
    }

    // Signature Pad functionality
    let signaturePad;
    let signatureModal;

    function openSignaturePad() {
      Swal.fire({
        title: 'Please Sign Here',
        html: '<div class="border border-gray-300 rounded-lg"><canvas id="signature-pad" width="500" height="200" style="touch-action: none;"></canvas></div>',
        showCancelButton: true,
        confirmButtonText: 'Save Signature',
        cancelButtonText: 'Clear',
        showDenyButton: true,
        denyButtonText: 'Cancel',
        didOpen: () => {
          const canvas = document.getElementById('signature-pad');
          signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
          });
        },
        preConfirm: () => {
          if (signaturePad.isEmpty()) {
            Swal.showValidationMessage('Please provide a signature');
            return false;
          }
          return signaturePad.toDataURL();
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Save signature as image
          const signatureData = result.value;
          document.getElementById('signature-data').value = signatureData;
          // Show signature preview
          const preview = document.getElementById('signature-preview');
          const img = document.getElementById('signature-img');
          img.src = signatureData;
          preview.classList.remove('hidden');
          // Hide input value
          document.getElementById('signature-input').value = '';
        } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
          // Clear signature
          signaturePad.clear();
        }
      });
    }

    // Clear signature functionality
    if (document.getElementById('clear-signature')) {
      document.getElementById('clear-signature').onclick = function() {
        document.getElementById('signature-data').value = '';
        document.getElementById('signature-img').src = '';
        document.getElementById('signature-preview').classList.add('hidden');
        document.getElementById('signature-input').value = '';
      };
    }
  </script>
</body>
</html>
