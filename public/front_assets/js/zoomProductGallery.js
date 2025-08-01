document.addEventListener('DOMContentLoaded', function () {
    const images = document.querySelectorAll('.product-details-image img, .thumb-item img, .zoom-gallery img');
    images.forEach(img => {
      img.addEventListener('error', function() {
        this.src = '{{ asset("front_assets/images/no-image.svg") }}';
        this.classList.add('error');
      });
      
      img.addEventListener('load', function() {
        this.classList.remove('error');
      });
    });

    const mainImage = document.querySelector('.main-image-container .main-image');
    const thumbItems = document.querySelectorAll('.thumb-item');
    const zoomImages = document.querySelectorAll('.zoom-image');
    
    thumbItems.forEach(item => {
      item.addEventListener('click', function() {
        const imageSrc = this.getAttribute('data-src');
        const imageIndex = this.getAttribute('data-index');
        
        if (mainImage) {
          mainImage.src = imageSrc;
          mainImage.setAttribute('data-index', imageIndex);
        }
        
        thumbItems.forEach(thumb => thumb.classList.remove('active'));
        this.classList.add('active');
        
        currentZoomIndex = parseInt(imageIndex);
      });
    });

    const zoomOverlay = document.getElementById('zoomOverlay');
    const zoomImage = document.getElementById('zoomImage');
    const zoomClose = document.getElementById('zoomClose');
    const zoomPrev = document.getElementById('zoomPrev');
    const zoomNext = document.getElementById('zoomNext');
    const zoomCounter = document.getElementById('zoomCounter');
    const zoomLoading = document.getElementById('zoomLoading');
    
    let currentZoomIndex = 0;
    let totalImages = thumbItems.length;
    let startX = 0;
    let startY = 0;

    function preloadImages() {
      thumbItems.forEach(item => {
        const imgSrc = item.getAttribute('data-src');
        const preloadImg = new Image();
        preloadImg.src = imgSrc;
      });
    }

    function handleTouchStart(e) {
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
    }

    function handleTouchEnd(e) {
      if (!startX || !startY) return;

      const endX = e.changedTouches[0].clientX;
      const endY = e.changedTouches[0].clientY;
      const diffX = startX - endX;
      const diffY = startY - endY;

      if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
        if (diffX > 0) {
          navigateZoom(1); 
        } else {
          navigateZoom(-1); 
        }
      }

      startX = 0;
      startY = 0;
    }

    zoomOverlay.addEventListener('touchstart', handleTouchStart, { passive: true });
    zoomOverlay.addEventListener('touchend', handleTouchEnd, { passive: true });

    if (mainImage) {
      mainImage.addEventListener('click', function() {
        currentZoomIndex = parseInt(this.getAttribute('data-index') || 0);
        openZoomOverlay();
      });
    }

    zoomClose.addEventListener('click', closeZoomOverlay);
    zoomOverlay.addEventListener('click', function(e) {
      if (e.target === zoomOverlay) {
        closeZoomOverlay();
      }
    });

    zoomPrev.addEventListener('click', function(e) {
      e.stopPropagation();
      navigateZoom(-1);
    });

    zoomNext.addEventListener('click', function(e) {
      e.stopPropagation();
      navigateZoom(1);
    });

    document.addEventListener('keydown', function(e) {
      if (zoomOverlay.style.display === 'flex') {
        switch(e.key) {
          case 'Escape':
            closeZoomOverlay();
            break;
          case 'ArrowLeft':
            navigateZoom(-1);
            break;
          case 'ArrowRight':
            navigateZoom(1);
            break;
        }
      }
    });

    function openZoomOverlay() {
      if (totalImages === 0) return;
      
      zoomOverlay.style.display = 'flex';
      updateZoomImage();
      updateZoomCounter();
      document.body.style.overflow = 'hidden';
      
      zoomOverlay.style.opacity = '0';
      setTimeout(() => {
        zoomOverlay.style.opacity = '1';
      }, 10);
    }

    function closeZoomOverlay() {
      zoomOverlay.style.opacity = '0';
      setTimeout(() => {
        zoomOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
      }, 300);
    }

    function navigateZoom(direction) {
      currentZoomIndex += direction;
      
      if (currentZoomIndex < 0) {
        currentZoomIndex = totalImages - 1;
      } else if (currentZoomIndex >= totalImages) {
        currentZoomIndex = 0;
      }
      
      const targetThumb = document.querySelector(`[data-index="${currentZoomIndex}"]`);
      if (targetThumb) {
        const imageSrc = targetThumb.getAttribute('data-src');
        
        if (mainImage) {
          mainImage.src = imageSrc;
          mainImage.setAttribute('data-index', currentZoomIndex);
        }
        
        thumbItems.forEach(thumb => thumb.classList.remove('active'));
        targetThumb.classList.add('active');
      }
      
      updateZoomImage();
      updateZoomCounter();
    }

    function updateZoomImage() {
      const currentThumb = document.querySelector(`[data-index="${currentZoomIndex}"]`);
      if (currentThumb) {
        const imageSrc = currentThumb.getAttribute('data-src');

        zoomLoading.style.display = 'block';
        zoomImage.style.opacity = '0';
        
        const tempImg = new Image();
        tempImg.onload = function() {
          zoomImage.src = imageSrc;
          zoomImage.alt = currentThumb.querySelector('img').alt;
          zoomImage.style.opacity = '1';
          zoomLoading.style.display = 'none';
        };
        tempImg.onerror = function() {
          zoomImage.src = '{{ asset("front_assets/images/no-image.svg") }}';
          zoomImage.alt = 'Image not available';
          zoomImage.style.opacity = '1';
          zoomLoading.style.display = 'none';
        };
        tempImg.src = imageSrc;
      }
    }

    function updateZoomCounter() {
      zoomCounter.textContent = `${currentZoomIndex + 1} / ${totalImages}`;
      
      zoomPrev.style.display = totalImages > 1 ? 'block' : 'none';
      zoomNext.style.display = totalImages > 1 ? 'block' : 'none';
      zoomCounter.style.display = totalImages > 1 ? 'block' : 'none';
    }

    preloadImages();

  });