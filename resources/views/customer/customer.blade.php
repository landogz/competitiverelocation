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
        <div class="">
          <h2>CUSTOMER INFO</h2>
          <p>Manage customer information here.</p>
        </div>
      `,
      'estimates': `
        <div class="">
          <h2>ESTIMATES</h2>
          <p>View and manage estimates here.</p>
        </div>
      `,
      'services': `
        <div class="">
          <h2>SERVICES</h2>
          <p>Manage available services here.</p>
        </div>
      `,
      'payments': `
        <div class="">
          <h2>PAYMENTS</h2>
          <p>Track payments and transactions here.</p>
        </div>
      `,
      'support': `
      <div class="border border-gray-300 p-6 rounded-lg shadow-md">
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
