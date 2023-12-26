<script>
    googletag.cmd.push(function() {
<?php foreach ($args['ad_items'] as $ad_name => $ad_item): $ad_info = $ad_item['ud_ad_info']; ?>
<?php if ($ad_info->dfp_ad_use_responsive_ad_size): ?>
        var <?= esc_js($ad_name) ?>_size_mapping = googletag.sizeMapping()
            .addSize([1140,0], <?= json_encode($ad_info->dfp_ad_size_of_device_size['d']['ad_sizes']) ?>)
            .addSize([1019,0], <?= json_encode($ad_info->dfp_ad_size_of_device_size['tl']['ad_sizes']) ?>)
            .addSize([768,0], <?= json_encode($ad_info->dfp_ad_size_of_device_size['tp']['ad_sizes']) ?>)
            .addSize([0,0], <?= json_encode($ad_info->dfp_ad_size_of_device_size['m']['ad_sizes']) ?>)
            .build();
<?php endif ?>
<?php endforeach ?>

<?php if ($args['interstitial_ad_unit']): ?>
        var interstitialSlot = googletag.defineOutOfPageSlot("<?= esc_js($args['interstitial_ad_unit']) ?>",  googletag.enums.OutOfPageFormat.INTERSTITIAL);
        if(interstitialSlot){
            interstitialSlot.addService(googletag.pubads());
        }
<?php endif ?>

        var screenWidth = document.body.clientWidth;
        if (screenWidth >= 1140) {
<?php foreach ($args['ad_item_device_size_mapping']['d'] as $ad_name => $ad_item): $ad_info = $ad_item['ud_ad_info'];?>
            googletag.defineSlot("<?= esc_js($ad_info->dfp_ad_unit_path) ?>", <?= json_encode($args['dfp_global_ad_size_of_ad_name'][$ad_name]) ?> , "ud-dfp-ad-pos-<?= esc_js($ad_name)?>")
                .defineSizeMapping(<?= esc_js($ad_name) ?>_size_mapping)
                .setCollapseEmptyDiv(<?= $ad_info->dfp_ad_collapse_empty_div === 'collapse' ? 'true' : ($ad_info->dfp_ad_collapse_empty_div === 'collapse_before' ? 'true, true' : 'false'); ?>)
                .addService(googletag.pubads());
<?php endforeach ?>
        }
        if (screenWidth >= 1019 && screenWidth < 1140) {
<?php foreach ($args['ad_item_device_size_mapping']['tl'] as $ad_name => $ad_item): $ad_info = $ad_item['ud_ad_info'];?>
            googletag.defineSlot("<?= esc_js($ad_info->dfp_ad_unit_path) ?>", <?= json_encode($args['dfp_global_ad_size_of_ad_name'][$ad_name]) ?> , "ud-dfp-ad-pos-<?= esc_js($ad_name)?>")
                .defineSizeMapping(<?= esc_js($ad_name) ?>_size_mapping)
                .setCollapseEmptyDiv(<?= $ad_info->dfp_ad_collapse_empty_div === 'collapse' ? 'true' : ($ad_info->dfp_ad_collapse_empty_div === 'collapse_before' ? 'true, true' : 'false'); ?>)
                .addService(googletag.pubads());
<?php endforeach ?>
        }
        if (screenWidth >= 768 && screenWidth < 1019) {
<?php foreach ($args['ad_item_device_size_mapping']['tp'] as $ad_name => $ad_item): $ad_info = $ad_item['ud_ad_info'];?>
            googletag.defineSlot("<?= esc_js($ad_info->dfp_ad_unit_path) ?>", <?= json_encode($args['dfp_global_ad_size_of_ad_name'][$ad_name]) ?> , "ud-dfp-ad-pos-<?= esc_js($ad_name)?>")
                .defineSizeMapping(<?= esc_js($ad_name) ?>_size_mapping)
                .setCollapseEmptyDiv(<?= $ad_info->dfp_ad_collapse_empty_div === 'collapse' ? 'true' : ($ad_info->dfp_ad_collapse_empty_div === 'collapse_before' ? 'true, true' : 'false'); ?>)
                .addService(googletag.pubads());
<?php endforeach ?>
        }
        if (screenWidth < 768) {
<?php foreach ($args['ad_item_device_size_mapping']['m'] as $ad_name => $ad_item): $ad_info = $ad_item['ud_ad_info'];?>
            googletag.defineSlot("<?= esc_js($ad_info->dfp_ad_unit_path) ?>", <?= json_encode($args['dfp_global_ad_size_of_ad_name'][$ad_name]) ?> , "ud-dfp-ad-pos-<?= esc_js($ad_name)?>")
                .defineSizeMapping(<?= esc_js($ad_name) ?>_size_mapping)
                .setCollapseEmptyDiv(<?= $ad_info->dfp_ad_collapse_empty_div === 'collapse' ? 'true' : ($ad_info->dfp_ad_collapse_empty_div === 'collapse_before' ? 'true, true' : 'false'); ?>)
                .addService(googletag.pubads());
<?php endforeach ?>
        }

<?php foreach ($args['ad_item_device_size_mapping']['global'] as $ad_name => $ad_item): $ad_info = $ad_item['ud_ad_info'];?>
            googletag.defineSlot("<?= esc_js($ad_info->dfp_ad_unit_path) ?>", <?= json_encode($args['dfp_global_ad_size_of_ad_name'][$ad_name]) ?> , "ud-dfp-ad-pos-<?= esc_js($ad_name)?>")
                .setCollapseEmptyDiv(<?= $ad_info->dfp_ad_collapse_empty_div === 'collapse' ? 'true' : ($ad_info->dfp_ad_collapse_empty_div === 'collapse_before' ? 'true, true' : 'false'); ?>)
                .addService(googletag.pubads());
<?php endforeach ?>


        googletag.pubads().enableSingleRequest();
<?php foreach ($args['targets'] as $key => $value): ?>
        googletag.pubads().setTargeting("<?= esc_js($key) ?>", <?= json_encode($value, JSON_UNESCAPED_UNICODE) ?>);
<?php endforeach ?>
        googletag.enableServices();


<?php if ($args['interstitial_ad_unit']): ?>
        if(interstitialSlot){
            googletag.display(interstitialSlot);
        }
<?php endif ?>
    });
</script>
<script>
googletag.cmd.push(function() {
    var screenWidth = document.body.clientWidth;

    function setupFallbackImage(targetElementId, useResponsiveFallbackImage, fallbackImages) {
        var isEmpty = false;
        googletag.pubads().addEventListener('slotRenderEnded', function(event) {
            if (event.slot.getSlotElementId() !== targetElementId) {
                return;
            }
            isEmpty = event.isEmpty;

            var divElem = document.getElementById(targetElementId);
            divElem.classList.remove('skeleton-ad');

            console.log(isEmpty);

            if (isEmpty) {
                if (useResponsiveFallbackImage) {
                    if (screenWidth >= 1140 && fallbackImages.d) {
                        divElem.innerHTML = fallbackImages.d;
                    } else if (screenWidth < 1140 && screenWidth >= 1019 && fallbackImages.tl) {
                        divElem.innerHTML = fallbackImages.tl;
                    } else if (screenWidth < 1019 && screenWidth >= 768 && fallbackImages.tp) {
                        divElem.innerHTML = fallbackImages.tp;
                    } else if (screenWidth < 768 && fallbackImages.m) {
                        divElem.innerHTML = fallbackImages.m;
                    }
                }else {
                    if (fallbackImages.global) {
                        divElem.innerHTML = fallbackImages.global;
                    }
                }
            }
        });
    }

    if (screenWidth >= 1140) {
<?php foreach ($args['ad_item_device_size_mapping']['d'] as $ad_name => $ad_item): $ad_info = $ad_item['ud_ad_info'];?>
        setupFallbackImage("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>", <?= json_encode($ad_info->dfp_ad_use_responsive_fallback_image) ?>, <?= json_encode($args['dfp_fallback_image_html_of_ad_name'][$ad_name]) ?>);
        googletag.display("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>");
<?php endforeach ?>
    }
    if (screenWidth >= 1019 && screenWidth < 1140) {
<?php foreach ($args['ad_item_device_size_mapping']['tl'] as $ad_name => $ad_item): ?>
        setupFallbackImage("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>", <?= json_encode($ad_info->dfp_ad_use_responsive_fallback_image) ?>, <?= json_encode($args['dfp_fallback_image_html_of_ad_name'][$ad_name]) ?>);
        googletag.display("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>");
<?php endforeach ?>
    }
    if (screenWidth >= 768 && screenWidth < 1019) {
<?php foreach ($args['ad_item_device_size_mapping']['tp'] as $ad_name => $ad_item): ?>
        setupFallbackImage("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>", <?= json_encode($ad_info->dfp_ad_use_responsive_fallback_image) ?>, <?= json_encode($args['dfp_fallback_image_html_of_ad_name'][$ad_name]) ?>);
        googletag.display("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>");
<?php endforeach ?>
    }
    if (screenWidth < 768) {
<?php foreach ($args['ad_item_device_size_mapping']['m'] as $ad_name => $ad_item): ?>
        setupFallbackImage("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>", <?= json_encode($ad_info->dfp_ad_use_responsive_fallback_image) ?>, <?= json_encode($args['dfp_fallback_image_html_of_ad_name'][$ad_name]) ?>);
        googletag.display("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>");
<?php endforeach ?>
    }

<?php foreach ($args['ad_item_device_size_mapping']['global'] as $ad_name => $ad_item): ?>
    setupFallbackImage("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>", <?= json_encode($ad_info->dfp_ad_use_responsive_fallback_image) ?>, <?= json_encode($args['dfp_fallback_image_html_of_ad_name'][$ad_name]) ?>);
    googletag.display("ud-dfp-ad-pos-<?= esc_js($ad_name) ?>");
<?php endforeach ?>
});
</script>
