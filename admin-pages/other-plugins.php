<?php

// If this file is called directly, abort.
if (! defined('WPINC')) {
   die;
}

    $image_plugin_reviews = plugins_url() . '/product-videos-for-woocommerce/assets/reviews.png';
    $image_plugin_pricing = plugins_url() . '/product-videos-for-woocommerce/assets/pricing.png';
    $image_plugin_videos = plugins_url() . '/product-videos-for-woocommerce/assets/videos.png';

?>
<style>
    .api-table, .api-video-div {
        max-width: 700px;
        width: 100%;
        display: block;
        margin: 50px auto;
        padding: 10px;
        background: #fff;
        border: 1px solid #d8d8d8;
        font-size: 14px;
    }
    h2 {
        text-align: center;
    }
    .button {
        text-align: center;
        display: block;
        margin: auto;
        display: block;
    }
</style>

<h1 style="text-align: center;"><?php echo esc_html( get_admin_page_title() ); ?></h1>

<!-- get support -->
<table class="api-table">
    <tr>
        <td><a class="button" href="/wp-admin/plugin-install.php?s=apigenius.io&tab=search&type=term" target="_blank">Install Plugins</a></td>
        <td style="padding-left: 25px;">
            <p>Thank you for using the Product Videos for Woocommerce Plugin.  Here are other great plugins from API Genius.  Click the button above to install from the Wordpress repo.<p>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td width="50%">
            <div class="api-video-div">
                <h3 style="text-align: center;font-size: 26px;">Product Videos for Woocommerce</h3>
                <a href="https://wordpress.org/plugins/product-videos-for-woocommerce/" target="_blank" class="btn-more-information button">More Information</a><br /><br />
                <img src="<?php echo $image_plugin_videos; ?>" width="100%" height="auto" alt="">
                <iframe width="100%" height="400" src="https://www.youtube.com/embed/YU9Bt-kC_rY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br /><br />
            </div>
        </td>
        <td width="50%">
            <div class="api-video-div">
                <h3 style="text-align: center;font-size: 26px;">Competitive Pricing for WooCommerce</h3>
                <a href="https://wordpress.org/plugins/competitive-pricing-for-wc-and-gs/" target="_blank" class="btn-more-information button">More Information</a><br /><br />
                <img src="<?php echo $image_plugin_pricing; ?>" width="100%" height="auto" alt="">
                <iframe width="100%" height="400" src="https://www.youtube.com/embed/5a_YbSOFnQc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br /><br />
            </div>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="api-video-div">
                <h3 style="text-align: center;font-size: 26px;">Product Videos for Woocommerce</h3>
                <a href="https://wordpress.org/plugins/product-videos-for-woocommerce/" target="_blank" class="btn-more-information button">More Information</a><br /><br />
                <img src="<?php echo $image_plugin_videos; ?>" width="100%" height="auto" alt="">
                <iframe width="100%" height="400" src="https://www.youtube.com/embed/YU9Bt-kC_rY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br /><br />
            </div>
        </td>
        <td width="50%">

        </td>
    </tr>
</table>
