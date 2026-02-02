/**
 * Custom Homepage Scripts
 * Make section titles clickable
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Configuration: Section title to URL mapping
        var sectionLinks = {
            'Best Selling': '/shop/', // Link to main shop
            'Trending Outfits': '/shop/', // Link to main shop
            'Visit Us': '/shop/', // Link to main shop - or could be party-dresses category
            'Showcase': '/shop/'
        };
        
        // Find section titles and make them clickable
        $('.vc_row h2, .wpb_wrapper h2').each(function() {
            var $title = $(this);
            var titleText = $title.text().trim();
            
            // Check if this title should be linkable
            for (var section in sectionLinks) {
                if (titleText.indexOf(section) !== -1) {
                    var url = sectionLinks[section];
                    
                    // Wrap title in a link if not already wrapped
                    if (!$title.parent('a').length) {
                        $title.css({
                            'cursor': 'pointer',
                            'display': 'inline-block'
                        });
                        
                        // Add click handler
                        $title.on('click', function() {
                            window.location.href = url;
                        });
                        
                        // Add title attribute for accessibility
                        $title.attr('title', 'Click to view ' + section);
                    }
                    
                    break;
                }
            }
        });
        
    });
    
})(jQuery);