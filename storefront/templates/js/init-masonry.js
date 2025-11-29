// initMasonry layout js version 1.3.4
// Developer: Fariborz jafarzadeh - 09351632292
// All socila id: fariborzj2
// Copyright (c) 2021, All rights reserved.

function initMasonry(gridSelector, itemSelector, responsiveOptions = {}) {
    var grid = document.querySelector(gridSelector);
    var items = grid.querySelectorAll(itemSelector);

    function updateLayout() {
      var screenWidth = window.innerWidth;
      var options = getOptionsForResolution(screenWidth, responsiveOptions);

      var numColumns = options.numColumns || 3; // Default number of columns is 3
      var gutterSize = options.gutterSize || 10; // Default gutter size is 10 pixels
      var direction = options.dir || 'ltr'; // Default direction is left to right

      var containerWidth = grid.offsetWidth;
      var itemWidth = (containerWidth / numColumns) - (gutterSize * (numColumns - 1) / numColumns);

      var columnHeights = Array(numColumns).fill(0);

      items.forEach(function(item) {
        var minHeight = Math.min.apply(null, columnHeights);
        var minIndex = columnHeights.indexOf(minHeight);

        item.style.width = itemWidth + 'px';
        item.style.position = 'absolute';

        if (direction === 'rtl') {
          item.style.right = (minIndex * (itemWidth + gutterSize)) + 'px';
        } else {
          item.style.left = (minIndex * (itemWidth + gutterSize)) + 'px';
        }
        item.style.top = minHeight + 'px';

        columnHeights[minIndex] += item.offsetHeight + gutterSize;
      });

      var maxHeight = Math.max.apply(null, columnHeights);
      grid.style.height = (maxHeight + 1) + 'px';

    }

    // Function to get options based on screen width
    function getOptionsForResolution(width, responsiveOptions) {
        var defaultOptions = responsiveOptions.default || {};
        var sortedBreakpoints = Object.keys(responsiveOptions)
            .filter(key => key !== 'default')
            .sort((a, b) => parseInt(a) - parseInt(b));

        for (var i = sortedBreakpoints.length - 1; i >= 0; i--) {
            var breakpoint = parseInt(sortedBreakpoints[i]);
            if (width >= breakpoint) {
                // Combine default options with breakpoint-specific options
                return { ...defaultOptions, ...responsiveOptions[breakpoint] };
            }
        }

        return defaultOptions;
    }

    window.addEventListener('load', updateLayout);
    window.addEventListener('resize', updateLayout);
    window.addEventListener('orientationchange', updateLayout);
  }

  initMasonry('.feature-grid', '.gr-purple ', {
    default: { numColumns: 1, gutterSize: 20, dir: 'rtl' },
    640: { numColumns: 2, gutterSize: 20 },
    1024: { numColumns: 3, gutterSize: 20}
  });