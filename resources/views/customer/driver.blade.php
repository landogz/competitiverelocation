<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>CRService</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
  <!-- Add Stripe.js -->
  <script src="https://js.stripe.com/v3/"></script>
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

    /* Remove scrollbar from SweetAlert2 HTML container */
    .swal2-html-container {
      overflow: hidden !important;
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
      border: 4px solid rgba(103, 80, 164, 0.3);
      border-top: 4px solid #6750A4;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Footer menu active state */
    .footer-menu-item.active,
    .footer-menu-item.active:hover {
      background: #fff;
      color: #1061B1 !important;
      border-radius: 8px 8px 0 0;
      font-weight: bold;
      box-shadow: 0 -2px 8px rgba(0,0,0,0.05);
    }
    .footer-menu-item.active .material-icons {
      color: #FFA71E;
    }
  </style>
  <link href="https://unpkg.com/lightbox2@2.11.3/dist/css/lightbox.min.css" rel="stylesheet">
  <script src="https://unpkg.com/lightbox2@2.11.3/dist/js/lightbox-plus-jquery.min.js"></script>
  <style>
    @media (max-width: 600px) {
      .rates-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      .rates-table {
        min-width: 400px;
        font-size: 0.95rem;
      }
      .rates-table th, .rates-table td {
        padding: 0.5rem 0.75rem;
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
        <img src="assets/images/competitive.png" alt="" class="h-12">
    </div>
    <div class="flex items-center gap-4">
        <div id="moving-top" class="text-sm text-right">
          <p>MOVING: <span class="font-medium">#001 | Local - Residential</span></p>
          <p>MOVING LOAD: <span class="font-medium">#001</span></p>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
          <button type="submit" class="bg-error text-error-on px-4 py-2 rounded-full text-sm font-medium state-layer transition-colors duration-200">
          Logout
        </button>
      </form>
      </div>
    </div>
  </header>
  
  <!-- Main Content -->
  <main class="max-w-7xl mx-auto p-4">
    <!-- Transaction/Job List Table -->
    <div id="job-list-section" class="mb-8">
      <h2 class="text-2xl font-medium mb-4">Load Board <span class="font-normal">View Jobs</span></h2>
      <div class="bg-surface rounded-lg elevation-1 overflow-hidden">
        @if($transactions->isEmpty())
          <div class="p-8 text-center">
            <div class="text-surface-onVariant text-lg mb-2">No Loadboard Recorded</div>
            <p class="text-surface-onVariant/70">There are currently no in-progress jobs assigned to you.</p>
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-surface-variant">
                <tr>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Transaction ID</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Customer Name</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Lead Type</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Service</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Date</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Pickup</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Delivery</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Miles</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Status</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-surface-onVariant">Action</th>
            </tr>
          </thead>
              <tbody class="divide-y divide-surface-variant">
                @foreach($transactions as $transaction)
                  <tr class="hover:bg-surface-variant/50 transition-colors">
                    <td class="py-3 px-4 text-sm">{{ $transaction->transaction_id }}</td>
                    <td class="py-3 px-4 text-sm">{{ $transaction->firstname }} {{ $transaction->lastname }}</td>
                    <td class="py-3 px-4 text-sm">{{ $transaction->lead_type }}</td>
                    <td class="py-3 px-4 text-sm">
                        @php
                            $services = $transaction->services;
                        @endphp
                        {{ $services[0]['name'] ?? 'N/A' }}
                    </td>
                    <td class="py-3 px-4 text-sm">
                        @php
                            try {
                                echo $transaction->date ? date('F d, Y', strtotime($transaction->date)) : 'N/A';
                            } catch (\Exception $e) {
                                echo 'N/A';
                            }
                        @endphp
                    </td>
                    <td class="py-3 px-4 text-sm">{{ $transaction->pickup_location }}</td>
                    <td class="py-3 px-4 text-sm">{{ $transaction->delivery_location }}</td>
                    <td class="py-3 px-4 text-sm">{{ $transaction->miles }}</td>
                    <td class="py-3 px-4 text-sm">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary text-primary-on">
                        {{ ucfirst($transaction->status) }}
                      </span>
                    </td>
                    <td class="py-3 px-4 text-sm">
                      <button class="view-job-btn bg-primary text-primary-on px-4 py-2 rounded-full text-sm font-medium state-layer" data-transaction-id="{{ $transaction->transaction_id }}">
                        View Job
                      </button>
                    </td>
            </tr>
                @endforeach
          </tbody>
        </table>
      </div>
        @endif
    </div>
    </div>

    <!-- Menu Section -->
    <div id="menu-section" style="display:none;">
  <!-- Footer Navigation -->
      <footer class="fixed bottom-0 left-0 right-0 bg-primary elevation-3">
        <div class="max-w-7xl mx-auto">
          <div class="flex justify-around items-center py-2">
            <a href="#view-jobs" class="footer-menu-item flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" id="menu-view-jobs" onclick="showJobList(event)">
              <span class="material-icons text-2xl">list_alt</span>
              <span class="text-xs mt-1 hidden sm:block">VIEW JOBS</span>
            </a>
            <a href="#moving-details" class="footer-menu-item flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" id="menu-moving-details" onclick="navigate('moving-details')">
              <span class="material-icons text-2xl">description</span>
              <span class="text-xs mt-1 hidden sm:block">MOVING DETAILS</span>
            </a>
            <a href="#inventory" class="footer-menu-item flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" id="menu-inventory" onclick="navigate('inventory')">
              <span class="material-icons text-2xl">inventory_2</span>
              <span class="text-xs mt-1 hidden sm:block">INVENTORY</span>
            </a>
            <a href="#documents" class="footer-menu-item flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" id="menu-documents" onclick="navigate('documents')">
              <span class="material-icons text-2xl">folder</span>
              <span class="text-xs mt-1 hidden sm:block">DOCUMENTS</span>
            </a>
            <a href="#services" class="footer-menu-item flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" id="menu-services" onclick="navigate('services')">
              <span class="material-icons text-2xl">build</span>
              <span class="text-xs mt-1 hidden sm:block">SERVICES</span>
            </a>
            <a href="#payments" class="footer-menu-item flex flex-col items-center text-primary-on hover:text-secondary state-layer p-2" id="menu-payments" onclick="navigate('payments')">
              <span class="material-icons text-2xl">payments</span>
              <span class="text-xs mt-1 hidden sm:block">PAYMENTS</span>
            </a>
          </div>
        </div>
      </footer>
      <div id="menu-content" class="mb-20"></div>
    </div>
  </main>

  <script>
    // Preloader functionality
    document.addEventListener("DOMContentLoaded", () => {
      const preloader = document.getElementById("preloader");
      const footer = document.querySelector("footer");    
      const movingtop = document.getElementById("moving-top");
      const main = document.querySelector("main");
      
      // Hide moving-top initially
      if (movingtop) {
        movingtop.style.display = 'none';
      }
      
      setTimeout(() => {
        preloader.classList.add("hide"); // Hide preloader after 2 seconds (or when content is ready)
        // Remove default navigation to moving-details
      }, 2000); // Adjust time as needed for your content

      // Hide menus on first load
      document.getElementById('menu-section').style.display = 'none';
      document.getElementById('job-list-section').style.display = '';

      // Add event listeners to all view-job buttons (prevent double requests)
      document.querySelectorAll('.view-job-btn').forEach(btn => {
        btn.onclick = function() {
          currentTransactionId = this.dataset.transactionId;
          checkJobTime(currentTransactionId);
        };
      });
    });

    // Modify the showJobList function to handle optional event parameter
    function showJobList(e) {
        if (e && e.preventDefault) {
      e.preventDefault();
        }
      document.getElementById('menu-section').style.display = 'none';
      document.getElementById('job-list-section').style.display = '';
      // Hide moving-top when returning to job list
      const movingtop = document.getElementById("moving-top");
      if (movingtop) {
        movingtop.style.display = 'none';
      }
      // Remove Customer Details section if present
      const customerDetails = document.getElementById('customer-details-section');
      if (customerDetails) customerDetails.remove();
      // Clear menu content area if present
      const menuContent = document.getElementById('menu-content');
      if (menuContent) menuContent.innerHTML = '';
      setActiveFooterMenu('menu-view-jobs');
    }

    const pages = {
      'moving-details': `
        <div class="max-w-4xl mx-auto">
          <!-- Customer Details -->
          <div class="mb-8">
            <h2 class="text-2xl font-medium mb-4">Customer Details</h2>
            <div class="grid grid-cols-1 gap-4">
              <div class="bg-surface-variant rounded-lg p-4 elevation-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div class="text-surface-onVariant font-medium">Full Name:</div>
                  <div id="customer-fullname"></div>
                  <div class="text-surface-onVariant font-medium">Email:</div>
                  <div id="customer-email" class="break-all"></div>
                  <div class="text-surface-onVariant font-medium">Move Date:</div>
                  <div id="customer-movedate"></div>
                  <div class="text-surface-onVariant font-medium">Phone:</div>
                  <div id="customer-phone"></div>
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
                <div id="pickup-address"></div>
              </div>
            </div>

            <!-- Move To -->
            <div class="bg-surface-variant rounded-lg p-4 elevation-1">
              <h2 class="text-xl font-medium mb-4">Move To</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="text-surface-onVariant font-medium">Pick-up Address:</div>
                <div id="delivery-address"></div>
              </div>
            </div>
          </div>

          <!-- Comments -->
          <div>
            <h2 class="text-2xl font-medium mb-4">Comments</h2>
            <div class="bg-surface-variant rounded-lg p-4 min-h-[100px] text-surface-onVariant elevation-1" id="customer-comments">
              No Comments
            </div>
          </div>
        </div>
      `,
      'inventory': `
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
          <!-- Inventory Items -->
          <div class="mb-8">
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
      'documents': `
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
              <button id="complete-btn" onclick="handleComplete()" class="bg-primary text-primary-on py-2 px-8 rounded-lg hover:bg-primary/90 transition-colors mb-2 md:mb-0">Save</button>
             
            </div>
          </div>
        </div>
      `,
      'services': `
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
          <div class="flex justify-between items-center mb-6">
            <span class="font-semibold text-gray-700">SERVICES / ADD TOTAL HOURS</span>
            <span class="font-bold text-lg">Total Hours: <span id="total-hours">12:50</span></span>
          </div>

          <!-- Start Time Section -->
          <div class="mb-8">
            <label class="block text-gray-600 font-medium mb-2">START TIME</label>
            <input type="text" id="start-time" class="w-full p-3 bg-gray-100 border border-gray-300 rounded mb-4" placeholder="Start time after the driver has signed" readonly>
            <div class="text-center text-gray-500 mb-2">Driver Signature</div>
            <div class="flex flex-col items-center mb-2">
              <img id="start-signature-img" src="" alt="Driver Signature" style="max-width:200px; max-height:80px; display:none;"/>
              </div>
            <div class="flex justify-center">
              <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-medium" id="driver-signature-btn" style="display:none;">
                Driver's Signature To Start Delivery Time
                      </button>
                  </div>
                </div>

          <!-- Finish Time Section -->
          <div class="mb-8">
            <label class="block text-gray-600 font-medium mb-2">FINISH TIME</label>
            <input type="text" id="finish-time" class="w-full p-3 bg-gray-100 border border-gray-300 rounded mb-4" placeholder="Set finish time after the receiver has done signing" readonly>
            <div class="text-center text-gray-500 mb-2">Receiver Signature</div>
            <div class="flex flex-col items-center mb-2">
              <img id="end-signature-img" src="" alt="Receiver Signature" style="max-width:200px; max-height:80px; display:none;"/>
            </div>
            <div class="flex justify-center">
              <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-medium" id="receiver-signature-btn">
                Receiver's Signature To Finish Delivery Time
            </button>
            </div>
          </div>
        </div>
      `,
      'payments': `
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

            <!-- Thank You Card (Hidden by default) -->
            <div id="thank-you-card" class="bg-surface rounded-lg elevation-1 overflow-hidden hidden">
              <div class="bg-surface-variant px-6 py-4">
                <h3 class="text-lg font-medium">Payment Complete</h3>
              </div>
              <div class="p-6 text-center">
                <div class="mb-6">
                  <span class="material-icons text-6xl text-primary mb-4">check_circle</span>
                  <h4 class="text-xl font-medium mb-2">Thank You!</h4>
                  <p class="text-surface-onVariant mb-4">We appreciate your business and trust in our services.</p>
                </div>
                <div class="bg-surface-variant/50 rounded-lg p-4 mb-6">
                  <p class="text-sm text-surface-onVariant mb-2">Transaction Details:</p>
                  <p class="font-medium" id="thank-you-transaction-id"></p>
                </div>
                <p class="text-sm text-surface-onVariant">If you have any questions, please don't hesitate to contact us.</p>
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
      `,
    };

    // Function to navigate between menu options
    function navigate(page) {
      const mainContent = document.getElementById("menu-content");
      mainContent.innerHTML = pages[page] || "<p>Page not found.</p>";
      
      // If navigating to moving-details, populate the data
      if (page === 'moving-details' && window.currentTransaction) {
        populateTransactionDetails(window.currentTransaction);
      }
      if (page === 'services') {
        setupServiceSignatureButtons();
        if (window.currentJobTime) {
          populateJobTimeFields(window.currentJobTime);
        }
      }
      if (page === 'inventory' && currentTransactionId) {
        loadInventoryItems(currentTransactionId);
        renderUploadedImagesGallery(window.currentTransaction);
      }
      if (page === 'documents' && window.currentTransaction) {
        populateDocuments(window.currentTransaction);
        populateRates(window.currentTransaction);
        // Always fetch inventory for documents page to ensure up-to-date cubic ft
        const transactionId = window.currentTransaction.transaction_id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/driver/inventory/${transactionId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          }
        })
        .then(response => response.json())
        .then(data => {
          window.currentInventory = data.inventory || [];
          populateTripDetails(window.currentTransaction, window.currentInventory);
          renderHighValueInventory(window.currentInventory);
        })
        .catch(() => {
          populateTripDetails(window.currentTransaction, []);
        });
        // Fetch job time
        fetch(`/driver/job-time/${transactionId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.job_time) {
            populateJobTime(data.job_time);
          } else {
            populateJobTime(null);
          }
        })
        .catch(() => {
          populateJobTime(null);
        });

        // Initialize credit card authorization form after the documents page is loaded
        setTimeout(() => {
          initializeCreditCardAuthorizationForm();
        }, 100);
      }
      setActiveFooterMenu('menu-' + page.replace('_', '-'));
    }

    // Add this new function to initialize the credit card authorization form
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

      // Fetch existing credit card authorization for this transaction
      const transactionId = window.currentTransaction?.id;
      if (transactionId) {
        fetch(`/credit-card-authorization/${transactionId}`)
          .then(res => res.json())
          .then(result => {
            if (result.success && result.data) {
              showEditCard(result.data);
              populateShipperSignatureFromCCAuth(result.data);
            } else {
              showForm();
              populateShipperSignatureFromCCAuth({});
            }
          })
          .catch(() => {
            showForm();
            populateShipperSignatureFromCCAuth({});
          });
      } else {
        showForm();
        populateShipperSignatureFromCCAuth({});
      }

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
          transaction_id: window.currentTransaction?.id,
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
                // Navigate to services page
                navigate('services');
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

    function populateTransactionDetails(transaction) {
      // Customer Details
      document.getElementById('customer-fullname').textContent = `${transaction.firstname} ${transaction.lastname}`;
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
      document.getElementById('customer-comments').textContent = transaction.comments || 'No Comments';
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

    // Add this after the existing script code
    let currentTransactionId = null;

    function checkJobTime(transactionId) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/driver/job-time/${transactionId}`, {
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
            // Check if job is already completed
            if (data.job_time && data.job_time.start_time && data.job_time.end_time) {
                Swal.fire({
                    title: 'Job Already Completed',
                    text: 'This job has already been completed. You cannot start it again.',
                    icon: 'warning'
                });
                return;
            }

            // Always update moving-top with transaction details
            const movingtop = document.getElementById("moving-top");
            if (movingtop && data.transaction) {
                movingtop.style.display = 'block';
                const leadType = data.transaction.lead_type ? data.transaction.lead_type.replace(/_/g, ' ').toLowerCase() : 'N/A';
                movingtop.innerHTML = `
                    <p>MOVING: <span class="font-medium">#${data.transaction.transaction_id} | ${leadType}</span></p>
                    <p>MOVING LOAD: <span class="font-medium">#${data.transaction.transaction_id}</span></p>
                `;
            }

            // Store transaction data for later use
            window.currentTransaction = data.transaction;
            window.currentJobTime = data.job_time;

            if (data.job_time && data.job_time.start_time && !data.job_time.end_time) {
                document.getElementById('job-list-section').style.display = 'none';
                document.getElementById('menu-section').style.display = '';
                navigate('moving-details');
            } else {
                openStartJobSignatureModal();
            }
        })
        .catch(error => {
            console.error('Error checking job time:', error);
            Swal.fire('Error', 'Failed to check job time. Please try again.', 'error');
        });
    }

    function openStartJobSignatureModal() {
        Swal.fire({
            title: 'Customer Signature Required',
            html: `
                <p class="mb-4">Please have the customer sign to start the job.</p>
                <div class="border border-gray-300 rounded-lg">
                    <canvas id="start-signature-pad" width="500" height="200" style="touch-action: none;"></canvas>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Start Job',
            cancelButtonText: 'Cancel',
            showDenyButton: true,
            denyButtonText: 'Clear',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                const canvas = document.getElementById('start-signature-pad');
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
                // Start job time with signature
                startJobTime(result.value);
            } else {
                // If user clicks cancel or closes the modal, return to job list
                showJobList();
            }
        });
    }

    function startJobTime(signature) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/driver/job-time/${currentTransactionId}/start`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ signature })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Server error:', data.error);
                Swal.fire('Error', data.error, 'error').then(() => {
                    showJobList();
                });
            } else {
                // Show success message and proceed to job details
                Swal.fire({
                    title: 'Success',
                    text: 'Job time started successfully',
                    icon: 'success'
                }).then(() => {
                    // Hide job list and show menu section
                    document.getElementById('job-list-section').style.display = 'none';
                    document.getElementById('menu-section').style.display = '';
                    
                    // Get transaction details before showing moving details
                    fetch(`/driver/transaction/${currentTransactionId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(transaction => {
                            // Now navigate to moving details
                            navigate('moving-details');
                        })
                        .catch(error => {
                            console.error('Error fetching transaction:', error);
                            Swal.fire('Error', 'Failed to load transaction details', 'error').then(() => {
                                showJobList();
                            });
                        });
                });
            }
        })
        .catch(error => {
            console.error('Error starting job time:', error);
            Swal.fire('Error', 'Failed to start job time. Please try again.', 'error').then(() => {
                showJobList();
            });
        });
    }

    // Wire up the signature buttons to use the existing modal signature logic
    function setupServiceSignatureButtons() {
      const driverBtn = document.getElementById('driver-signature-btn');
      if (driverBtn) {
        driverBtn.onclick = function() {
          openStartJobSignatureModal(); // Use your existing modal logic for driver signature
        };
      }
      const receiverBtn = document.getElementById('receiver-signature-btn');
      if (receiverBtn) {
        receiverBtn.onclick = function() {
          openEndJobSignatureModal(); // You should have a similar modal logic for ending job/receiver signature
        };
      }
    }

    // Add a function to populate the job time fields
    function populateJobTimeFields(jobTime) {
      // Set start time
      const startTimeInput = document.getElementById('start-time');
      if (startTimeInput) {
        startTimeInput.value = jobTime && jobTime.start_time
          ? new Date(jobTime.start_time).toLocaleString()
          : '';
      }
      // Set start signature image
      const startSignatureImg = document.getElementById('start-signature-img');
      if (startSignatureImg) {
        if (jobTime && jobTime.start_signature) {
          startSignatureImg.src = jobTime.start_signature;
          startSignatureImg.style.display = 'block';
        } else {
          startSignatureImg.style.display = 'none';
        }
      }
      // Set finish time and signature
      populateFinishTimeFields(jobTime);
      // Update total hours
      updateTotalHours();
    }

    function openEndJobSignatureModal() {
      Swal.fire({
        title: 'Receiver Signature Required',
        html: `
          <p class="mb-4">Please have the receiver sign to finish the job.</p>
          <div class="border border-gray-300 rounded-lg">
            <canvas id="end-signature-pad" width="500" height="200" style="touch-action: none;"></canvas>
          </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Finish Job',
        cancelButtonText: 'Cancel',
        showDenyButton: true,
        denyButtonText: 'Clear',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          const canvas = document.getElementById('end-signature-pad');
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
          saveEndJobTime(result.value);
        }
      });
    }

    function saveEndJobTime(signature) {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch(`/driver/job-time/${currentTransactionId}/end`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ signature })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.error) {
          Swal.fire('Error', data.error, 'error');
        } else {
          // Update finish time and signature in the UI
          if (data.job_time) {
            populateFinishTimeFields(data.job_time);
            window.currentJobTime = data.job_time;
            updateTotalHours();
          }
          Swal.fire('Success', 'Job time ended successfully', 'success');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to end job time. Please try again.', 'error');
      });
    }

    function populateFinishTimeFields(jobTime) {
      // Set finish time
      const finishTimeInput = document.getElementById('finish-time');
      if (finishTimeInput) {
        finishTimeInput.value = jobTime && jobTime.end_time
          ? new Date(jobTime.end_time).toLocaleString()
          : '';
      }
      // Set end signature image
      const endSignatureImg = document.getElementById('end-signature-img');
      if (endSignatureImg) {
        if (jobTime && jobTime.end_signature) {
          endSignatureImg.src = jobTime.end_signature;
          endSignatureImg.style.display = 'block';
        } else {
          endSignatureImg.style.display = 'none';
        }
      }
    }

    function updateTotalHours() {
      const jobTime = window.currentJobTime;
      const totalHoursSpan = document.getElementById('total-hours');
      if (jobTime && jobTime.start_time && jobTime.end_time && totalHoursSpan) {
        const start = new Date(jobTime.start_time);
        const end = new Date(jobTime.end_time);
        const diffMs = end - start;
        if (diffMs > 0) {
          const hours = Math.floor(diffMs / 3600000);
          const minutes = Math.floor((diffMs % 3600000) / 60000);
          totalHoursSpan.textContent = `${hours} hour${hours !== 1 ? 's' : ''} ${minutes} minute${minutes !== 1 ? 's' : ''}`;
        } else {
          totalHoursSpan.textContent = '0 hours 0 minutes';
        }
      }
    }

    // Add this after the existing script code
    function loadInventoryItems(transactionId) {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch(`/driver/inventory/${transactionId}`, {
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

    // Helper to format as currency
    function formatCurrency(value) {
      let num = parseFloat(value);
      if (isNaN(num)) num = 0;
      return '$' + num.toFixed(2);
    }

    function populateTripDetails(transaction, inventory) {
      document.getElementById('trip-miles').textContent = transaction.miles ? `${transaction.miles} miles` : 'N/A';
      // Calculate total cubic ft from inventory
      let totalCubicFt = 0;
      if (Array.isArray(inventory)) {
        totalCubicFt = inventory.reduce((sum, item) => sum + ((parseFloat(item.cubic_ft) || 0) * (parseInt(item.quantity) || 1)), 0);
      }
      document.getElementById('trip-cubicft').textContent = totalCubicFt ? totalCubicFt.toFixed(2) : 'N/A';
    }

    function formatJobTime(date) {
      if (!date) return 'N/A';
      const d = new Date(date);
      const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit' };
      let str = d.toLocaleString('en-US', options);
      // Remove seconds if present and format am/pm
      str = str.replace(/:00\s?/, '');
      str = str.replace('AM', 'am').replace('PM', 'pm');
      return str;
    }

    function populateJobTime(job_time) {
      // Format start and end times
      const start = job_time && job_time.start_time ? new Date(job_time.start_time) : null;
      const end = job_time && job_time.end_time ? new Date(job_time.end_time) : null;
      const startElem = document.getElementById('jobtime-start');
      const endElem = document.getElementById('jobtime-end');
      const totalElem = document.getElementById('jobtime-total');
      if (startElem) startElem.textContent = start ? formatJobTime(job_time.start_time) : 'N/A';
      if (endElem) endElem.textContent = end ? formatJobTime(job_time.end_time) : 'N/A';
      // Calculate total hours/minutes
      let total = 'N/A';
      if (start && end) {
        const diffMs = end - start;
        if (diffMs > 0) {
          const hours = Math.floor(diffMs / 3600000);
          const minutes = Math.floor((diffMs % 3600000) / 60000);
          total = `${hours} Hour${hours !== 1 ? 's' : ''} ${minutes} Minute${minutes !== 1 ? 's' : ''}`;
        } else {
          total = '0 Hours 0 Minutes';
        }
      }
      if (totalElem) totalElem.textContent = total;
    }

    // Add this JS function after your other helpers
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

    // Add a JS function to populate the shipper signature section from credit card authorization
    function populateShipperSignatureFromCCAuth(auth) {
      const nameDiv = document.getElementById('shipper-name');
      const sigDiv = document.getElementById('shipper-signature');
      const dateDiv = document.getElementById('shipper-date');
      const commentsTextarea = document.getElementById('shipper-comments');
      
      if (nameDiv) nameDiv.textContent = auth.cardholder_name || auth.full_name || '';
      if (sigDiv) {
        if (auth.signature) {
          sigDiv.innerHTML = `<img src="${auth.signature}" alt="Signature" style="max-height:40px;max-width:150px;display:inline-block;" />`;
        } else {
          sigDiv.textContent = '';
        }
      }
      if (dateDiv) dateDiv.textContent = auth.date || '';
      if (commentsTextarea) commentsTextarea.value = auth.comments || '';
    }

    // Function to render uploaded images gallery below inventory (Lightbox2, no manual init)
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

    // Set active footer menu item
    function setActiveFooterMenu(menuId) {
      document.querySelectorAll('.footer-menu-item').forEach(item => {
        item.classList.remove('active');
      });
      const activeItem = document.getElementById(menuId);
      if (activeItem) activeItem.classList.add('active');
    }

    // Add this function for the Complete button
    function handleComplete() {
      const completeBtn = document.getElementById('complete-btn');
      const comment = document.getElementById('shipper-comments').value;
      const transactionId = window.currentTransaction?.id;
      
      if (!transactionId) {
        Swal.fire('Error', 'No transaction selected.', 'error');
        return;
      }
      
      // Show loading state
      completeBtn.disabled = true;
      completeBtn.innerHTML = '<span class="animate-spin mr-2">⟳</span> Saving...';

      console.log('Starting comment update process...');
      console.log('Comment:', comment);
      console.log('Transaction ID:', transactionId);

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
              comments: comment,
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
        .then(updateResult => {
          if (updateResult.success) {
            Swal.fire({
              title: 'Success!',
              text: 'Comment saved successfully',
              icon: 'success'
            }).then(() => {
              // Navigate to services page
              navigate('services');
            });
          } else {
            throw new Error(updateResult.message || 'Failed to update comment');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Error', error.message || 'An error occurred while saving the comment.', 'error');
        })
        .finally(() => {
          // Reset button state
          completeBtn.disabled = false;
          completeBtn.innerHTML = 'Complete';
        });
    }

    // Add this after the existing script code
    let stripe, elements, paymentElement;

    async function initializeStripePayment() {
      try {
        // Calculate remaining balance
        const response = await fetch(`/customer/payments/${window.currentTransaction.id}`);
        const data = await response.json();
        
        let remainingBalance = 0;
        let totalPaid = 0;
        let grandTotal = 0;

        if (data.success) {
          const payments = data.payments || [];
          totalPaid = payments.reduce((sum, payment) => {
            if (payment.status === 'Succeeded') {
              return sum + parseFloat(payment.amount);
            }
            return sum;
          }, 0);
          
          grandTotal = parseFloat(window.currentTransaction.grand_total || 0);
          remainingBalance = Math.max(grandTotal - totalPaid, 0);

          // Update payment activity display
          const tbody = document.querySelector('#payments-activity-table');
          if (tbody) {
            tbody.innerHTML = '';
            if (!data.payments || data.payments.length === 0) {
              tbody.innerHTML = `
                <tr class="hover:bg-surface-variant/50 transition-colors">
                  <td colspan="4" class="py-8 px-4 text-center text-surface-onVariant">
                    <div class="text-3xl mb-2">📝</div>
                    <div class="font-medium">No Payment History</div>
                    <div class="text-sm mt-1">No payments have been made yet.</div>
                  </td>
                </tr>
              `;
            } else {
              data.payments.forEach(payment => {
                let statusClass = '';
                let statusText = payment.status;
                if (payment.status === 'Succeeded') {
                  statusClass = 'bg-green-600 text-white';
                  statusText = 'PAID';
                } else {
                  statusClass = 'bg-yellow-100 text-yellow-800';
                }
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
            }
          }

          // Update totals display
          const totalsDiv = document.getElementById('payments-totals');
          if (totalsDiv) {
            totalsDiv.innerHTML = `
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
                <span class="font-medium ${remainingBalance > 0 ? 'text-error' : 'text-green-600'}">$${remainingBalance.toFixed(2)}</span>
              </div>
            `;
          }
        }

        // Show/hide payment form or thank you card based on remaining balance
        const paymentForm = document.getElementById('stripe-payment-form').closest('.bg-surface');
        const thankYouCard = document.getElementById('thank-you-card');
        
        if (remainingBalance === 0) {
          paymentForm.classList.add('hidden');
          thankYouCard.classList.remove('hidden');
          document.getElementById('thank-you-transaction-id').textContent = `Transaction #${window.currentTransaction.transaction_id}`;
          return;
        } else {
          paymentForm.classList.remove('hidden');
          thankYouCard.classList.add('hidden');
        }

        // Validate remaining balance
        if (remainingBalance <= 0) {
          showStripeMessage('No remaining balance to pay.', 'error');
          return;
        }

        // Fetch client secret and publishable key from backend
        const paymentResponse = await fetch('/payment/process', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            transaction_id: window.currentTransaction.id,
            amount: remainingBalance.toFixed(2),
            payment_type: 'remaining_balance',
            is_remaining_balance: true, // Add this flag to explicitly indicate this is a remaining balance payment
            payment_method_id: 'pm_card_visa' // Remove for live, keep for test
          })
        });

        const paymentData = await paymentResponse.json();
        
        if (!paymentData.success) {
          // Show error message and navigate to services page
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: paymentData.message || 'Failed to process payment',
            confirmButtonColor: '#dc3545'
          });
          navigate('services');
          return;
        }

        // Log the payment data for debugging
        console.log('Payment Process Response:', {
          amount: remainingBalance.toFixed(2),
          payment_type: 'remaining_balance',
          is_remaining_balance: true,
          transaction_id: window.currentTransaction.id
        });

        // Use the publishable key from backend response
        const publishableKey = paymentData.stripeKey;
        if (!publishableKey) {
          showStripeMessage('Stripe publishable key not found.', 'error');
          return;
        }

        // Initialize Stripe
        stripe = Stripe(publishableKey);
        
        // Create Elements instance
        elements = stripe.elements({
          clientSecret: paymentData.clientSecret,
          appearance: {
            theme: 'stripe',
            variables: {
              colorPrimary: '#1061B1',
              colorBackground: '#ffffff',
              colorText: '#1C1B1F',
              colorDanger: '#B3261E',
              fontFamily: 'Roboto, system-ui, sans-serif',
              spacingUnit: '4px',
              borderRadius: '8px'
            }
          }
        });

        // Create and mount the Payment Element
        paymentElement = elements.create('payment');
        paymentElement.mount('#stripe-payment-element');

        // Update button text to show remaining balance
        const submitButton = document.getElementById('stripe-submit-button');
        const buttonText = document.getElementById('stripe-button-text');
        if (submitButton && buttonText) {
          submitButton.disabled = false;
          buttonText.textContent = `Pay Remaining Balance ($${remainingBalance.toFixed(2)})`;
        }

      } catch (error) {
        console.error('Error initializing Stripe:', error);
        showStripeMessage('Failed to initialize payment system. Please try again.', 'error');
      }
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
      
      const submitButton = document.getElementById('stripe-submit-button');
      const buttonText = document.getElementById('stripe-button-text');
      const spinner = document.getElementById('stripe-spinner');
      const paymentForm = document.getElementById('stripe-payment-form');
      
      // Prevent double submission
      if (submitButton.disabled) {
        return;
      }
      
      try {
        // Disable the button and show loading state
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');
        paymentForm.style.pointerEvents = 'none';

        // Confirm the payment
        const { error, paymentIntent } = await stripe.confirmPayment({
          elements,
          confirmParams: {
            return_url: `${window.location.origin}/payment/success`,
          },
          redirect: 'if_required'
        });

        if (error) {
          showStripeMessage(error.message, 'error');
          return;
        }

        if (paymentIntent) {
          // Confirm on server
          const res = await fetch('/payment/confirm', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
              transaction_id: window.currentTransaction.id,
              payment_intent_id: paymentIntent.id,
              payment_type: 'remaining_balance' // Add payment type to distinguish from downpayment
            })
          });

          const result = await res.json();
          
          if (result.success) {
            showStripeMessage('Payment successful!', 'success');
            // Reload payment activity
            await loadPaymentsActivity(window.currentTransaction.id);
            // Short delay before refreshing
            setTimeout(() => navigate('payments'), 500);
          } else {
            showStripeMessage(result.message, 'error');
          }
        }
      } catch (err) {
        console.error('Payment error:', err);
        showStripeMessage(err.message || 'An error occurred during payment. Please try again.', 'error');
      } finally {
        // Reset button state
        submitButton.disabled = false;
        buttonText.classList.remove('hidden');
        spinner.classList.add('hidden');
        paymentForm.style.pointerEvents = 'auto';
      }
    }

    // Add spinner styles
    const style = document.createElement('style');
    style.textContent = `
      .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid #ffffff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);

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
      const t = window.currentTransaction || {};
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

    // Extend navigate to initialize Stripe on payments tab
    const originalNavigate = navigate;
    navigate = function(page) {
      originalNavigate(page);
      if (page === 'payments') {
        setTimeout(() => {
          initializeStripePayment();
          document.getElementById('stripe-payment-form').addEventListener('submit', handleStripeSubmit);
          if (window.currentTransaction && window.currentTransaction.id) {
            loadPaymentsActivity(window.currentTransaction.id);
          }
        }, 200);
      }
    };
  </script>
</body>
</html>
