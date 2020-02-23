var meta_gallery_frame;
jQuery(document).ready(function($) {
    // Runs when the image button is clicked.
    jQuery("#sortable").sortable({
        update: function(event, ui) {
            var metadataString = [];

            var metadataString = [];
            jQuery("#sortable li").each(function(el, el2) {
                var imageId = jQuery(el2).find("img").attr("id");
                metadataString.push(imageId);
            })
            jQuery("#portfolio_gallery_value").val(metadataString.toString());

        }
    });
    jQuery("#portfolio_gallery_button").click(function(e) {
        //Attachment.sizes.thumbnail.url/ Prevents the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if (meta_gallery_frame) {
            meta_gallery_frame.open();
            return;
        }

        // Sets up the media library frame
        meta_gallery_frame = wp.media.frames.meta_gallery_frame = wp.media({
            title: "Select a gallery",
            // button: { text: 'click' },
            // library: { type: "image" },
            multiple: true
        });

        // Create Featured Gallery state. This is essentially the Gallery state, but selection behavior is altered.
        meta_gallery_frame.states.add([
            new wp.media.controller.Library({
                id: "portfolio-gallery",
                title: "Select Images for Gallery",
                priority: 20,
                toolbar: "main-gallery",
                filterable: "uploaded",
                library: wp.media.query(meta_gallery_frame.options.library),
                multiple: meta_gallery_frame.options.multiple ? "reset" : false,
                editable: true,
                allowLocalEdits: true,
                displaySettings: true,
                displayUserSettings: true
            })
        ]);

        meta_gallery_frame.on("open", function() {
            var selection = meta_gallery_frame.state().get("selection");
            var library = meta_gallery_frame.state("gallery-edit").get("library");
            var ids = jQuery("#portfolio_gallery_value").val();
            if (ids) {
                idsArray = ids.split(",");
                idsArray.forEach(function(id) {
                    attachment = wp.media.attachment(id);
                    attachment.fetch();
                    selection.add(attachment ? [attachment] : []);
                });
            }
        });

        meta_gallery_frame.on("ready", function() {
            jQuery(".media-modal").addClass("no-sidebar");
        });

        // When an image is selected, run a callback.
        //meta_gallery_frame.on('update', function() {
        meta_gallery_frame.on("select", updateGallery);

        // Finally, open the modal
        meta_gallery_frame.open();
    });

    jQuery(document.body).on("click", ".shift8_portfolio_gallery_close", function(
        event
    ) {
        event.preventDefault();

        if (confirm("Are you sure you want to remove this image?")) {
            var removedImage = jQuery(this)
                .children("img")
                .attr("id");
            var oldGallery = jQuery("#portfolio_gallery_value").val();
            var newGallery = oldGallery
                .replace("," + removedImage, "")
                .replace(removedImage + ",", "")
                .replace(removedImage, "");
            jQuery(this)
                .parents()
                .eq(1)
                .remove();
            jQuery("#portfolio_gallery_value").val(newGallery);
        }
    });

    var updateGallery = function() {
        var imageIDArray = [];
        var imageHTML = "";
        var metadataString = "";
        images = meta_gallery_frame.state().get("selection");
        imageHTML += '<ul class="portfolio-gallery">';
        images.each(function(attachment) {
            imageIDArray.push(attachment.attributes.id);
            imageHTML +=
                '<li><span class="shift8_portfolio_gallery_container"><span class="shift8_portfolio_gallery_close"><img id="' +
                attachment.attributes.id +
                '" src="' +
                (attachment.attributes.sizes.thumbnail ?
                    attachment.attributes.sizes.thumbnail.url :
                    attachment.attributes.sizes.full.url) +
                '"></span></span></li>';
        });
        imageHTML += "</ul>";
        metadataString = imageIDArray.join(",");
        if (metadataString) {
            jQuery("#portfolio_gallery_value").val(metadataString);
            jQuery("#portfolio-gallery").html(imageHTML);

        }
    }
});