<?php
/**
 * Dashboard home tab template
 */

defined( 'ABSPATH' ) || die();
?>
<div class="ha-dashboard-panel">
    <div class="ha-home-banner">
        <div class="ha-home-banner__content">
            <img class="ha-home-banner__logo" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/halogo.svg" alt="">
            <span class="ha-home-banner__divider"></span>
            <h2>Thanks a lot <br><span>for choosing HappyAddons</span></h2>
        </div>
    </div>
    <div class="ha-home-body">
        <div class="ha-row ha-py-5 ha-align-items-center">
            <div class="ha-col ha-col-6">
                <img class="ha-img-fluid ha-title-icon-size" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/knowledge.svg" alt="">
                <h3 class="ha-feature-title">Knowledge & Wiki</h3>
                <p class="f18">We have created full-proof documentation for you. It will help you to understand how our plugin works.</p>
                <a class="ha-btn ha-btn-primary" target="_blank" rel="noopener" href="https://happyaddons.com/go/docs">Take Me to The Knowledge Page</a>
            </div>
            <div class="ha-col ha-col-6">
                <img class="ha-img-fluid" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/art1.png" alt="">
            </div>
        </div>
        <div class="ha-row ha-py-5 ha-pt-0">
            <div class="ha-col ha-col-12">
                <img class="ha-img-fluid ha-title-icon-size" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/film.svg" alt="">
                <h3 class="ha-feature-title">Video Tutorial</h3>
                <p class="f16">How to use Floating Effects and manage CSS Transform?</p>
            </div>
            <div class="ha-col ha-col-4">
                <a href="https://www.youtube.com/watch?v=KSRaUaD30Jc" class="ha-feature-sub-title-a">
                    <img class="ha-img-fluid ha-rounded" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/crossdomain-video-cover.jpg" alt="">
                    <h4 class="ha-feature-sub-title">Cross Domain Copy Paste (Pro)</h4>
                </a>
            </div>
            <div class="ha-col ha-col-4">
                <a href="https://www.youtube.com/watch?v=LmtacsLcFPU" class="ha-feature-sub-title-a">
                    <img class="ha-img-fluid ha-rounded" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/translate-video-cover.jpg" alt="">
                    <h4 class="ha-feature-sub-title">Happy Effects - CSS Transform</h4>
                </a>
            </div>
            <div class="ha-col ha-col-4">
                <a href="https://www.youtube.com/watch?v=F33g3zqkeog" class="ha-feature-sub-title-a">
                    <img class="ha-img-fluid ha-rounded" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/floating-video-cover.jpg" alt="">
                    <h4 class="ha-feature-sub-title">Happy Effects - Floating Effects</h4>
                </a>
            </div>
            <div class="ha-col ha-col-12 ha-align-center ha-pt-2">
                <a class="ha-btn ha-btn-secondary" target="_blank" rel="noopener" href="https://www.youtube.com/channel/UC1-e7ewkKB1Dao1U90QFQFA">View more videos</a>
            </div>
        </div>
        <div class="ha-row ha-align-items-end ha-py-5 ha-pt-0">
            <div class="ha-col ha-col-9">
                <img class="ha-img-fluid ha-title-icon-size" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/faq.svg" alt="">
                <h3 class="ha-feature-title ha-text-primary">FAQ</h3>
                <p class="f16 ha-mb-0">Frequently Asked Questions</p>
            </div>
            <div class="ha-col ha-col-3 ha-align-right">
                <a class="btn-more" target="_blank" rel="noopener" href="https://happyaddons.com/go/faq">Get More FAQ ></a>
            </div>
            <div class="ha-col ha-col-12">
                <div class="ha-row">
                    <div class="ha-col ha-col-6 ha-pt-3">
                        <h4 class="f18">Can I use these addons in my client project?</h4>
                        <p class="ha-mb-0 f16">Yes, absolutely, no holds barred. Use it to bring colorful moments to your customers. And don’t forget to check out our premium features.</p>
                    </div>
                    <div class="ha-col ha-col-6 ha-pt-3">
                        <h4 class="f18">Is there any support policy available for the free users?</h4>
                        <p class="ha-mb-0 f16">Free or pro version, both comes with excellent support from us. However, pro users will get priority support.</p>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $appsero = \Happy_Addons\Elementor\Base::instance()->appsero;
        $margin_top = '';
        if ( $appsero ) :
            if ( ! $appsero->insights()->notice_dismissed() && ! $appsero->insights()->tracking_allowed() ) :
                $optin_url  = add_query_arg( $appsero->slug . '_tracker_optin', 'true' );
                $margin_top = 'ha-py-5';
                ?>
                <div class="ha-row">
                    <div class="ha-col ha-col-12">
                        <div class="ha-cta ha-rounded">
                        <div class="ha-row ha-align-items-center">
                            <div class="ha-col-8">
                                <h3 class="ha-feature-title">Call for Contributors</h3>
                                <p class="f16">Are you interested to contribute to making this plugin more awesome?</p>
                                <a class="link btn-how-to-contribute" href="#">How am I going to contribute?</a>
                                <p class="ha-mb-0" style="display: none;">By allow Happy Elementor Addons to collect non-sensitive diagnostic data and usage information so that we can make sure optimum compatibility.
                                    Happy Elementor Addons collect - Server environment details (php, mysql, server, WordPress versions), Number of users in your site, Site language, Number of active and inactive plugins, Site name and url, Your name and email address. We are using Appsero to collect your data. <a href="https://appsero.com/privacy-policy/" target="_blank" style="color:#fff">Learn more</a> about how Appsero collects and handle your data.</p>
                            </div>
                            <div class="ha-cta-action ha-col-4 ha-align-right">
                                <a class="btn-contribute" href="<?php echo esc_url( $optin_url ); ?>">I like to contribute</a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <?php
            endif;
        endif;
        ?>

        <div class="ha-row <?php echo $margin_top; ?>">
            <div class="ha-col ha-col-6">
                <div class="ha-border-box ha-min-height-455">
                    <img class="ha-img-fluid ha-title-icon-size" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/support-call.svg" alt="">
                    <h3 class="ha-feature-title ha-text-secondary">Support And Feedback</h3>
                    <p class="f16">Feeling like to consult with an expert? Take live Chat support immediately from <a href="https://happyaddons.com/" target="_blank" rel="noopener">HappyAddons</a>. We are always ready to help you 24/7.</p>
                    <p class="f16 ha-mb-2"><strong>Or if you’re facing technical issues with our plugin, then please create a support ticket</strong></p>
                    <a class="ha-btn ha-btn-secondary" target="_blank" rel="noopener" href="https://happyaddons.com/go/contact-support">Get Support</a>
                </div>
            </div>
            <div class="ha-col ha-col-6">
                <div class="ha-border-box ha-min-height-455">
                    <img class="ha-img-fluid ha-title-icon-size" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/newspaper.svg" alt="">
                    <h3 class="ha-feature-title ha-text-primary">Newsletter Subscription</h3>
                    <p class="f16">To get updated news, current offers, deals, and tips please subscribe to our Newsletters.</p>
                    <a class="ha-btn ha-btn-primary" target="_blank" rel="noopener" href="https://happyaddons.com/go/subscribe">Subscribe Now</a>
                </div>
            </div>
        </div>

        <div class="ha-row ha-py-5 ha-align-items-center">
            <div class="ha-col ha-col-6">
                <img class="ha-img-fluid" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/art2.png" alt="">
            </div>
            <div class="ha-col ha-col-6">
                <img class="ha-img-fluid ha-title-icon-size" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/cross-game.svg" alt="">
                <h3 class="ha-feature-title">Missing Any Feature?</h3>
                <p class="f16">Are you in need of a feature that’s not available in our plugin? Feel free to do a
                    feature request from here,</p>
                <a class="ha-btn ha-btn-primary" target="_blank" rel="noopener" href="https://happyaddons.com/go/contact-support">Request Feature</a>
            </div>
        </div>

        <div class="ha-row ha-py-5">
            <div class="ha-col ha-col-12">
                <div class="ha-border-box">
                    <div class="ha-row ha-align-items-center">
                        <div class="ha-col ha-col-3" >
                            <img class="ha-img-fluid ha-pr-2" src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/c-icon.png" alt="">
                        </div>
                        <div class="ha-col ha-col-8">
                            <h3 class="ha-feature-title ha-text-secondary ha-mt-0">Happy with Our Work?</h3>
                            <p class="f16 ha-mb-2">We are really thankful to you that you have chosen our plugin. If our plugin brings a smile in your face while working, please share your happiness by giving us a 5***** rating in WordPress Org. It will make us happy and won’t take more than 2 mins.</p>
                            <a class="ha-btn ha-btn-secondary" target="_blank" rel="noopener" href="https://wordpress.org/support/plugin/happy-elementor-addons/reviews/?filter=5">I’m Happy to Give You 5*</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
