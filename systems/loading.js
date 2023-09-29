document.addEventListener('DOMContentLoaded', function() {
    var loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.style.display = 'none';
  });
  
  window.addEventListener('beforeunload', function() {
    var loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.style.display = 'flex';
  });
  