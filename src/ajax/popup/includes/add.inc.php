<?php

/*
* This file is part of the Bol-Partner-Plugin for Wordpress.
*
* (c) Netvlies Internetdiensten
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

// Read the XML file
$xmlLocation = 'https://www.bol.com/nl/cms/images/xml/201105%20-%20Couponcode%20XML.xml';

$buzzClient = new \Buzz\Client\Curl();
$buzzClient->setMaxRedirects(0);
$buzzClient->setTimeout(10);

$errorInRequest = false;
$browser = new \Buzz\Browser($buzzClient);

try {
    $response = $browser->get($xmlLocation);

    try{
        $adds = new SimpleXMLElement($response->getContent());

    } catch (\Exception $e) {
        // Echo the error an continue with loading the page so the user can still add products in the widget
        echo __('Error: Bol.com promotions could not be loaded', 'bolcom-partnerprogramma-wordpress-plugin') . ': Error parsing the xml as SimpleXMLElement';
        $errorInRequest = true;
    }

    if ($response->getStatusCode() !== 200) {
        echo __('Error: Bol.com promotions could not be loaded', 'bolcom-partnerprogramma-wordpress-plugin') . ': ' .
            $response->getStatusCode() . ' response for source: ' . $xmlLocation;
        $errorInRequest = true;
    }

} catch (\BolOpenApi\Exception $e) {
    echo  __('Error: Bol.com promotions could not be loaded', 'bolcom-partnerprogramma-wordpress-plugin') . '<br /><br />';
    echo $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() .'<br /><br />';
    $errorInRequest = true;

}  catch (\RuntimeException $e) {
    echo  __('Error: Bol.com promotions could not be loaded', 'bolcom-partnerprogramma-wordpress-plugin') . '<br /><br />';
    echo $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() .'<br /><br />';
    $errorInRequest = true;
}

// Don't render this when we have an error
if ($adds && !$errorInRequest) {

    $properties = array(
        'audio/navigatie'               => 'audio_navigatie',
        'baby'                          => 'baby',
        'boeken int'                    => 'boeken_int',
        'boeken nl'                     => 'boeken_nl',
        'camera'                        => 'camera',
        'computer'                      => 'computer',
        'crosscategorie'                => 'crosscategorie',
        'dier, tuin en klussen'         => 'dier_tuin_klussen',
        'dvd'                           => 'dvd',
        'ebooks'                        => 'ebooks',
        'elektronica'                   => 'elektronica',
        'games'                         => 'games',
        'home entertainment'            => 'home_entertainment',
        'huishoudelijk'                 => 'huishoudelijk',
        'koken, tafelen en huishouden'  => 'koken_tafelen_huishouden',
        'mooi en gezond'                => 'mooi_en_gezond',
        'muziek'                        => 'muziek',
        'speelgoed'                     => 'speelgoed',
        'telefoon/tablet'               => 'telefoon_tablet',
        'wonen'                         => 'wonen'
    );

    $currentDate = new DateTime();
    $currentDate->setTime(0, 0, 0);

    $sortedAdds = array();
    foreach ($adds as $add) {
        // Check or the advertisement group exists
        if (!empty($add->Product__groep) && array_key_exists( (string) preg_replace('/\s/', '', strtolower($add->Product__groep)), $properties) && !empty($add->Omschrijving)) {

            // Check or the add is valid for the currentDate
            if (!empty($add->Startdatum) && !empty($add->Einddatum)) {
                $startDate = new DateTime($add->Startdatum);
                $endDate = new DateTime($add->Einddatum);
            }

            if ($startDate && $endDate && $currentDate >= $startDate && $currentDate <= $endDate) {
                $key =  preg_replace('/\s/', '', strtolower($add->Product__groep));
                $sortedAdds[$properties[$key]][] = $add;
            }
        }
    }
} else {
    // Only show this error when other errors are not shown already
    if ($errorInRequest != true) {
        echo __('Error: Bol.com promotions could not be loaded', 'bolcom-partnerprogramma-wordpress-plugin');
    }
}

?>

<?php if (! $errorInRequest) { ?>
<div class="promotions postbox">
    <div title="Click to toggle" class="handlediv"><br></div>
    <h3 class="hndle"><span><?php _e('Show the Bol.com promotions of a selected category', 'bolcom-partnerprogramma-wordpress-plugin'); ?></span></h3>
    <div class="inside">
        <div class="adds hide">
            <p><?php _e('These promotions are currently active at Bol.com.', 'bolcom-partnerprogramma-wordpress-plugin'); ?>
                <?php _e('These promotion links are ready to use. Copy them and use them directly.', 'bolcom-partnerprogramma-wordpress-plugin'); ?></p>

            <?php
            foreach ($sortedAdds as $key => $addGroup) {
                $count = 0;
                ?>
                <div class="add <?php echo $key; ?> hide">
                    <?php foreach($addGroup as $add) { ?>
                        <?php if ($count == 0) { ?>
                            <h4><?php echo $add->Product__groep; ?></h4>
                        <?php } ?>

                        <?php $count++; ?>
                        <?php
                            // Build the url for promotions
                            $link = '';
                            if (!empty($add->Landingspagina)) {
                                $partnerSettings = get_option('bol_partner_settings');
                                $siteId = $partnerSettings['site_id'];

                                $link = "http://partnerprogramma.bol.com/click/click?p=1&t=url&s=" . $siteId . "&f=WP_Bolcomacties&name=bolcomacties&url=" . $add->Landingspagina;
                            }

                        ?>
                        <p><a href="<?php echo $link; ?>" target="_blank"><?php echo $add->Omschrijving; ?></a><br />
                            <?php _e('From', 'bolcom-partnerprogramma-wordpress-plugin'); ?>
                            <?php echo $add->Startdatum; ?> <?php _e('till', 'bolcom-partnerprogramma-wordpress-plugin'); ?> <?php echo $add->Einddatum; ?>

                            <?php if (!empty($add->Coupon_code) && $add->Coupon_code != 'automatisch geactiveerd') { ?>
                            <br />Coupon code: <?php echo $add->Coupon_code; ?>
                        <?php } ?>
                        </p>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>
