// Bootstrap JavaScript (v5.0.0)
// (c) 2021 OpenJS Foundation and contributors
// Released under the MIT license

var Tooltip = (function () {
    // Tooltip constructor
    function Tooltip(element) {
      // Your tooltip logic here
    }
  
    // Add methods to Tooltip prototype
    Tooltip.prototype.method1 = function () {
      // Method 1 logic
    };
  
    Tooltip.prototype.method2 = function () {
      // Method 2 logic
    };
  
    // Return the Tooltip constructor
    return Tooltip;
  })();
  
  // Add other Bootstrap components and functionalities here
  
  // Initialize your Bootstrap components
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new Tooltip(tooltipTriggerEl);
    });
  
    // Add other component initialization code here
  });
  