<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>CRService</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
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

    @media print {
      body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      header, footer {
        display: none !important;
      }
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
        <img src="../assets/images/competitive.png" alt="" class="h-12">
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
        <!-- <a href="#services" class="flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" onclick="navigate('services')">
          <span class="material-icons text-2xl">build</span>
          <span class="text-xs mt-1 hidden sm:block">SERVICES</span>
        </a> -->
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

  <link href="https://unpkg.com/lightbox2@2.11.3/dist/css/lightbox.min.css" rel="stylesheet">
  <script src="https://unpkg.com/lightbox2@2.11.3/dist/js/lightbox-plus-jquery.min.js"></script>
  <script>
    // Preloader functionality
    document.addEventListener("DOMContentLoaded", () => {
      const preloader = document.getElementById("preloader");
      const footer = document.querySelector("footer");    
      const movingtop = document.getElementById("moving-top");
      const main = document.querySelector("main");
      
      // Get transaction data from PHP
      const transaction = @json($transaction);
      const inventory = @json($inventory);
      
      // Store transaction data globally
      window.transaction = transaction;
      window.inventory = inventory;
      window.salesReps = @json($salesReps);
      
      // Update header information
      document.querySelector('#moving-top p:first-child span').textContent = `#${transaction.transaction_id} | ${transaction.lead_type === 'local' ? 'Local' : 'Long Distance'} - ${transaction.service || 'Residential'}`;
      document.querySelector('#moving-top p:last-child span').textContent = `#${transaction.transaction_id}`;
      
      setTimeout(() => {
        preloader.classList.add("hide");
        // Show Customer Details by default
        navigate('customer-info');
        
        // Populate transaction details
        populateTransactionDetails(transaction);
        populateTripDetails(transaction, inventory);
      }, 2000);
    });

    // Signature Pad functionality
    let signaturePad;
    let signatureModal;

    function openSignaturePad() {
      // Create modal if it doesn't exist
      if (!signatureModal) {
        signatureModal = document.createElement('div');
        signatureModal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
        signatureModal.innerHTML = `
          <div class="bg-surface rounded-lg p-6 max-w-lg w-full mx-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-medium">Sign Here</h3>
              <button type="button" onclick="closeSignaturePad()" class="text-surface-onVariant hover:text-surface-on">
                <span class="material-icons">close</span>
              </button>
            </div>
            <div class="border border-surface-variant rounded-lg mb-4">
              <canvas id="signature-pad" width="400" height="200" class="w-full"></canvas>
            </div>
            <div class="flex justify-end gap-3">
              <button type="button" onclick="clearSignature()" class="px-4 py-2 text-surface-onVariant hover:text-surface-on">
                Clear
              </button>
              <button type="button" onclick="saveSignature()" class="px-4 py-2 bg-primary text-primary-on rounded-lg hover:bg-primary/90">
                Save
              </button>
            </div>
          </div>
        `;
        document.body.appendChild(signatureModal);
      }

      // Initialize signature pad
      const canvas = document.getElementById('signature-pad');
      signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)'
      });

      // Show modal
      signatureModal.style.display = 'flex';
    }

    function closeSignaturePad() {
      if (signatureModal) {
        signatureModal.style.display = 'none';
      }
    }

    function clearSignature() {
      if (signaturePad) {
        signaturePad.clear();
      }
    }

    function saveSignature() {
      if (signaturePad && !signaturePad.isEmpty()) {
        const signatureData = signaturePad.toDataURL();
        document.getElementById('signature-data').value = signatureData;
        document.getElementById('signature-img').src = signatureData;
        document.getElementById('signature-preview').classList.remove('hidden');
        document.getElementById('signature-input').value = 'Signed';
        closeSignaturePad();
      } else {
        alert('Please provide a signature');
      }
    }

    // Add clear signature button functionality
    if (document.getElementById('clear-signature')) {
      document.getElementById('clear-signature').onclick = function() {
        document.getElementById('signature-data').value = '';
        document.getElementById('signature-img').src = '';
        document.getElementById('signature-preview').classList.add('hidden');
        document.getElementById('signature-input').value = '';
      };
    }

    // Define pages as an object of strings or functions
    const pages = {
      'customer-info': `
        <div class="max-w-7xl mx-auto">
          <!-- Customer Details -->
          <div class="mb-8">
            <h2 class="text-2xl font-medium mb-4">Customer Details</h2>
            <div class="grid grid-cols-1 gap-4">
              <div class="bg-surface-variant rounded-lg p-4 elevation-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div class="text-surface-onVariant font-medium">Full Name:</div>
                  <div id="customer-fullname" class="font-medium">${window.transaction?.firstname || ''} ${window.transaction?.lastname || ''}</div>
                  <div class="text-surface-onVariant font-medium">Email:</div>
                  <div id="customer-email" class="break-all font-medium">${window.transaction?.email || 'N/A'}</div>
                  <div class="text-surface-onVariant font-medium">Move Date:</div>
                  <div id="customer-movedate" class="font-medium">${window.transaction?.date ? new Date(window.transaction.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</div>
                  <div class="text-surface-onVariant font-medium">Phone:</div>
                  <div id="customer-phone" class="font-medium">${window.transaction?.phone || 'N/A'}</div>
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
                <div id="pickup-address" class="font-medium">${window.transaction?.pickup_location || 'N/A'}</div>
              </div>
            </div>

            <!-- Move To -->
            <div class="bg-surface-variant rounded-lg p-4 elevation-1">
              <h2 class="text-xl font-medium mb-4">Move To</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-surface-onVariant font-medium">Pick-up Address:</div>
                <div id="delivery-address" class="font-medium">${window.transaction?.delivery_location || 'N/A'}</div>
              </div>
            </div>
          </div>

          <!-- Inventory Items -->
          <div class="mb-8 mt-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
              <h2 class="text-2xl font-medium">Inventory Items</h2>
              <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                <div class="bg-surface-variant rounded-lg px-4 py-2 flex-1 sm:flex-none min-w-[140px] flex flex-col items-start">
                  <span class="text-sm text-surface-onVariant">Total Items:</span>
                  <span class="ml-0 mt-1 font-medium text-lg" id="total-items-count">0</span>
                </div>
                <div class="bg-surface-variant rounded-lg px-4 py-2 flex-1 sm:flex-none min-w-[140px] flex flex-col items-start">
                  <span class="text-sm text-surface-onVariant">Total Cubic Ft:</span>
                  <span class="ml-0 mt-1 font-medium text-lg" id="total-cubic-ft">0.00</span>
                </div>
              </div>
            </div>
            <div id="inventory-items-container">
              <!-- Inventory items will be loaded here -->
              <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
                <p class="mt-4 text-surface-onVariant">Loading inventory items...</p>
              </div>
            </div>
            <!-- Uploaded Images Gallery -->
            <div id="uploaded-images-gallery" class="flex flex-wrap gap-3 mt-6"></div>
          </div>
        </div>
      `,
      'estimates': `
        <div class="max-w-4xl mx-auto">
          <!-- Header Section -->
          <div class="flex justify-between items-start mb-6">
            <div>
              <h2 class="text-2xl font-medium mb-2">Services / Bill of Lading</h2>
              <div class="text-sm text-surface-onVariant">
                <p>DATE: <span id="bol-date">02 / 24 / 2021</span></p>
                <p>TIME: <span id="bol-time">04:02 PM</span></p>
                <p>Reference No: <span id="bol-reference">#001 | Local - Residential</span></p>
              </div>
              <div class="text-sm text-surface-onVariant mt-2">
                <p>USDOT #<span id="bol-usdot">1877921</span></p>
                <p>MC #<span id="bol-mc">077599</span></p>
                <p>ADDRESS: <span id="bol-address">519 cascade ct Sewell NJ 08080</span></p>
                <p>PHONE: <span id="bol-phone">#877-385-2919</span></p>
              </div>
            </div>
            <div>
              <img src="../assets/images/competitive.png" alt="CRServices Logo" class="w-32">
            </div>
          </div>

          <!-- Move Details Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Move From -->
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4 bg-surface-variant/50 p-2 rounded-lg">MOVE FROM</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Customer Name:</div>
                <div id="bol-from-name">Jon Doe</div>
                <div class="text-surface-onVariant font-medium">Phone:</div>
                <div id="bol-from-phone">+1 236 254 4568</div>
                <div class="text-surface-onVariant font-medium">Email:</div>
                <div id="bol-from-email" class="break-all">email@email.com</div>
                <div class="text-surface-onVariant font-medium">Pick-up Address:</div>
                <div id="bol-from-address">123 Street</div>
              </div>
            </div>

            <!-- Move To -->
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4 bg-surface-variant/50 p-2 rounded-lg">MOVE TO</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Customer Name:</div>
                <div id="bol-to-name">Jon Doe</div>
                <div class="text-surface-onVariant font-medium">Phone:</div>
                <div id="bol-to-phone">+1 236 254 4568</div>
                <div class="text-surface-onVariant font-medium">Email:</div>
                <div id="bol-to-email" class="break-all">email@email.com</div>
                <div class="text-surface-onVariant font-medium">Pick-up Address:</div>
                <div id="bol-to-address">123 Street</div>
              </div>
            </div>
          </div>

          <!-- Rates Section -->
          <div class="mb-6">
            <h3 class="font-medium mb-4 bg-surface-variant/50 p-2 rounded-lg">RATES</h3>
            <div class="bg-surface-variant rounded-lg overflow-hidden elevation-1 rates-table-wrapper">
              <table class="w-full min-w-[600px] rates-table">
                <thead>
                  <tr class="bg-surface-variant">
                    <th class="text-left p-2 font-medium text-surface-onVariant">Description</th>
                    <th class="text-right p-2 font-medium text-surface-onVariant">Price</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant">
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2" id="rate-initial-desc">Initial Rate - $70/hr / 128.15 CF ( 8000 lbs ) / 3 Men @ 8hours</td>
                    <td class="p-2 text-right text-error" id="rate-initial-price">Labor Total</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2" id="rate-supplies-desc">Moving Supplies</td>
                    <td class="p-2 text-right text-error" id="rate-supplies-price">Moving Supplies + Packing Sub Total</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2" id="rate-additional-desc">Additional Services</td>
                    <td class="p-2 text-right text-error" id="rate-additional-price">Additional Services</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2" id="rate-charges-desc">Additional Charges</td>
                    <td class="p-2 text-right text-error" id="rate-charges-price">Additional Charges</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2" id="rate-truck-desc">Truck Fee Charges</td>
                    <td class="p-2 text-right text-error" id="rate-truck-price">Truck Fee</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2" id="rate-transaction-desc">Transaction Fee</td>
                    <td class="p-2 text-right text-error" id="rate-transaction-price">Transaction Fee</td>
                  </tr>
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="p-2" id="rate-discount-desc">Discount</td>
                    <td class="p-2 text-right text-error" id="rate-discount-price">Discount</td>
                  </tr>
                  <tr class="bg-surface-variant/50">
                    <td class="p-2 font-medium" id="rate-deposit-desc">Initial Deposit</td>
                    <td class="p-2 text-right font-medium" id="rate-deposit-price">$625.00</td>
                  </tr>
                  <tr class="bg-surface-variant/50">
                    <td class="p-2 font-medium" id="rate-total-desc">Total</td>
                    <td class="p-2 text-right font-medium" id="rate-total-price">$1,250.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p class="text-sm text-surface-onVariant mt-2">NOTES: ANY ADDITIONAL TIME NEEDED IN 1/2 HOUR INCREMENTS</p>
            <p class="text-sm text-error mt-1">PAYMENT TERMS: INITIAL PAYMENT: <span id="payment-initial-deposit">$625.00</span></p>
            <p class="text-sm font-medium mt-1">REMAINING BALANCES IS DUE CASH OR ONLINE PAYMENT. PLEASE HAVE YOUR PAYMENT READY WHEN CREW DELIVERS.</p>
          </div>

          <!-- Trip Details Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4">TRIP DETAILS</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Mileage Between:</div>
                <div id="trip-miles">N/A</div>
                <div class="text-surface-onVariant font-medium">Total Cubic Ft:</div>
                <div id="trip-cubicft">N/A</div>
              </div>
            </div>
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4">TOTAL HOURS</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Start Time:</div>
                <div id="jobtime-start">N/A</div>
                <div class="text-surface-onVariant font-medium">Finish Time:</div>
                <div id="jobtime-end">N/A</div>
                <div class="text-surface-onVariant font-medium">Total Hours:</div>
                <div id="jobtime-total">N/A</div>
              </div>
            </div>
          </div>

          <!-- Valuation Coverage -->
          <div class="mb-6">
            <h3 class="font-medium mb-4">VALUATION COVERAGE:</h3>
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <p class="text-sm mb-4">CUSTOMERS DECLARED VALUE AND LIMIT OF COMPANY'S LIABILITY</p>
              <p class="text-sm text-surface-onVariant mb-4">The following is a description of the options for protecting your belongings during the moving process.</p>
              
              <div class="mb-4">
                <p class="font-medium mb-2">Option 1: Released Value-</p>
                <p class="text-sm text-surface-onVariant">$0.60 per pound per article up to a maximum of $2,000.00 Service Provider has a maximum liability under State Law for loss or damage to your property while being handled at the time of the job. Any damages not documented while the movers are present will not be the responsibility of the mover or Service Provider.</p>
              </div>

              <div class="mb-4">
                <p class="font-medium mb-2">Option 2: Full Replacement Value-</p>
                <p class="text-sm text-surface-onVariant mb-2">Additional Charges Apply for This Option</p>
                <ol class="list-decimal list-inside text-sm text-surface-onVariant mb-4">
                  <li>Repair the article to the extent necessary to restore it to the same condition as when it was received by Service Provider or pay you the cost of such repairs.</li>
                  <li>Replace the article with an article of like kind and quality, or pay you the cost of such a replacement.</li>
                </ol>
              </div>

              <p class="text-sm text-surface-onVariant mb-4">Any and all claims must be submitted in writing within 15 days of completed move. See Terms & Condition Services for more information.</p>

              <div class="mb-4">
                <p class="font-medium mb-2">Exclusions-</p>
                <ul class="list-disc list-inside text-sm text-surface-onVariant space-y-1">
                  <li>Items of extraordinary value not listed or claimed on the High Value Inventory Form</li>
                  <li>Lamps, lamp shades, artwork, pictures, mirrors, artificial plants and statues not packed by Service Provider</li>
                  <li>Any marble or glass not crated or boxed by Service Provider</li>
                  <li>Items found in boxes not crated, packed or unpacked by Service Provider</li>
                  <li>Any items packed and/or unpacked by Service Provider where they (Service Provider) were not the sole transporter</li>
                  <li>Any items not put in appropriate boxes or crates, when Service Provider recommended (plasma televisions, grandfather clocks, etc.)</li>
                  <li>Mechanical condition of audio/visual or electronic equipment</li>
                  <li>Computers and battery operated items in transit or in storage</li>
                  <li>Missing hardware not disassembled by Service Provider</li>
                  <li>Gold leaf or plaster frames and chandeliers not crated by Service Provider</li>
                  <li>Pressboard or particle board furniture</li>
                  <li>Previously damaged or repaired items</li>
                  <li>Previously damaged or loose veneer</li>
                  <li>Furniture where glue has dried out</li>
                  <li>Any small, loose items which are not boxed (keys, remote controls, etc.)</li>
                  <li>If one item in a set is damaged, only that one item is covered by the insurance, not the whole set</li>
                  <li>Plants</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Credit Card Authorization Form -->
          <div class="mb-6" id="credit-card-authorization-section">
            <h3 class="font-medium mb-4">CREDIT CARD AUTHORIZATION</h3>
            <div class="bg-surface-variant p-6 rounded-lg elevation-1">
              <div id="credit-card-authorization-edit-card" style="display:none"></div>
              <form class="space-y-6" id="credit-card-authorization-form" onsubmit="return false;">
                <!-- Customer Information -->
                <div>
                  <label class="block text-sm font-medium text-surface-onVariant mb-2">Customer Information</label>
                  <input type="text" name="full_name" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="Full Name">
                </div>
                <!-- Authorization Statement -->
                <div class="text-sm text-surface-onVariant space-y-4">
                  <p class="flex items-start">
                    <span class="mr-2">I,</span>
                    <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center mb-2">
                      <input type="text" name="name" required class="flex-1 p-2 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-sm" placeholder="(Name)">
                      <input type="text" name="title" required class="flex-1 p-2 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-sm" placeholder="(Title)">
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
                          <input type="radio" name="card-type" class="form-radio h-4 w-4 text-primary" required>
                          <span class="ml-2">Visa</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" name="card-type" class="form-radio h-4 w-4 text-primary" required>
                          <span class="ml-2">Mastercard</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" name="card-type" class="form-radio h-4 w-4 text-primary" required>
                          <span class="ml-2">Discover</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" name="card-type" class="form-radio h-4 w-4 text-primary" required>
                          <span class="ml-2">Amex</span>
                        </label>
                      </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-6">
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">Last 8 Digits of Card Number</label>
                        <input type="text" name="last_8_digits" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="12345678" maxlength="8">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">CVC Code</label>
                        <input type="text" name="cvc" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="123" maxlength="4">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">Expiration Date</label>
                        <input type="text" name="expiration_date" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="MM/YY" maxlength="5">
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-surface-onVariant mb-2">Cardholder Name (as it appears on the card)</label>
                      <input type="text" name="cardholder_name" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="John Doe">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">Phone Number</label>
                        <input type="tel" name="phone" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="(123) 456-7890">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">Email</label>
                        <input type="email" name="email" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="john@example.com">
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
                      <input type="text" name="street_address" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="123 Main Street">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-6">
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">City</label>
                        <input type="text" name="city" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="New York">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">State</label>
                        <input type="text" name="state" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="NY">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-surface-onVariant mb-2">ZIP Code</label>
                        <input type="text" name="zip_code" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="10001">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Signature and Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-surface-onVariant mb-2">Cardholder Signature</label>
                    <div class="relative w-full">
                      <input type="text" id="signature-input" name="signature" required class="w-full p-2 sm:p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus-border-primary transition-colors text-xs sm:text-sm" placeholder="Click to sign" readonly>
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
                    <input type="text" name="date" required class="w-full p-3 bg-surface border border-surface-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" placeholder="MM/DD/YYYY">
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
                    <th class="text-left p-2 font-medium text-surface-onVariant">Cubic Ft</th>
                  </tr>
                </thead>
                <tbody id="high-value-inventory-container" class="divide-y divide-surface-variant">
                  <!-- High value items will be loaded here -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- SHIPPER SIGNATURE ON PICK UP -->
          <div class="mb-6">
            <h3 class="font-bold mb-2">SHIPPER SIGNATURE ON PICK UP</h3>
            <p class="text-sm text-surface-onVariant mb-4">This is to certify that the above named materials are properly classified, packaged, marked, and labeled, and are in proper condition for transportation according to the applicable regulations of the DOT. This contract is non negotiable and all monies are due COD are described in your contract.</p>
            <label class="block text-sm font-medium text-surface-onVariant mb-2">Additional Comments</label>
            <textarea id="shipper-comments" class="w-full p-2 border border-surface-variant rounded-lg min-h-[100px] mb-6"></textarea>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="text-center">
              <div class="border-b border-gray-400 pb-2 mb-2" id="shipper-name"></div>
              <span class="text-sm text-gray-600">Customer Name</span>
            </div>
            <div class="text-center">
              <div class="border-b border-gray-400 pb-2 mb-2" id="shipper-signature"></div>
              <span class="text-sm text-gray-600">Signature</span>
            </div>
            <div class="text-center">
              <div class="border-b border-gray-400 pb-2 mb-2" id="shipper-date"></div>
              <span class="text-sm text-gray-600">Date</span>
            </div>
          </div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
              <button id="complete-btn" onclick="handleComplete()" class="bg-primary text-primary-on py-2 px-8 rounded-lg hover:bg-primary/90 transition-colors mb-2 md:mb-0">Complete</button>
              <button onclick="printEstimates()" class="flex items-center gap-2 bg-surface-variant text-surface-onVariant py-2 px-6 rounded-lg hover:bg-surface-variant/80 transition-colors">
                <span class="material-icons">print</span> Print
              </button>
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
                <p>DATE: <span id="service-date">02 / 24 / 2021</span></p>
                <p>TIME: <span id="service-time">04:02 PM</span></p>
                <p>Reference No: <span id="service-reference">#001 | Local - Residential</span></p>
              </div>
              </div>
              </div>

          <!-- Trip Details Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-surface-variant p-4 rounded-lg elevation-1">
              <h3 class="font-medium mb-4">TRIP DETAILS</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="text-surface-onVariant font-medium">Mileage Between:</div>
                <div id="trip-miles">10 miles</div>
                <div class="text-surface-onVariant font-medium">Estimated Weight:</div>
                <div id="trip-cubicft">8000 lbs</div>
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
        </div>
      `,
      'payments': function() {
        const t = window.transaction || {};
        return `
        <div class="max-w-7xl mx-auto">
          <h2 class="text-2xl font-medium mb-6">Payments</h2>
          
          <!-- Payment Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Payment Form Card -->
            <div class="bg-surface rounded-lg elevation-1 overflow-hidden">
              <div class="bg-surface-variant px-6 py-4">
                <h3 class="text-lg font-medium">Make a Payment</h3>
              </div>
              <div class="p-6">
                <form id="stripe-payment-form">
                  <div id="stripe-payment-element" class="mb-6"></div>
                  <button id="stripe-submit-button" class="w-full px-6 py-3 bg-primary text-primary-on rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center gap-2" type="submit">
                    <span id="stripe-button-text">Pay Now</span>
                    <div class="spinner hidden" id="stripe-spinner"></div>
                  </button>
                </form>
              </div>
            </div>

            <!-- Payment Activity Card -->
            <div class="bg-surface rounded-lg elevation-1 overflow-hidden">
              <div class="bg-surface-variant px-6 py-4">
                <h3 class="text-lg font-medium">Payment Activity</h3>
              </div>
              <div class="p-6">
                <div class="overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="border-b border-surface-variant">
                        <th class="text-left py-3 px-4 font-medium text-surface-onVariant">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-surface-onVariant">Date</th>
                        <th class="text-left py-3 px-4 font-medium text-surface-onVariant">Method</th>
                        <th class="text-right py-3 px-4 font-medium text-surface-onVariant">Amount</th>
                      </tr>
                    </thead>
                    <tbody id="payments-activity-table" class="divide-y divide-surface-variant">
                      <!-- Payment rows will be inserted here -->
                    </tbody>
                  </table>
                </div>
                
                <!-- Payment Summary -->
                <div class="mt-6 space-y-2 text-right text-sm" id="payments-totals">
                  <!-- Totals will be inserted here -->
                </div>
              </div>
            </div>
          </div>
        </div>
        `;
      },
      'support': `
        <div class="max-w-4xl mx-auto">
          <h2 class="text-2xl font-medium mb-4">Support</h2>
          <p class="text-surface-onVariant mb-6">Need help? Contact support here.</p>
          <div class="space-y-4">
            @foreach($salesReps as $rep)
                <a href="tel:{{ $rep->phone }}" class="w-full block p-4 bg-primary text-primary-on rounded-lg hover:bg-primary/90 state-layer transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="font-medium">
                            {{ $rep->position }}: {{ $rep->first_name ?? $rep->name }} {{ $rep->last_name ?? '' }}
                        </span>
                        <span class="material-icons">phone</span>
                    </div>
                    <div class="text-xs mt-1 text-primary-on/80">{{ $rep->phone }}</div>
                </a>
            @endforeach
        </div>
      </div>
      `
    };

    // Function to navigate between menu options
    function navigate(page) {
      const mainContent = document.getElementById("main-content");
      const pageContent = pages[page];
      mainContent.innerHTML = typeof pageContent === 'function' ? pageContent() : (pageContent || "<p>Page not found.</p>");
      
      // If navigating to customer-info, populate transaction details
      if (page === 'customer-info' && window.transaction) {
        populateTransactionDetails(window.transaction);
        loadInventoryItems(window.transaction.id);
      }
      
      // If navigating to estimates, populate all necessary data
      if (page === 'estimates' && window.transaction) {
        populateDocuments(window.transaction);
        populateRates(window.transaction);
        // Always fetch inventory for estimates page to ensure up-to-date cubic ft
        const transactionId = window.transaction.id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/customer/inventory/${transactionId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          }
        })
        .then(response => response.json())
        .then(data => {
          window.inventory = data.inventory || [];
          populateTripDetails(window.transaction, window.inventory);
          renderHighValueInventory(window.inventory);
        })
        .catch(() => {
          populateTripDetails(window.transaction, []);
        });

        // Initialize credit card authorization form after the estimates page is loaded
        setTimeout(() => {
          initializeCreditCardAuthorizationForm();
        }, 100);
      }
    }

    function populateTransactionDetails(transaction) {
      // Customer Details
      document.getElementById('customer-fullname').textContent = `${transaction.firstname || ''} ${transaction.lastname || ''}`;
      document.getElementById('customer-email').textContent = transaction.email || 'N/A';
      document.getElementById('customer-movedate').textContent = transaction.date
        ? new Date(transaction.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
        : 'N/A';
      document.getElementById('customer-phone').textContent = transaction.phone || 'N/A';

      // Move From
      document.getElementById('pickup-address').textContent = transaction.pickup_location || 'N/A';

      // Move To
      document.getElementById('delivery-address').textContent = transaction.delivery_location || 'N/A';

      // Comments
      // document.getElementById('customer-comments').textContent = transaction.comments || 'No Comments';
    }

    // Add the loadInventoryItems function from driver page
    function loadInventoryItems(transactionId) {
      // Get CSRF token from meta tag
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      
      // If no token found, show error
      if (!token) {
        console.error('CSRF token not found');
        const container = document.getElementById('inventory-items-container');
        container.innerHTML = `
          <div class="bg-error-container rounded-lg p-6 sm:p-8 text-center">
            <p class="text-error-on text-lg mb-2">Error Loading Inventory</p>
            <p class="text-error-on/70">Security token not found. Please refresh the page.</p>
          </div>
        `;
        return;
      }

      fetch(`/customer/inventory/${transactionId}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        const container = document.getElementById('inventory-items-container');
        if (!data.inventory || data.inventory.length === 0) {
          container.innerHTML = `
            <div class="bg-surface-variant rounded-lg p-6 sm:p-8 text-center">
              <p class="text-surface-onVariant text-lg mb-2">No Inventory Items</p>
              <p class="text-surface-onVariant/70">There are no inventory items recorded for this transaction.</p>
            </div>
          `;
          return;
        }

        // Group items by category
        const groupedItems = data.inventory.reduce((acc, item) => {
          const category = item.category_name || 'Uncategorized';
          if (!acc[category]) {
            acc[category] = [];
          }
          acc[category].push(item);
          return acc;
        }, {});

        // Calculate overall totals
        let totalItems = 0;
        let totalCubicFt = 0;

        // Create HTML for each category
        const html = Object.entries(groupedItems).map(([category, items]) => {
          // Calculate category totals
          const categoryTotal = items.reduce((sum, item) => sum + (parseInt(item.quantity) || 1), 0);
          const categoryCubicFt = items.reduce((sum, item) => sum + (parseFloat(item.cubic_ft) || 0), 0);
          
          // Add to overall totals
          totalItems += categoryTotal;
          totalCubicFt += categoryCubicFt;

          return `
            <div class="mb-6 bg-surface rounded-lg elevation-1 overflow-hidden">
              <div class="bg-surface-variant px-4 sm:px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="text-lg font-medium">${category}</h3>
                <div class="flex flex-wrap gap-4 w-full sm:w-auto">
                  <div class="text-sm bg-surface/50 px-3 py-1 rounded-full">
                    <span class="text-surface-onVariant">Items:</span>
                    <span class="ml-2 font-medium">${categoryTotal}</span>
                  </div>
                  <div class="text-sm bg-surface/50 px-3 py-1 rounded-full">
                    <span class="text-surface-onVariant">Cubic Ft:</span>
                    <span class="ml-2 font-medium">${categoryCubicFt.toFixed(2)}</span>
                  </div>
                </div>
              </div>
              <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="min-w-full inline-block align-middle">
                  <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-surface-variant">
                      <thead>
                        <tr class="bg-surface-variant/50">
                          <th scope="col" class="py-3 px-4 text-left w-20 sm:w-24 font-medium text-surface-onVariant text-sm">Qty</th>
                          <th scope="col" class="py-3 px-4 text-left font-medium text-surface-onVariant text-sm">Item Name</th>
                          <th scope="col" class="py-3 px-4 text-left font-medium text-surface-onVariant text-sm">Cubic Ft</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-surface-variant">
                        ${items.map(item => `
                          <tr class="hover:bg-surface-variant/50 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap text-sm">${item.quantity || 1}</td>
                            <td class="py-3 px-4 text-sm">
                              <div class="font-medium">${item.name}</div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">${item.cubic_ft || '-'}</td>
                          </tr>
                        `).join('')}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          `;
        }).join('');

        // Update overall totals
        document.getElementById('total-items-count').textContent = totalItems;
        document.getElementById('total-cubic-ft').textContent = totalCubicFt.toFixed(2);

        container.innerHTML = html;

        // Render uploaded images gallery
        renderUploadedImagesGallery(window.transaction);
      })
      .catch(error => {
        console.error('Error loading inventory items:', error);
        const container = document.getElementById('inventory-items-container');
        container.innerHTML = `
          <div class="bg-error-container rounded-lg p-6 sm:p-8 text-center">
            <p class="text-error-on text-lg mb-2">Error Loading Inventory</p>
            <p class="text-error-on/70">Failed to load inventory items. Please try again.</p>
          </div>
        `;
      });
    }

    // Add function to render uploaded images gallery
    function renderUploadedImagesGallery(transaction) {
      const gallery = document.getElementById('uploaded-images-gallery');
      if (!gallery) return;
      gallery.innerHTML = '';

      if (!transaction || !transaction.uploaded_image) {
        gallery.innerHTML = '<div class="text-surface-onVariant/70 text-center w-full">No uploaded images.</div>';
        return;
      }

      const images = transaction.uploaded_image.split(',').map(url => url.trim()).filter(Boolean);
      if (images.length === 0) {
        gallery.innerHTML = '<div class="text-surface-onVariant/70 text-center w-full">No uploaded images.</div>';
        return;
      }

      gallery.innerHTML = images.map((url, idx) => `
        <a href="${url}" class="lightbox-image" data-lightbox="uploaded-images" data-title="Uploaded Image ${idx + 1}">
          <img src="${url}" alt="Uploaded Image ${idx + 1}" class="rounded shadow border" style="height:100px;object-fit:cover;max-width:160px;margin-bottom:8px;">
        </a>
      `).join('');
    }

    function populateRates(transaction) {
      // Use the first service if available
      const service = Array.isArray(transaction.services) && transaction.services.length > 0 ? transaction.services[0] : null;
      
      // Initial Rate Description
      let initialDesc = 'Initial Rate';
      if (service) {
        initialDesc = `${service.name || 'Service'} - ${service.rate || '$0.00'} / ${service.crew_rate || ''} / ${service.no_of_crew || ''} Men`;
      }
      document.getElementById('rate-initial-desc').textContent = initialDesc;
      document.getElementById('rate-initial-price').textContent = formatCurrency(service ? service.subtotal : transaction.subtotal);

      // Moving Supplies
      document.getElementById('rate-supplies-desc').textContent = 'Moving Supplies';
      document.getElementById('rate-supplies-price').textContent = formatCurrency(transaction.supplies_total);

      // Additional Services
      document.getElementById('rate-additional-desc').textContent = 'Additional Services';
      document.getElementById('rate-additional-price').textContent = formatCurrency(transaction.additional_services_total);

      // Additional Charges
      document.getElementById('rate-charges-desc').textContent = 'Additional Charges';
      document.getElementById('rate-charges-price').textContent = formatCurrency(transaction.additional_charges_total);

      // Truck Fee Charges
      document.getElementById('rate-truck-desc').textContent = 'Truck Fee Charges';
      document.getElementById('rate-truck-price').textContent = formatCurrency(transaction.truck_fee);

      // Transaction Fee
      document.getElementById('rate-transaction-desc').textContent = 'Transaction Fee';
      document.getElementById('rate-transaction-price').textContent = formatCurrency(transaction.software_fee);

      // Discount
      document.getElementById('rate-discount-desc').textContent = 'Discount';
      document.getElementById('rate-discount-price').textContent = formatCurrency(transaction.discount);

      // Initial Deposit
      document.getElementById('rate-deposit-desc').textContent = 'Initial Deposit';
      document.getElementById('rate-deposit-price').textContent = formatCurrency(transaction.downpayment);

      // Total
      document.getElementById('rate-total-desc').textContent = 'Total';
      document.getElementById('rate-total-price').textContent = formatCurrency(transaction.grand_total);

      // Update PAYMENT TERMS label
      document.getElementById('payment-initial-deposit').textContent = formatCurrency(transaction.downpayment);
    }

    function populateTripDetails(transaction, inventory) {
      const tripMiles = document.getElementById('trip-miles');
      const tripCubicft = document.getElementById('trip-cubicft');
      
      if (tripMiles) {
        tripMiles.textContent = transaction.miles ? `${transaction.miles} miles` : 'N/A';
      }
      
      // Calculate total cubic ft from inventory
      let totalCubicFt = 0;
      if (Array.isArray(inventory)) {
        totalCubicFt = inventory.reduce((sum, item) => sum + ((parseFloat(item.cubic_ft) || 0) * (parseInt(item.quantity) || 1)), 0);
      }
      
      if (tripCubicft) {
        tripCubicft.textContent = totalCubicFt ? totalCubicFt.toFixed(2) : 'N/A';
      }
    }

    // Helper to format as currency
    function formatCurrency(value) {
      let num = parseFloat(value);
      if (isNaN(num)) num = 0;
      return '$' + num.toFixed(2);
    }

    function populateDocuments(transaction) {
      // Header
      document.getElementById('bol-date').textContent = transaction.date ? new Date(transaction.date).toLocaleDateString('en-US') : 'N/A';
      document.getElementById('bol-time').textContent = transaction.time || 'N/A';
      document.getElementById('bol-reference').textContent = `#${transaction.transaction_id} | ${transaction.lead_type || ''}`;
      document.getElementById('bol-usdot').textContent = transaction.usdot || '1877921';
      document.getElementById('bol-mc').textContent = transaction.mc || '077599';
      document.getElementById('bol-address').textContent = transaction.company_address || '519 cascade ct Sewell NJ 08080';
      document.getElementById('bol-phone').textContent = transaction.company_phone || '#877-385-2919';

      // Move From
      document.getElementById('bol-from-name').textContent = `${transaction.firstname || ''} ${transaction.lastname || ''}`;
      document.getElementById('bol-from-phone').textContent = transaction.phone || '';
      document.getElementById('bol-from-email').textContent = transaction.email || '';
      document.getElementById('bol-from-address').textContent = transaction.pickup_location || '';

      // Move To
      document.getElementById('bol-to-name').textContent = `${transaction.firstname || ''} ${transaction.lastname || ''}`;
      document.getElementById('bol-to-phone').textContent = transaction.phone || '';
      document.getElementById('bol-to-email').textContent = transaction.email || '';
      document.getElementById('bol-to-address').textContent = transaction.delivery_location || '';
    }

    function renderHighValueInventory(inventory) {
      // Filter for high value items (adjust the condition as needed)
      const highValueItems = inventory.filter(item =>
        item.category_name && item.category_name.toLowerCase().includes('high value')
      );

      const container = document.getElementById('high-value-inventory-container');
      if (!container) return;

      if (highValueItems.length === 0) {
        container.innerHTML = `<tr><td colspan="3" class="text-center p-4">No High Value Items</td></tr>`;
        return;
      }

      container.innerHTML = highValueItems.map(item => `
        <tr class="hover:bg-surface-variant/50 transition-colors">
          <td class="p-2">${item.name}</td>
          <td class="p-2">${item.quantity || 1}</td>
          <td class="p-2">${item.cubic_ft || '-'}</td>
        </tr>
      `).join('');
    }

    function initializeCreditCardAuthorizationForm() {
      const form = document.getElementById('credit-card-authorization-form');
      const editCardDiv = document.getElementById('credit-card-authorization-edit-card');
      if (!form) {
        console.log('Credit card authorization form not found');
        return;
      }

      // Helper to show/hide form and edit card
      function showForm() {
        form.style.display = '';
        if (editCardDiv) editCardDiv.style.display = 'none';
      }

      function showEditCard(data) {
        form.style.display = 'none';
        if (editCardDiv) {
          editCardDiv.style.display = '';
          editCardDiv.innerHTML = `
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-4">
              <div class="flex items-center justify-between">
                <div>
                  <div class="font-semibold text-green-800 mb-1">Credit Card Authorization is already saved.</div>
                  <div class="text-green-700 text-sm">Cardholder: <b>${data.cardholder_name || ''}</b> <br>Date: <b>${data.date || ''}</b></div>
                </div>
                <button id="edit-cc-auth-btn" class="ml-4 px-4 py-2 bg-primary text-primary-on rounded hover:bg-primary/90">Edit</button>
              </div>
            </div>
          `;
          // Edit button handler
          const editBtn = document.getElementById('edit-cc-auth-btn');
          if (editBtn) {
            editBtn.onclick = function() {
              // Fill form with data and show it
              for (const [key, value] of Object.entries(data)) {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) input.value = value || '';
              }
              // Set radio for card type
              if (data.card_type) {
                const radios = form.querySelectorAll('[name="card-type"]');
                radios.forEach(r => {
                  if (r.nextElementSibling && r.nextElementSibling.textContent.trim() === data.card_type) {
                    r.checked = true;
                  } else {
                    r.checked = false;
                  }
                });
              }
              // Set signature preview
              if (data.signature) {
                const sigImg = document.getElementById('signature-img');
                const sigPreview = document.getElementById('signature-preview');
                if (sigImg && sigPreview) {
                  sigImg.src = data.signature;
                  sigPreview.classList.remove('hidden');
                }
                const sigData = document.getElementById('signature-data');
                if (sigData) sigData.value = data.signature;
              }
              // Set edit id
              form.setAttribute('data-edit-id', data.id);
              showForm();
            };
          }
        }
      }

      // Check if there's an existing authorization
      const transactionId = window.transaction.id;
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      
      fetch(`/credit-card-authorization/${transactionId}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data) {
          showEditCard(data.data);
          populateShipperSignatureFromCCAuth(data.data);
        } else {
          showForm();
          populateShipperSignatureFromCCAuth({});
        }
      })
      .catch(error => {
        console.error('Error loading credit card authorization:', error);
        showForm();
        populateShipperSignatureFromCCAuth({});
      });

      // Set today's date by default
      const today = new Date();
      const formattedDate = today.toLocaleDateString('en-US', {
        month: '2-digit',
        day: '2-digit',
        year: 'numeric'
      });
      
      const dateInput = form.querySelector('[name="date"]');
      if (dateInput) {
        dateInput.value = formattedDate;
      }

      // Handle form submission
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Validate signature
        const signatureData = document.getElementById('signature-data');
        if (!signatureData || !signatureData.value) {
          Swal.fire('Error', 'Please provide a signature', 'error');
            return false;
          }

        // Validate form
        if (!form.checkValidity()) {
          form.reportValidity();
          return false;
        }

        // Get form data
        const formData = new FormData(form);
        // Convert MM/DD/YYYY to YYYY-MM-DD for MySQL
        let dateValue = formData.get('date');
        if (dateValue && dateValue.includes('/')) {
          const [month, day, year] = dateValue.split('/');
          dateValue = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        }
        const data = {
          transaction_id: window.transaction.id,
          full_name: formData.get('full_name'),
          name: formData.get('name'),
          title: formData.get('title'),
          card_type: Array.from(form.querySelectorAll('[name="card-type"]')).find(r => r.checked)?.nextElementSibling.textContent.trim() || '',
          last_8_digits: formData.get('last_8_digits'),
          cvc: formData.get('cvc'),
          expiration_date: formData.get('expiration_date'),
          cardholder_name: formData.get('cardholder_name'),
          phone: formData.get('phone'),
          email: formData.get('email'),
          street_address: formData.get('street_address'),
          city: formData.get('city'),
          state: formData.get('state'),
          zip_code: formData.get('zip_code'),
          signature: signatureData.value,
          date: dateValue
        };

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
          Swal.fire('Error', 'CSRF token not found', 'error');
          return false;
        }

        // Determine if this is an edit or new submission
        const editId = form.getAttribute('data-edit-id');
        const url = editId ? `/credit-card-authorization/${editId}` : '/credit-card-authorization';
        const method = editId ? 'PUT' : 'POST';

        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
          const originalButtonText = submitButton.innerHTML;
          submitButton.disabled = true;
          submitButton.innerHTML = '<span class="animate-spin mr-2">⟳</span> Submitting...';

          // Submit form data
          fetch(url, {
            method: method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token.getAttribute('content')
            },
            body: JSON.stringify(data)
          })
          .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              return response.text().then(text => {
                Swal.fire({
                  title: 'Server Error',
                  html: `<pre style="max-height:300px;overflow:auto;text-align:left;">${text.replace(/</g, '&lt;')}</pre>`,
                  icon: 'error',
                  width: 700
                });
                throw new Error('Server did not return JSON.');
              });
            }
            return response.json();
          })
          .then(result => {
            if (result.success) {
              Swal.fire({
                title: 'Success!',
                text: 'Credit Card Authorization saved successfully',
                icon: 'success'
              }).then(() => {
                // Reload the authorization data
                loadCreditCardAuthorization();
              });
            } else {
              throw new Error(result.message || 'Failed to save Credit Card Authorization');
            }
          })
          .catch(error => {
            if (error.message !== 'Server did not return JSON.') {
              Swal.fire({
                title: 'Error!',
                text: error.message || 'An error occurred while saving. Please try again.',
                icon: 'error'
              });
            }
          })
          .finally(() => {
            // Reset button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
          });
        }

        return false;
      });

    // Clear signature functionality
      const clearSignatureBtn = document.getElementById('clear-signature');
      if (clearSignatureBtn) {
        clearSignatureBtn.addEventListener('click', function() {
          const signatureData = document.getElementById('signature-data');
          const signatureImg = document.getElementById('signature-img');
          const signaturePreview = document.getElementById('signature-preview');
          const signatureInput = document.getElementById('signature-input');

          if (signatureData) signatureData.value = '';
          if (signatureImg) signatureImg.src = '';
          if (signaturePreview) signaturePreview.classList.add('hidden');
          if (signatureInput) signatureInput.value = '';
        });
      }
    }

    // Add the loadCreditCardAuthorization function
    function loadCreditCardAuthorization() {
      initializeCreditCardAuthorizationForm();
    }

    // Add function to handle completion
    function handleComplete() {
      const completeBtn = document.getElementById('complete-btn');
      const comment = document.getElementById('shipper-comments').value;
      const transactionId = window.transaction?.id;
      
      if (!transactionId) {
        Swal.fire('Error', 'No transaction selected.', 'error');
        return;
      }
      
      // Show loading state
      completeBtn.disabled = true;
      completeBtn.innerHTML = '<span class="animate-spin mr-2">⟳</span> Saving...';

      // Fetch the credit card authorization for this transaction
      fetch(`/credit-card-authorization/${transactionId}`)
        .then(res => {
          if (!res.ok) {
            throw new Error('Failed to fetch credit card authorization');
          }
          return res.json();
        })
        .then(result => {
          if (result.success && result.data) {
            // Update the comment field
            const formData = {
              comments: comment,  // Use the comment from textarea
              transaction_id: transactionId,
              // Include all existing fields to prevent data loss
              full_name: result.data.full_name,
              name: result.data.name,
              title: result.data.title,
              card_type: result.data.card_type,
              last_8_digits: result.data.last_8_digits,
              cvc: result.data.cvc,
              expiration_date: result.data.expiration_date,
              cardholder_name: result.data.cardholder_name,
              phone: result.data.phone,
              email: result.data.email,
              street_address: result.data.street_address,
              city: result.data.city,
              state: result.data.state,
              zip_code: result.data.zip_code,
              signature: result.data.signature,
              date: result.data.date
            };

            return fetch(`/credit-card-authorization/${result.data.id}`, {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify(formData)
            });
          } else {
            throw new Error('No credit card authorization found for this transaction.');
          }
        })
        .then(res => {
          if (!res.ok) {
            throw new Error('Failed to update comment');
          }
          return res.json();
        })
        .then(result => {
          if (result.success) {
            Swal.fire({
              title: 'Success!',
              text: 'Comments saved successfully',
              icon: 'success'
            });
          } else {
            throw new Error(result.message || 'Failed to save comments');
          }
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: error.message || 'An error occurred while saving. Please try again.',
            icon: 'error'
          });
        })
        .finally(() => {
          // Reset button state
          completeBtn.disabled = false;
          completeBtn.innerHTML = 'Complete';
        });
    }

    // Add function to populate shipper signature from credit card authorization
    function populateShipperSignatureFromCCAuth(auth) {
      if (!auth) return;

      // Populate shipper name
      const shipperName = document.getElementById('shipper-name');
      if (shipperName) {
        shipperName.textContent = auth.cardholder_name || auth.full_name || '';
      }

      // Populate shipper signature
      const shipperSignature = document.getElementById('shipper-signature');
      if (shipperSignature && auth.signature) {
        shipperSignature.innerHTML = `<img src="${auth.signature}" alt="Signature" class="max-h-16" />`;
      }

      // Populate shipper date
      const shipperDate = document.getElementById('shipper-date');
      if (shipperDate) {
        shipperDate.textContent = auth.date ? new Date(auth.date).toLocaleDateString('en-US') : '';
      }

      // Populate comments field
      const shipperComments = document.getElementById('shipper-comments');
      if (shipperComments) {
        shipperComments.value = auth.comments || '';
      }
    }

    // Add Stripe.js if not already present
    if (!window.Stripe) {
      const stripeScript = document.createElement('script');
      stripeScript.src = 'https://js.stripe.com/v3/';
      document.head.appendChild(stripeScript);
    }

    let stripe, elements, paymentElement;

    async function initializeStripePayment() {
      // Wait for Stripe.js to load
      if (!window.Stripe) {
        await new Promise(resolve => {
          const check = setInterval(() => {
            if (window.Stripe) {
              clearInterval(check);
              resolve();
            }
          }, 50);
        });
      }

      // Check if initial deposit is already paid
      const response = await fetch(`/customer/payments/${window.transaction.id}`);
      const data = await response.json();
      if (data.success) {
        const payments = data.payments || [];
        const grandTotal = parseFloat(window.transaction.grand_total || 0);
        const totalPaid = payments
          .filter(payment => payment.status === 'Succeeded')
          .reduce((sum, payment) => sum + parseFloat(payment.amount), 0);
        const remaining = Math.max(grandTotal - totalPaid, 0);
        const hasPaidInitialDeposit = payments.some(payment => 
          payment.status === 'Succeeded' && 
          parseFloat(payment.amount) === parseFloat(window.transaction.downpayment)
        );

        if (remaining === 0) {
          document.getElementById('stripe-payment-form').innerHTML = `
            <div class="p-8 text-center">
              <div class="text-3xl mb-4">🎉</div>
              <div class="text-xl font-bold mb-2">Thank you!</div>
              <div class="text-surface-onVariant">Your balance is paid in full.</div>
            </div>
          `;
          return;
        }

        if (hasPaidInitialDeposit) {
          Swal.fire({
            title: 'Initial Deposit Paid',
            text: 'You have already paid the initial deposit. You can pay the remaining balance after the service is completed.',
            icon: 'info',
            confirmButtonColor: '#1061B1'
          });
          document.getElementById('stripe-submit-button').style.display = 'none';
          return;
        }
      }

      // Fetch client secret and publishable key from backend
      const paymentResponse = await fetch('/payment/process', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          transaction_id: window.transaction.id,
          payment_method_id: 'pm_card_visa' // Remove for live, keep for test
        })
      });
      const paymentData = await paymentResponse.json();
      if (!paymentData.success) {
        showStripeMessage(paymentData.message, 'error');
        return;
      }
      // Use the publishable key from backend response
      const publishableKey = paymentData.stripeKey;
      if (!publishableKey) {
        showStripeMessage('Stripe publishable key not found.', 'error');
        return;
      }
      stripe = Stripe(publishableKey);
      elements = stripe.elements({ clientSecret: paymentData.clientSecret });
      paymentElement = elements.create('payment');
      paymentElement.mount('#stripe-payment-element');
    }

    function showStripeMessage(message, type = 'error') {
      Swal.fire({
        icon: type === 'error' ? 'error' : 'success',
        title: type === 'error' ? 'Error' : 'Success',
        text: message,
        confirmButtonColor: type === 'error' ? '#dc3545' : '#28a745',
        confirmButtonText: 'OK'
      });
    }

    async function handleStripeSubmit(e) {
      e.preventDefault();
      document.getElementById('stripe-submit-button').disabled = true;
      document.getElementById('stripe-spinner').classList.remove('hidden');
      document.getElementById('stripe-button-text').classList.add('hidden');
      try {
        const { error, paymentIntent } = await stripe.confirmPayment({
          elements,
          confirmParams: {
            return_url: `${window.location.origin}/payment/success`,
          },
          redirect: 'if_required'
        });
        if (error) {
          showStripeMessage(error.message, 'error');
        } else if (paymentIntent) {
          // Confirm on server
          const res = await fetch('/payment/confirm', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
              transaction_id: window.transaction.id,
              payment_intent_id: paymentIntent.id
            })
          });
          const result = await res.json();
          if (result.success) {
            showStripeMessage('Payment successful!', 'success');
            setTimeout(() => navigate('payments'), 500); // short delay for user to see the message
          } else {
            showStripeMessage(result.message, 'error');
          }
        }
      } catch (err) {
        showStripeMessage(err.message, 'error');
      }
      document.getElementById('stripe-submit-button').disabled = false;
      document.getElementById('stripe-spinner').classList.add('hidden');
      document.getElementById('stripe-button-text').classList.remove('hidden');
    }

    // Extend navigate to initialize Stripe on payments tab
    const originalNavigate = navigate;
    navigate = function(page) {
      originalNavigate(page);
      if (page === 'payments') {
        setTimeout(() => {
          initializeStripePayment();
          document.getElementById('stripe-payment-form').addEventListener('submit', handleStripeSubmit);
          if (window.transaction && window.transaction.id) {
            loadPaymentsActivity(window.transaction.id);
          }
        }, 200);
      }
    };

    // Add JS to fetch and render payment activity
    async function loadPaymentsActivity(transactionId) {
      const res = await fetch(`/customer/payments/${transactionId}`);
      const data = await res.json();
      if (!data.success) return;

      const tbody = document.querySelector('#payments-activity-table');
      if (!tbody) return;
      tbody.innerHTML = '';
      let totalPaid = 0;

      data.payments.forEach(payment => {
        totalPaid += parseFloat(payment.amount);
        let statusClass = '';
        let statusText = payment.status;
        if (payment.status === 'Succeeded') {
          statusClass = 'bg-green-600 text-white';
          statusText = 'PAID';
        } else {
          statusClass = 'bg-yellow-100 text-yellow-800';
        }
        // Format date to be more readable
        const paymentDate = new Date(payment.created_at);
        const formattedDate = paymentDate.toLocaleDateString('en-US', {
          weekday: 'short',
          month: 'short',
          day: 'numeric',
          year: 'numeric',
          hour: 'numeric',
          minute: 'numeric',
          hour12: true
        });
        tbody.innerHTML += `
          <tr class="hover:bg-surface-variant/50 transition-colors">
            <td class="py-3 px-4">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                ${statusText}
              </span>
            </td>
            <td class="py-3 px-4 text-surface-onVariant whitespace-nowrap">${formattedDate}</td>
            <td class="py-3 px-4 text-surface-onVariant">${payment.payment_method || '-'}</td>
            <td class="py-3 px-4 text-right font-medium">$${parseFloat(payment.amount).toFixed(2)}</td>
          </tr>
        `;
      });

      // Totals
      const t = window.transaction || {};
      const grandTotal = parseFloat(t.grand_total || 0);
      const remaining = Math.max(grandTotal - totalPaid, 0);
      
      document.getElementById('payments-totals').innerHTML = `
        <div class="flex justify-between items-center py-2">
          <span class="text-surface-onVariant">Total Paid:</span>
          <span class="font-medium">$${totalPaid.toFixed(2)}</span>
        </div>
        <div class="flex justify-between items-center py-2">
          <span class="text-surface-onVariant">Grand Total:</span>
          <span class="font-medium">$${grandTotal.toFixed(2)}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-t border-surface-variant mt-2 pt-2">
          <span class="text-surface-onVariant">Remaining Balance:</span>
          <span class="font-medium ${remaining > 0 ? 'text-error' : 'text-green-600'}">$${remaining.toFixed(2)}</span>
        </div>
      `;
    }

    // Add print function for estimates
    async function getJobTimeForPrint(transactionId) {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const response = await fetch(`/driver/job-time/${transactionId}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        }
      });
      const data = await response.json();
      return data.job_time || null;
    }

    async function printEstimates() {
      const printWindow = window.open('', '_blank');
      const now = new Date();
      const dateStr = now.toLocaleDateString('en-US');
      const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
      let signatureImgSrc = '';
      const sigImgElem = document.querySelector('#shipper-signature img');
      if (sigImgElem) {
        signatureImgSrc = sigImgElem.src;
      }
      // Fetch job time for this transaction
      const jobTime = await getJobTimeForPrint(window.transaction.transaction_id);
      // Format job time
      const startTime = jobTime && jobTime.start_time
        ? new Date(jobTime.start_time).toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
          })
        : '';
      const finishTime = jobTime && jobTime.end_time
        ? new Date(jobTime.end_time).toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
          })
        : '';
      let totalHours = '';
      if (jobTime && jobTime.start_time && jobTime.end_time) {
        const start = new Date(jobTime.start_time);
        const end = new Date(jobTime.end_time);
        const diffMs = end - start;
        if (diffMs > 0) {
          const hours = Math.floor(diffMs / 3600000);
          const minutes = Math.floor((diffMs % 3600000) / 60000);
          totalHours = `${hours} Hour${hours !== 1 ? 's' : ''} ${minutes} Minute${minutes !== 1 ? 's' : ''}`;
        } else {
          totalHours = '0 Hours 0 Minutes';
        }
      }
      // Define signature and driver info for valuation coverage
      const signatureImg = jobTime && jobTime.start_signature
        ? `<img src="${jobTime.start_signature}" alt="Signature" style="max-height:32px;max-width:160px;object-fit:contain;background:#fff;display:inline-block;" />`
        : `<span style=\"display:inline-block; border-bottom:1px solid #000; width:220px; height:18px; vertical-align:middle;\"></span>`;
      let driverName = 'Driver Name';
      let driverPhone = 'Driver Phone';
      if (window.salesReps && window.transaction?.assigned_agent) {
        const driver = window.salesReps.find(rep => rep.agent_id == window.transaction.assigned_agent);
        if (driver) {
          driverName = `${driver.first_name || ''} ${driver.last_name || ''}`.trim();
          driverPhone = driver.phone || '';
        }
      }
      // Company info
      const companyName = window.transaction?.company_name || 'COMPETITIVE RELOCATION SERVICES';
      const companyAddress = window.transaction?.company_address || '13 Galaxy Ct<br>Sewell, NJ 08080';
      const companyPhone = window.transaction?.company_phone || '(877) 385-2919';
      const usdot = window.transaction?.usdot || '1877921';
      const mc = window.transaction?.mc || '677599';
      // Customer info
      const customerName = `${window.transaction?.firstname || ''} ${window.transaction?.lastname || ''}`.trim();
      const pickup = window.transaction?.pickup_location || '';
      const delivery = window.transaction?.delivery_location || '';
      const cityStateZip = window.transaction?.pickup_citystatezip || '';
      const deliveryCityStateZip = window.transaction?.delivery_citystatezip || '';
      const phone = window.transaction?.phone || '';
      // Rates
      const subtotal = formatCurrency(window.transaction?.subtotal || 0);
      const downpayment = formatCurrency(window.transaction?.downpayment || 0);
      const grandTotal = formatCurrency(window.transaction?.grand_total || 0);
      const discount = formatCurrency(window.transaction?.discount || 0);
      // Trip details
      const miles = window.transaction?.miles || '';
      const totalCubicFt = window.inventory ? window.inventory.reduce((sum, item) => sum + ((parseFloat(item.cubic_ft) || 0) * (parseInt(item.quantity) || 1)), 0).toFixed(2) : '';
      // Get the value from the shipper-comments textarea for printing
      const shipperCommentsValue = document.getElementById('shipper-comments')?.value || '';
      // Print content
      const printContent = `
<!DOCTYPE html>
<html>
<head>
  <title>Bill of Lading - ${window.transaction?.transaction_id || ''}</title>
  <style>
    @page { size: A4; margin: 15mm; }
    body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
    .bol-header { text-align: center; margin-bottom: 8px; }
    .bol-logo { max-width: 120px; margin-bottom: 2px; }
    .bol-title { font-size: 18px; font-weight: bold; letter-spacing: 1px; }
    .bol-company { font-size: 13px; font-weight: bold; margin-top: 2px; }
    .bol-contact { font-size: 12px; margin-bottom: 2px; }
    .bol-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .bol-table th, .bol-table td { border: 1px solid #888; padding: 5px 7px; vertical-align: top; }
    .bol-table th { background: #e9e9e9; font-weight: bold; font-size: 12px; }
    .bol-section-title { background: #e9e9e9; font-weight: bold; font-size: 13px; padding: 4px 7px; border: 1px solid #888; }
    .bol-highlight { background: #fff700; font-weight: bold; }
    .bol-red { color: #c00; font-weight: bold; }
    .bol-yellow { background: #fff700; }
    .bol-signature-label { font-size: 11px; color: #333; margin-top: 2px; }
    .bol-signature-line { border-bottom: 1px solid #000; width: 90%; margin: 0 auto 2px auto; min-height: 32px; text-align: center; }
    .bol-signature-img { max-height: 40px; max-width: 160px; object-fit: contain; background: #fff; display: inline-block; }
    .bol-no-border { border: none !important; }
    .bol-small { font-size: 11px; }
    .bol-table td.bol-no-border { background: none; }
    .bol-table th.bol-no-border { background: none; }
    .bol-table th.bol-yellow, .bol-table td.bol-yellow { background: #fff700; }
    .bol-table th.bol-red { color: #c00; background: #fff; }
    .bol-table th.bol-section { background: #e9e9e9; font-size: 13px; }
    .bol-table th, .bol-table td { word-break: break-word; }
    .bol-table td.bol-center { text-align: center; }
    .bol-table td.bol-bold { font-weight: bold; }
    .bol-table td.bol-red { color: #c00; font-weight: bold; }
    .bol-table td.bol-yellow { background: #fff700; }
    .bol-table td.bol-small { font-size: 11px; }
    .bol-table td.bol-signature { min-width: 120px; }
    .bol-table td.bol-signature img { display: block; margin: 0 auto; }
    .bol-table td.bol-signature-label { font-size: 10px; color: #666; text-align: center; }
    .bol-table td.bol-no-border { border: none; }
    .bol-table th.bol-no-border { border: none; }
    .bol-table th.bol-section { border: 1px solid #888; }
    .bol-table td.bol-section { border: 1px solid #888; }
    .bol-table th, .bol-table td { vertical-align: top; }
    .bol-table td.bol-initial { background: #fff700; font-weight: bold; text-align: left; }
    .bol-table td.bol-initial-box { background: #fff700; font-weight: bold; text-align: left; border: 1px solid #888; }
    .bol-table td.bol-payment { font-size: 11px; }
    .bol-table td.bol-comment { font-size: 11px; }
    .bol-table td.bol-hv { min-width: 60px; }
    .bol-table td.bol-hv-comment { min-width: 120px; }
    .bol-table td.bol-hv-center { text-align: center; }
    .bol-table td.bol-hv-bold { font-weight: bold; }
    .bol-table td.bol-hv-small { font-size: 11px; }
    .bol-table td.bol-hv-yellow { background: #fff700; }
    .bol-table td.bol-hv-red { color: #c00; font-weight: bold; }
    .bol-table td.bol-hv-signature { min-width: 120px; }
    .bol-table td.bol-hv-signature img { display: block; margin: 0 auto; }
    .bol-table td.bol-hv-signature-label { font-size: 10px; color: #666; text-align: center; }
    .bol-table td.bol-hv-no-border { border: none; }
    .bol-table th.bol-hv-no-border { border: none; }
    .bol-table th.bol-hv-section { border: 1px solid #888; }
    .bol-table td.bol-hv-section { border: 1px solid #888; }
    @media print { header, footer { display: none !important; } }
  </style>
</head>
<body>
  <div class="bol-header">
    <table style="width:100%; border:none; margin-bottom:8px;">
  <tr>
    <td style="width:33%; text-align:left; font-size:13px; vertical-align:top; line-height:1.4;">
      <b>Date:</b> ${window.transaction?.date ? new Date(window.transaction.date).toLocaleDateString('en-US') : dateStr}<br>
      <b>Time:</b> ${window.transaction?.time || timeStr}
    </td>
    <td style="width:34%; text-align:center; vertical-align:top;">
      <div style="margin-bottom:2px;">
        <img src="../assets/images/competitive.png" alt="Logo" style="max-width:120px; max-height:60px; display:block; margin:0 auto 2px auto;" />
      </div>
      <div style="font-size:16px; font-weight:bold; letter-spacing:1px;">BILL OF LADING</div>
      <div style="font-size:13px; font-weight:bold;">COMPETITIVE RELOCATION SERVICES</div>
      <div style="font-size:12px;">13 Galaxy Ct<br>Sewell, NJ 08080<br>(877) 385-2919</div>
    </td>
    <td style="width:33%; text-align:right; font-size:13px; vertical-align:top; line-height:1.4;">
      <b>USDOT #1877921</b><br>
      <b>MC #677599</b>
    </td>
  </tr>
</table>
  </div>

  <table class="bol-table">
    <tr>
      <th colspan="2" class="bol-section-title">SHIP FROM:</th>
      <th colspan="2" class="bol-section-title">SHIP TO:</th>
    </tr>
    <tr>
      <td class="bol-small"><b>CUSTOMER NAME:</b></td>
      <td class="bol-small">${customerName}</td>
      <td class="bol-small"><b>CUSTOMER NAME:</b></td>
      <td class="bol-small">${customerName}</td>
    </tr>
    <tr>
      <td class="bol-small"><b>PICK UP ADDRESS:</b></td>
      <td class="bol-small">${pickup}</td>
      <td class="bol-small"><b>DELIVERY ADDRESS:</b></td>
      <td class="bol-small">${delivery}</td>
    </tr>
    <tr>
      <td class="bol-small"><b>Phone:</b></td>
      <td class="bol-small">${phone}</td>
      <td class="bol-small"><b>Phone:</b></td>
      <td class="bol-small">${phone}</td>
    </tr>
    <tr>
      <td class="bol-small"><b>ADDITIONAL INFORMATION:</b></td>
      <td colspan="3" class="bol-small"></td>
    </tr>
  </table>

  <table class="bol-table">
    <tr>
      <th colspan="2" class="bol-section-title">RATES:</th>
      <th colspan="2" class="bol-section-title">VALUATION COVERAGE:</th>
    </tr>
    <tr>
      <td colspan="2" class="bol-small" style="width:50%;vertical-align:top;">
        <table style="width:100%; border-collapse:collapse; font-size:11px;">
          <tr>
            <th style="text-align:left; border-bottom:1px solid #888;">Description</th>
            <th style="text-align:right; border-bottom:1px solid #888;">Price</th>
          </tr>
          <tr>
            <td>MOVING SERVICES - ${window.transaction?.services?.[0]?.rate || '$0.00'} / ${window.transaction?.services?.[0]?.crew_rate || ''} / ${window.transaction?.services?.[0]?.no_of_crew || ''} Men</td>
            <td style="text-align:right;">${formatCurrency(window.transaction?.subtotal || 0)}</td>
          </tr>
          <tr>
            <td>Moving Supplies</td>
            <td style="text-align:right;">${formatCurrency(window.transaction?.supplies_total || 0)}</td>
          </tr>
          <tr>
            <td>Additional Services</td>
            <td style="text-align:right;">${formatCurrency(window.transaction?.additional_services_total || 0)}</td>
          </tr>
          <tr>
            <td>Additional Charges</td>
            <td style="text-align:right;">${formatCurrency(window.transaction?.additional_charges_total || 0)}</td>
          </tr>
          <tr>
            <td>Truck Fee Charges</td>
            <td style="text-align:right;">${formatCurrency(window.transaction?.truck_fee || 0)}</td>
          </tr>
          <tr>
            <td>Transaction Fee</td>
            <td style="text-align:right;">${formatCurrency(window.transaction?.software_fee || 0)}</td>
          </tr>
          <tr>
            <td>Discount</td>
            <td style="text-align:right;">${formatCurrency(window.transaction?.discount || 0)}</td>
          </tr>
          <tr>
            <td style="font-weight:bold;">Initial Deposit</td>
            <td style="text-align:right; font-weight:bold;">${formatCurrency(window.transaction?.downpayment || 0)}</td>
          </tr>
          <tr>
            <td style="font-weight:bold;">Total</td>
            <td style="text-align:right; font-weight:bold;">${formatCurrency(window.transaction?.grand_total || 0)}</td>
          </tr>
          <tr>
            <td style="font-weight:bold;">Remaining Balance</td>
            <td style="text-align:right; font-weight:bold;">${formatCurrency((parseFloat(window.transaction?.grand_total || 0) - parseFloat(window.transaction?.downpayment || 0)) || 0)}</td>
          </tr>
        </table>
      </td>
      <td colspan="2" class="bol-small" style="width:50%;vertical-align:top;">
        <span class="bol-red">CUSTOMERS DECLARED VALUE AND LIMIT OF COMPANY'S LIABILITY</span><br>
        <span style="font-size:11px;">Since rates are based on the declared value of the property, and the customer (shipper) is required to declare in writing the released value of the property, the agreed or declared value of the property is hereby specifically stated to be not exceeding $0.60 cents per pound per article with a limit of $2,000.00 while being handled by the carrier.</span><br><br>
        <span class="bol-yellow" style="padding:2px 6px;">Signature:</span> ${signatureImg}<br>
        Driver: ${driverName}<br>
        Phone: ${driverPhone}
      </td>
    </tr>
  </table>

  <table class="bol-table">
    <tr>
      <th class="bol-section-title">TRIP DETAILS:</th>
      <th class="bol-section-title">TOTAL HOURS:</th>
    </tr>
    <tr>
      <td class="bol-small" style="width:50%;vertical-align:top;">
        <b>MILEAGE BETWEEN:</b> ${miles}<br>
        <b>ESTIMATED WEIGHT:</b> ${totalCubicFt}
      </td>
      <td class="bol-small" style="width:50%;vertical-align:top;">
        <b>START TIME:</b> ${startTime}<br>
        <b>FINISH TIME:</b> ${finishTime}<br>
        <b>TOTAL HOURS:</b> ${totalHours}
      </td>
    </tr>
  </table>

  <table class="bol-table">
    <tr>
      <th class="bol-section-title">ADDITIONAL COMMENTS:</th>
      <th class="bol-section-title">PAYMENT TERMS:</th>
    </tr>
    <tr>
      <td class="bol-comment" style="width:50%;vertical-align:top;">
        ${shipperCommentsValue}
      </td>
      <td class="bol-payment" style="width:50%;vertical-align:top;">
        DEPOSITS ARE NON-REFUNDABLE AND REMAINING BALANCES IS DUE CASH ONLY. PLEASE HAVE YOUR PAYMENT READY WHEN CREW DELIVERY.
      </td>
    </tr>
  </table>

  <table class="bol-table">
    <tr>
      <th class="bol-section-title">Shipper Signature on Pick Up</th>
      <th class="bol-section-title">Driver Signature on Pick Up</th>
    </tr>
    <tr>
      <td class="bol-signature" style="width:50%;vertical-align:top;">
        Shipper Signature/Date:<br>
        <div style="display:flex;align-items:center;gap:20px;">
          <div>
            ${signatureImgSrc
              ? `<img src="${signatureImgSrc}" alt="Shipper Signature" style="max-height:32px;max-width:160px;object-fit:contain;background:#fff;display:inline-block;" />`
              : `<span style=\"display:inline-block; border-bottom:1px solid #000; width:220px; height:18px; vertical-align:middle;\"></span>`
            }
          </div>
          <div>
            <span style="display:inline-block; border-bottom:1px solid #000; width:120px; height:18px; vertical-align:middle;">${new Date().toLocaleDateString('en-US')}</span>
          </div>
        </div>
        <br><span class="bol-small">This is to certify that the above-named materials are properly classified, packaged, marked, and labeled, and are in proper condition for transportation according to the applicable regulations of the DOT. This contract is non-negotiable, and all monies are due COD are described in your contract.</span>
      </td>
      <td class="bol-signature" style="width:50%;vertical-align:top;">
        Driver Signature/Date:<br>
        <div style="display:flex;align-items:center;gap:20px;">
          <div>
            ${jobTime && jobTime.start_signature
              ? `<img src="${jobTime.start_signature}" alt="Driver Signature" style="max-height:32px;max-width:160px;object-fit:contain;background:#fff;display:inline-block;" />`
              : `<span style=\"display:inline-block; border-bottom:1px solid #000; width:220px; height:18px; vertical-align:middle;\"></span>`
            }
          </div>
          <div>
            <span style="display:inline-block; border-bottom:1px solid #000; width:120px; height:18px; vertical-align:middle;">${jobTime && jobTime.start_time ? new Date(jobTime.start_time).toLocaleDateString('en-US') : ''}</span>
          </div>
        </div>
        Time: <span style="display:inline-block; border-bottom:1px solid #000; width:120px; height:18px; vertical-align:middle;">${jobTime && jobTime.start_time ? new Date(jobTime.start_time).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : ''}</span><br><br>
        Drivers Signature on Delivery<br>
        Driver Signature/Date:<br>
        <div style="display:flex;align-items:center;gap:20px;">
          <div>
            ${jobTime && jobTime.end_signature
              ? `<img src="${jobTime.end_signature}" alt="Driver Signature" style="max-height:32px;max-width:160px;object-fit:contain;background:#fff;display:inline-block;" />`
              : `<span style=\"display:inline-block; border-bottom:1px solid #000; width:220px; height:18px; vertical-align:middle;\"></span>`
            }
          </div>
          <div>
            <span style="display:inline-block; border-bottom:1px solid #000; width:120px; height:18px; vertical-align:middle;">${jobTime && jobTime.end_time ? new Date(jobTime.end_time).toLocaleDateString('en-US') : ''}</span>
          </div>
        </div>
        Time: <span style="display:inline-block; border-bottom:1px solid #000; width:120px; height:18px; vertical-align:middle;">${jobTime && jobTime.end_time ? new Date(jobTime.end_time).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : ''}</span>
      </td>
    </tr>
  </table>
</body>
</html>
      `;
      printWindow.document.write(printContent);
      printWindow.document.close();
      printWindow.onload = function() {
        printWindow.print();
        printWindow.onafterprint = function() {
          printWindow.close();
        };
      };
    }
  </script>
</body>
</html>
